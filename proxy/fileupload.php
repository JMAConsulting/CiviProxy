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

// basic restraints
$valid_parameters = [
  'fileparams' => 'string',
];
$parameters = civiproxy_get_parameters($valid_parameters);

// check if parameters specified
if (empty($parameters['fileparams']))   civiproxy_http_error("Missing/invalid parameter 'fileparams'.");
$parameters = json_decode($parameters['fileparams']);

$contact = civicrm_api3('Contact', 'get', [
  'first_name' => $parameters->first_name,
  'last_name' => $parameters->last_name,
  'email' => $parameters->email,
  'api_key' => 'eeeddd',//$mail_subscription_user_key,
  'sequential' => 1,
]);

if (!empty($contact['id'])) {
  $cid = $contact['id'];
}
else {
  civiproxy_http_error("No contact found.");
}

$files = (array) $parameters->files;

foreach ($files as $key => $file) {
  if (empty($file)) {
    continue;
  }
  //$fileName = getcwd() . '/yhvfiles/' . basename($file);
  //file_put_contents($fileName, file_get_contents($file));
  //$serverFiles[$key] = $proxy_base . '/yhvfiles/' . basename($file);
  $serverFiles[$key] = $file;
  //$filesToDelete[] = $fileName; 
}

$activityParams = [
  'cid' => $cid,
  'tb_test' => $serverFiles['tb_test'],
  'police_check' => $serverFiles['police_check'],
  'first_aid' => $serverFiles['first_aid'],
  'api_key' => 'eeeddd',//$mail_subscription_user_key,
];

if (!empty($parameters->dates->tb_test)) {
  $activityParams['tb_test_date'] = date('Y-m-d', strtotime($parameters->dates->tb_test));
}

if (!empty($parameters->dates->police_check)) {
  $activityParams['police_check_date'] = date('Y-m-d', strtotime($parameters->dates->police_check));
}

civicrm_api3('FormProcessor', 'volunteer_activity', $activityParams);

// Delete the files off the server.
/* foreach ($filesToDelete as $file) {
  unlink($file);	
} */


/*$file_name = '/home/yeehong-wp-proxy.jmaconsulting.biz/htdocs/yhvdir/' . basename($_REQUEST['tb_test']); 

$ff = file_put_contents( $file_name,file_get_contents($_REQUEST['tb_test']));

print_R($file_name);
print_R($ff); */
