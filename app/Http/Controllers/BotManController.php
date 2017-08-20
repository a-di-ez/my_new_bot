<?php

namespace App\Http\Controllers;

use App\Conversations\ExampleConversation;
use Illuminate\Http\Request;
use Mpociot\BotMan\BotMan;
use GuzzleHttp\Client;

class BotManController extends Controller
{
    public function handle()
    {
        $botman = app('botman');

        // Simple respond method
        $botman->hears('Привет', function (BotMan $bot) {
            $bot->types();
            $bot->reply('Здрасте:) Как вас величать?');
        });

        $botman->hears('Я {name}', function (BotMan $bot, $name) {
            $bot->types();
            $bot->reply('Очень приятно, ' . $name . ':) Что вы здесь забыли?');
        });

        $botman->hears('Give me {currency} rates', function (BotMan $bot, $currency) {
            $bot->types();
            $results = $this->getCurrency($currency);
            $bot->reply($results);
        });

        $botman->fallback(function (BotMan $bot) {
            $bot->types();
            $bot->reply('Моя твоя непонимать...');
        });

        $botman->listen();
    }

    public function getCurrency($currency)
    {
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
    }
}