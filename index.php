<?php

$oldDomain = 'apparatov.net';

$user_agent = (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null);

function curlProxy($mirror, $userAgent)
{
    global $oldDomain, $redirectDomain;
    $url = "https://{$mirror}{$_SERVER['REQUEST_URI']}";
    // create a new cURL resource
    $ch = curl_init();
    // set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_USERAGENT, $userAgent . ' AppEngine-Google');
    //curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    //    'X_ENGINE: google',
    //));
    $result = curl_exec($ch);
    $result = str_replace($oldDomain, $redirectDomain, $result);
    $info = curl_getinfo($ch);
    $contentType = $info['content_type'];
    @header("Content-Type: $contentType");
    // close cURL resource, and free up system resources
    curl_close($ch);
    return $result;
}

echo curlProxy($oldDomain, $user_agent);