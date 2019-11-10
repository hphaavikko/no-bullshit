<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Yes, I really do need this here since my development environment PHP runs on a different server than the Vue app :D

$url = 'https://koodihaaste-api.solidabis.com/bullshit';

$jwt = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJidWxsc2hpdCI6ImJ1bGxzaGl0IiwiaWF0IjoxNTcxMzE4MTUzfQ.QSi_9KFykbOKQgPZ-AEntmxrbFmz0rAI5aJt03OMVIo';

$headers = array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $jwt
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$result = curl_exec($ch);

echo $result;

?>
