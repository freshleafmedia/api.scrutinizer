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

$app->post('/tests/xmlSitemapExists', [ 'uses' => 'TestController@testXmlSitemapExists']);
$app->post('/tests/compressedXmlSitemapExists', [ 'uses' => 'TestController@testCompressedXmlSitemapExists']);
$app->post('/tests/robotsTextExists', [ 'uses' => 'TestController@testRobotsTextExists']);
