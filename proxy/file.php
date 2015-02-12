<?php
/*--------------------------------------------------------+
| SYSTOPIA CiviProxy                                      |
|  a simple proxy solution for external access to CiviCRM |
| Copyright (C) 2015 SYSTOPIA                             |
| Author: B. Endres (endres -at- systopia.de)             |
| http://www.systopia.de/                                 |
+---------------------------------------------------------*/

require_once "config.php";
require_once "proxy.php";

// basic check
civiproxy_security_check('file');

// basic restraints
$valid_parameters = array( 'id'   => 'string' );
$parameters = civiproxy_get_parameters($valid_parameters);

// check if id specified
if (empty($parameters['id'])) civiproxy_http_error("Resource not found");

// load PEAR file cache
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . 'libs');
if (!file_exists($file_cache_options['cacheDir'])) mkdir($file_cache_options['cacheDir']);
require_once('Cache/Lite.php');
$file_cache = new Cache_Lite($file_cache_options);

// look up the required resource
$header_key = 'header&' . $parameters['id'];
$data_key   = 'data&'   . $parameters['id'];

$header = $file_cache->get($header_key);
$data   = $file_cache->get($data_key);

if ($header && $data) {
  error_log("CACHE HIT");
  $header_lines = json_decode($header);
  foreach ($header_lines as $header_line) {
    header($header_line);
  }

  print $data;
  exit();
}

// if we get here, we have a cache miss => load
$url = $target_file . $parameters['id'];
error_log("CACHE MISS. LOADING $url");

$curlSession = curl_init();
curl_setopt($curlSession, CURLOPT_URL, $url);
curl_setopt($curlSession, CURLOPT_HEADER, 1);
curl_setopt($curlSession, CURLOPT_RETURNTRANSFER,1);
curl_setopt($curlSession, CURLOPT_TIMEOUT, 30);
curl_setopt($curlSession, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curlSession, CURLOPT_CAINFO, 'target.pem');

//Send the request and store the result in an array
$response = curl_exec($curlSession);

// Check that a connection was made
if (curl_error($curlSession)) {
  error_log(curl_error($curlSession));
  civiproxy_http_error(curl_error($curlSession), curl_errno($curlSession));
}

// process the results
$content = explode("\r\n\r\n", $response, 2);
$header  = $content[0];
$body    = $content[1];

// extract headers
$header_lines = explode(chr(10), $header);

// store the information in the cache
$file_cache->save(json_encode($header_lines), $header_key);
$file_cache->save($body, $data_key);

// and reply
foreach ($header_lines as $header_line) {
  header($header_line);
}

print $body;
curl_close ($curlSession);
