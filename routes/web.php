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

$app->post('/tests/seo/sitemap', [ 'uses' => 'SeoController@testSitemap']);
$app->post('/tests/seo/robotsText', [ 'uses' => 'SeoController@testRobotsText']);

$app->post('/tests/performance/responseTime', [ 'uses' => 'PerformanceController@testResponseTime']);
