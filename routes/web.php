<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use GuzzleHttp\Client;

Route::match(['get', 'post'], '/botman', 'BotManController@handle');

Route::get('/test', function () {
    $client = new Client();
    $uri = 'https://api.privatbank.ua/p24api/pubinfo?exchange&json&coursid=11';
    $response = $client->get($uri);
    $results = json_decode($response->getBody()->getContents());
    $date = date('d F Y', strtotime($results->date));
    $data = "Here's the exchange rates based on $currency currency\nDate: $date \n";

    foreach ($results->rates as $k => $v) {
        $data .= $k . " - " . $v . "\n";
    }

    return $data;
});