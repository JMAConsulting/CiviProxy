<?php

$postfields = file_get_contents('php://input');
ini_set('include_path', dirname(dirname(__FILE__)));
require_once "proxy.php";

$target = $target_civicrm . '?civiwp=CiviCRM&q=civicrm/sparkpost/callback';
$curlSession = curl_init();
curl_setopt($curlSession, CURLOPT_CUSTOMREQUEST,           "POST");
curl_setopt($curlSession, CURLOPT_POSTFIELDS,     $postfields);
curl_setopt($curlSession, CURLOPT_URL,            $target);
curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curlSession, CURLOPT_HTTPHEADER, array(                                                                          
  'Content-Type: application/json',                                                                                
  'Content-Length: ' . strlen($postfields))                                                                       
);

curl_exec($curlSession);

if (curl_error($curlSession)){
  file_put_contents('error.txt', (curl_error($curlSession)));
}

?>
