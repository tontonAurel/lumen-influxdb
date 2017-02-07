<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


use Illuminate\Http\Request;

$app->get('/', function () use ($app) {
  $client = new InfluxDB\Client('localhost',  8086);
  $database = $client->selectDB('demo');
  $result = $database->query('select * from logs');
  dd ($result->getPoints());
});

$app->get('/loggin', function (Request $request) use ($app) {
  $client = new InfluxDB\Client('localhost',  8086);
  $database = $client->selectDB('demo');
  $now = new Datetime();
  $points = array(
    new InfluxDB\Point(
        'logs', // name of the measurement
        1, // the measurement value
        ['type' => 'action_1', 'user_id' => 1, /* 'venue_id' => 1*/], // optional tags
        ['ip' => $request->ip(), 'user_agent' => $request->header('User-Agent')], // optional additional fields
        $now->getTimestamp()

    ),
  );
  // we are writing unix timestamps, which have a second precision
  $result = $database->writePoints($points, InfluxDB\Database::PRECISION_SECONDS);
});

$app->get('/venue', function (Request $request) use ($app) {
  $client = new InfluxDB\Client('localhost',  8086);
  $database = $client->selectDB('demo');
  $now = new Datetime();
  $points = array(
    new InfluxDB\Point(
        'logs', // name of the measurement
        1, // the measurement value
        ['type' => 'action_2', 'user_id' => 1, 'venue_id' => 1], // optional tags
        ['ip' => $request->ip(), 'user_agent' => $request->header('User-Agent')], // optional additional fields
        $now->getTimestamp()

    ),
  );
  // we are writing unix timestamps, which have a second precision
  $result = $database->writePoints($points, InfluxDB\Database::PRECISION_SECONDS);
});
