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

$files = (array) $parameters->files;
$serverFiles = [
  'tb_test' => '',
  'police_check' => '',
  'first_aid' => '',
  'police_check_reimbursement' => '',
];

foreach ($files as $key => $file) {
  $fileName = getcwd() . '/yhvfiles/' . basename($file);
  file_put_contents($fileName, file_get_contents($file));
  $serverFiles[$key] = $proxy_base . '/yhvfiles/' . basename($file);
  $filesToDelete[] = $fileName; 
}

$ff = civicrm_api3('FormProcessor', $parameters->type, [
  'cid' => $parameters->cid,
  'tb_test' => $serverFiles['tb_test'],
  'police_check' => $serverFiles['police_check'],
  'police_check_reimbursement' => $serverFiles['police_check_reimbursement'],
  'first_aid' => $serverFiles['first_aid'],
  'activity_date' => date('Y-m-d', strtotime($parameters->activity_date)),
  'api_key' => 'eeeddd',//$mail_subscription_user_key,
]);

// Delete the files off the server.
foreach ($filesToDelete as $file) {
  unlink($file);        
}

