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

            $client = new \GuzzleHttp\Client();

            $request = new \GuzzleHttp\Psr7\Request('GET', $this->url->address);

            $promise = $client->sendAsync($request)->then(function($response) {
               
                $this->url->fill([

                    'status' => 'recebido',

                    'code' => $response->getStatusCode(),

                    'message' => $response->getReasonPhrase(),

                    'filetype' => $response->getHeader('Content-Type')[0],

                    'filename' => uniqid(),

                ]);

                if( $this->url->save() )
                {
                    if( Storage::disk('local')->put( 'public/' . $this->url->filename, (string) $response->getBody() ))
                    {
                        event(new Done( $this->url ));
                    }
                }
                
            });

            $this->url->update([

                'status' => 'processando',

            ]);

            $promise->wait();

            
        } catch (\GuzzleHttp\Exception\BadResponseException $exception) {

            $this->url->update([

                'status' => 'erro',

                'code' => $exception->getResponse()->getStatusCode(),

                'message' => $exception->getResponse()->getReasonPhrase(),

            ]);

            event(new Done( $this->url ));

        } catch (\GuzzleHttp\Exception\ClientException $e) {

            $this->url->update([

                'status' => 'erro',

                'code' => $exception->getResponse()->getStatusCode(),

                'message' => $exception->getResponse()->getReasonPhrase(),

            ]);

            event(new Done( $this->url ));

        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            
            $this->url->update([

                'status' => 'problema',

                'message' => 'Endereço não resolvido'

            ]);

            event(new Done( $this->url ));

        }

    }
}
