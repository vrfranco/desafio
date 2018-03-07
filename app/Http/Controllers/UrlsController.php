<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Jobs\Download;
use App\Url;

class UrlsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Url::orderBy('created_at', 'desc')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Url::saved(function($url)
        {
            Download::dispatch( $url );//->delay(now()->addSeconds(10));
        });        

        return Url::create([
            'address' => $request->address,
            'status' => 'processando',
        ]);;
    }
}
