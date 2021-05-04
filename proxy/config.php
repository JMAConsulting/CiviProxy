<?php
/*--------------------------------------------------------+
| SYSTOPIA CiviProxy                                      |
|  a simple proxy solution for external access to CiviCRM |
| Copyright (C) 2015 SYSTOPIA                             |
| Author: B. Endres (endres -at- systopia.de)             |
| http://www.systopia.de/                                 |
+---------------------------------------------------------*/


/****************************************************************
 **                            URLS                            **
 ****************************************************************/
// this should point to the base address of the CiviProxy installation
$proxy_base     = 'https://yhccrm.yeehong.com';

// this should point to the target CiviCRM system
$target_civicrm = 'https://yhccivicrm.yeehong.com';


/****************************************************************
 **                      DEFAULT PATHS                         **
 **                                                            **
 **          set to NULL to disable a feature                  **
 ****************************************************************/

// default paths, override if you want. Set to NULL to disable
$target_rest      = $target_civicrm . '/wp-content/plugins/civicrm/civicrm/extern/rest.php';
$target_url       = $target_civicrm . '/civicrm/mailing/url';
$target_open      = $target_civicrm . '/civicrm/mailing/open';
$target_file      = $target_civicrm . '/wp-content/uploads/civicrm/persist/';
$target_mail_view = $target_civicrm . '/civicrm/mailing/view';
$target_mosaico_file = $target_civicrm . '/civicrm/mosaico/img?src=';
$social_icons = $target_civicrm . '/wp-content/uploads/civicrm/ext/uk.co.vedaconsulting.mosaico/packages/mosaico/templates/versafix-1/img/social_def/';

// Set api-key for mail subscribe/unsubscribe user
// Set to NULL/FALSE to disable the feature
$mail_subscription_user_key = "hGHgY6yw";

// CAREFUL: only enable temporarily on debug systems. Will log all queries to given PUBLIC file
$debug                      = 'debug.log';

// Local network interface or IP to be used for the relayed query 
// This is usefull in some VPN configurations (see CURLOPT_INTERFACE)
$target_interface           = NULL;

/****************************************************************
 **                   File Caching Options                     **
 ****************************************************************/

// API and SITE keys
$api_key_map = array();
$sys_key_map = array();

if (file_exists(dirname(__FILE__)."/secrets.php")) {
  // keys can also be stored in 'secrets.php'
  require "secrets.php";
}

// define file cache options, see http://pear.php.net/manual/en/package.caching.cache-lite.cache-lite.cache-lite.php
$file_cache_options = array(
  'cacheDir' => 'file_cache/',
  'lifeTime' => 86400
);

// define regex patterns that shoud NOT be accepted
$file_cache_exclude = array();

// if set, cached file must match at least one of these regex patterns
$file_cache_include = array(
  //'#.+[.](png|jpe?g|gif)#i'           // only media files
);



/****************************************************************
 **                   REST API OPTIONS                         **
 ****************************************************************/
$rest_allowed_actions = array(
  'system' => array(
    'check' => array(),
  ),
  // this is an example:
  'Contact' => array(
    'getsingle'      => array(
      'email' => 'string'
    ),
    'getwpuser' => array(
      'username' => 'string',
      'password' => 'string',
      'json' => 'string',
    ),
    'createwpuser' => array(
      'cid' => 'int',
      'email' => 'string',
      'first_name' => 'string',
      'last_name' => 'string',
      'json' => 'string',
    ),
    'getvalue' => array(
      'contact_id' => 'int',
    ),
    'wpresetpassword' => array(
      'username' => 'string',
      'password' => 'string',
      'key' => 'string',
    ),
    'validateusername' => array(
      'username' => 'string',
    ),
    'validateemail' => array(
      'email' => 'string',
    ),
    'validatevolunteeremail' => array(
      'email' => 'string',
      'cid' => 'string',
    ),
    'sendpasswordresetlink' => array(
      'username' => 'string',
    ),
    'getvolunteer' => array(
      'cid' => 'int',
    ),
    'get' => array(
      'first_name' => 'string',
      'last_name' => 'string',
      'email' => 'string',
      'json' => 'string',
    ),
  ),
  'Yhvsignup' => array (
    'getshifts' => array(
      'json' => 'string',
    ),
    'getselectvals' => array(
      'json' => 'string',
    ),
    'geturls' => array(
      'json' => 'string',
    ),
    'doaction' => array(
      'json' => 'string',
    ),
    'getchainedselect' => array(
      'json' => 'string',
    ),
  ),
  'FormProcessor' => array(
    'getfields' => array(
    ),
    'volunteer_application' => array(
      'json' => 'string',
    ),
    'volunteer_signup' => array(
      'json' => 'string',
    ),
    'contact_information' => array(
      'json' => 'string',
    ),
    'volunteer_activity' => array(
      'json' => 'string',
    ),
  ),
  'FormProcessorInstancelist' => array(
    'list' => array(),
  ),
  'FormProcessorDefaults' => array(
    'getfields' => array(),
    'volunteer_application' => array(
      'json' => 'string',
    ),
    'volunteer_signup' => array(
      'json' => 'string',
    ),
    'contact_information' => array(
      'json' => 'string',
    ),
  ),
  'FormProcessorInstance' => array(
    'list' => array(
      'json' => 'string',
    ),
  ),
);

