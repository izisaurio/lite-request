<?php

require '../vendor/autoload.php';

use LiteRequest\Request;

$post = Request::post('https://jsonplaceholder.typicode.com/posts', [
    CURLOPT_SSL_VERIFYPEER => false
]);
$response = $post->exec();

var_dump($response->body, $response->headers);

$get = Request::get('https://jsonplaceholder.typicode.com/todos/1', [
    CURLOPT_SSL_VERIFYPEER => false
]);
$response = $get->exec();

var_dump($response->json());