<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use App\Events\Done;
use App\Jobs\Save;
use App\Url;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

class Download implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Url $url)
    {
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            
            $client = new Client();
            
            $request = new Request('GET', $this->url->address);

            $disk = Storage::disk('s3');

            $promise = $client->sendAsync($request)->then(function($response) use ($disk) {

                $this->url->fill([

                    'status' => 'recebido',

                    'code' => $response->getStatusCode(),

                    'message' => $response->getReasonPhrase(),

                    'filetype' => $response->getHeader('Content-Type')[0],

                    'filesize' => $response->getBody()->getSize(),

                    'filename' => uniqid(),

                ]);

                if( $this->url->save() )
                {
                    $filename = $this->url->filename;

                    $content = $response->getBody()->getContents();

                    if( $disk->put( $filename, $content ))
                    {
                        event(new Done( $this->url ));
                    }
                }
                
            });

            $this->url->update([

                'status' => 'processando',

            ]);

            $promise->wait();

            
        } catch (BadResponseException $exception) {

            $this->url->update([

                'status' => 'erro',

                'code' => $exception->getResponse()->getStatusCode(),

                'message' => $exception->getResponse()->getReasonPhrase(),

            ]);

            event(new Done( $this->url ));

        } catch (ClientException $e) {

            $this->url->update([

                'status' => 'erro',

                'code' => $exception->getResponse()->getStatusCode(),

                'message' => $exception->getResponse()->getReasonPhrase(),

            ]);

            event(new Done( $this->url ));

        } catch (ConnectException $e) {
            
            $this->url->update([

                'status' => 'problema',

                'message' => 'Endereço não resolvido'

            ]);

            event(new Done( $this->url ));

        }

    }
}
