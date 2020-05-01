<?php
/*--------------------------------------------------------+
| SYSTOPIA CiviProxy                                      |
|  a simple proxy solution for external access to CiviCRM |
| Copyright (C) 2015 SYSTOPIA                             |
| Author: B. Endres (endres -at- systopia.de)             |
| http://www.systopia.de/                                 |
+---------------------------------------------------------*/

ini_set('include_path', dirname(dirname(__FILE__)));
require_once "proxy.php";

if (empty($mail_subscription_user_key)) civiproxy_http_error("Feature disabled", 405);

// basic check
civiproxy_security_check('mail-optout');

// basic restraints
$valid_parameters = array(    'jid'          => 'int',
                              'qid'          => 'int',
                              'h'            => 'hex');
$parameters = civiproxy_get_parameters($valid_parameters);

// check if parameters specified
if (empty($parameters['jid'])) civiproxy_http_error("Missing/invalid parameter 'jid'.");
if (empty($parameters['qid'])) civiproxy_http_error("Missing/invalid parameter 'qid'.");
if (empty($parameters['h']))   civiproxy_http_error("Missing/invalid parameter 'h'.");

// PERFORM OPT OUT
$group_query = civicrm_api3('MailingEventUnsubscribe', 'create',
                          array( 'job_id'         => $parameters['jid'],
                                 'event_queue_id' => $parameters['qid'],
                                 'hash'           => $parameters['h'],
                                 'org_unsubscribe'=> TRUE,
                                 'api_key'        => $mail_subscription_user_key,
                                ));
if (!empty($group_query['is_error'])) {
  civiproxy_http_error($group_query['error_message'], 500);
}
?>

<!DOCTYPE html>
<html>
 <head>
  <meta charset="UTF-8">
  <title>Yee Hong Center for Geriatric Care</title>
  <link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">
  <style type="text/css">
    body {
      margin: 0;
      padding: 0;
    }

    .container {
        position: relative;
        width: 100%;
    }

    .center {
      margin-left: auto;
      margin-right: auto;
      width: 970px;
    }

    p, .optoutform {
      font-family: "Open Sans", sans-serif;
      font-size: 160%;
    }

    #info {
      padding-top: 20px;
      vertical-align: top;
      text-align: center;
      width: 462px;
    }

    .optout {
      border-radius: 25px;
      padding: 20px; 
      width: 820px;
      height: 195px;
      border: grey solid 2px; 
    }
 
    .controls {
      margin-top: -15px;
      margin-left: 410px;
    }

    .button {
      margin-left: 40px;
      border-radius: 25px;
      border: grey none 2px;
      padding: 10px;
      font-size: 20px;
    }
  </style>
 </head>
 <body>
 <div id="container">
    <div id="info" class="center">
      <a href="http://www.yeehong.com/"><?php echo $civiproxy_logo;?></a>

    </div>
    <div id="content" class="center">
    <?php

      if (isset($_POST['is_opt_out']) && $_POST['is_opt_out'] == 'yes') {
        $html = "<p style='text-align:center'>Thank you. You have been successfully opted out of all mailings.</p>";
      }
      else if (isset($_POST['is_opt_out']) && $_POST['is_opt_out'] == 'no') {
        $html = "<p style='text-align:center'>Your opt out request has been cancelled.</p>";
      }
      else {
        $html = "<p style='text-align:center'>Would you like to opt-out of all mailings?</p>
        <p style='font-size:100%;text-align:center'>If you click YES, you will no longer receive any emails from Yee Hong Center for Geriatric Care.</p><br/>
        <form method='post' class='optoutform'>
        <div class='controls'>
          <input type='radio' name='is_opt_out' value='yes' checked> YES
          <input type='radio' name='is_opt_out' value='no' checked> NO <br/>
          <input type='submit' value='Submit' style='margin-left:50px'>
        </div>
        </form>";
      }
      echo $html;
    ?>
      
    </div>
  </div>
  
 </body>
</html>
