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

// see if mail open tracking is enabled
if (!$target_wp_version) {
  civiproxy_http_error("Feature disabled", 405);
}

// basic check
if (!civiproxy_security_check('wp-version')) {
  civiproxy_http_error("Access Denied", 403);
}

civiproxy_redirect($target_wp_version, []);
