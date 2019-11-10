<?php

ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0)');
header('Content-Type: text/html; charset=UTF-8');
header('Access-Control-Allow-Origin: *'); // Yes, I really do need this here since my development environment PHP runs on a different server than the Vue app :D

/**
* Get a web file (HTML, XHTML, XML, image, etc.) from a URL.  Return an
* array containing the HTTP server response header fields and content.
*/
function get_web_page( $url )
{
   $user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

   $options = array(

       CURLOPT_CUSTOMREQUEST  =>"GET",        //set request type post or get
       CURLOPT_POST           =>false,        //set to GET
       CURLOPT_USERAGENT      => $user_agent, //set user agent
       CURLOPT_COOKIEFILE     =>"cookie.txt", //set cookie file
       CURLOPT_COOKIEJAR      =>"cookie.txt", //set cookie jar
       CURLOPT_RETURNTRANSFER => true,     // return web page
       CURLOPT_HEADER         => false,    // don't return headers
       CURLOPT_FOLLOWLOCATION => true,     // follow redirects
       CURLOPT_ENCODING       => "",       // handle all encodings
       CURLOPT_AUTOREFERER    => true,     // set referer on redirect
       CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
       CURLOPT_TIMEOUT        => 120,      // timeout on response
       CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
       CURLOPT_SSL_VERIFYPEER => false
   );

   $ch      = curl_init( $url );
   curl_setopt_array( $ch, $options );
   $content = curl_exec( $ch );
   $err     = curl_errno( $ch );
   $errmsg  = curl_error( $ch );
   $header  = curl_getinfo( $ch );
   curl_close( $ch );

   $header['errno']   = $err;
   $header['errmsg']  = $errmsg;
   $header['content'] = $content;
   return $header;
}

$url = 'https://www.google.fi/search?q=' . $_GET['q'];

$result = get_web_page( $url );

if ($result['http_code'] == 200)
{
   echo $result['content'];
}
else
{
   echo $result['http_code'] . '<br />';
   echo 'Error ' . $result['errno'] . ': ' . $result['errmsg'];
}

/*
$headers = array(
    'Content-Type: text/html'
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
*/

?>
