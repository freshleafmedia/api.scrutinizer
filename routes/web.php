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

$app->get('/tests/seo/sitemap', [ 'uses' => 'SeoController@testSitemap']);
$app->get('/tests/seo/robotsText', [ 'uses' => 'SeoController@testRobotsText']);

$app->get('/tests/performance/responseTime', [ 'uses' => 'PerformanceController@testResponseTime']);

$app->get('/tests/security/sslLabs', [ 'uses' => 'SecurityController@testSslLabs']);
