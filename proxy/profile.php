<?php

ini_set('include_path', dirname(dirname(__FILE__)));
require_once "proxy.php";

define('PROFILE_ID', 15);
define('CONSENT', 5);
define('MSG_ID_CONSENT', 68);
define('MSG_ID_THANKS', 69);
define('MSG_ID_REQUEST_CONSENT', 70);

// basic restraints
$valid_parameters = array(    'cid'          => 'int',
                              'cs'           => 'string');
$parameters = civiproxy_get_parameters($valid_parameters);

// check if parameters specified
if (empty($parameters['cid'])) civiproxy_http_error("Missing/invalid parameter 'cid'.");
if (empty($parameters['cs']))   civiproxy_http_error("Missing/invalid parameter 'cs'.");

$checksum = civicrm_api3('Checksum', 'validate', 
                          array( 'contact_id'         => $parameters['cid'],
                                 'checksum'       => $parameters['cs'],
                                 'api_key'        => $mail_subscription_user_key,
                                ));
if (empty($checksum['values'][$parameters['cid']]['is_valid'])) {
  civiproxy_http_error("Invalid contact checksum.");
}

$email = civicrm_api3('Email', 'getvalue', [
           'return' => 'email',
           'contact_id' => $parameters['cid'],
           'is_primary' => 1,
           'api_key'    => $mail_subscription_user_key,
         ])['result'];

// Groups.
$groups = civicrm_api3('GroupContact', 'get', [
  'return' => ["group_id"],
  'contact_id' => $parameters['cid'],
  'status' => 'Added',
  'api_key'    => $mail_subscription_user_key,
])['values'];

if (!empty($groups)) {
  foreach ($groups as $group) {
    $ids[] = "group_" . $group['group_id'];
    $prev[] = $group['group_id']; 
  }
}

if (!empty($ids)) {
  $gids = json_encode($ids);
}

if (!empty($_POST)) {
  if (!empty($_POST['group'])) {
    $g = $_POST['group'];
    foreach ($g as $gid => $sub) {
      if ($sub) {
        civicrm_api3('GroupContact', 'create', [
          'contact_id' => $parameters['cid'],
          'group_id' => $gid,
          'status' => 'Added',
          'api_key'    => $mail_subscription_user_key,
        ]);
        $title[] = civicrm_api3('Group', 'getvalue', [
          'id' => $gid,
          'return' => 'title',
          'api_key'    => $mail_subscription_user_key,
        ])['result'];
      }
      else {
        if (in_array($gid, $prev)) {
          civicrm_api3('GroupContact', 'create', [
            'contact_id' => $parameters['cid'],
            'group_id' => $gid,
            'status' => 'Removed',
            'api_key'    => $mail_subscription_user_key,
          ]);
        }
      }
    }
  }

  $activityParams = array( 
    'activity_type_id' => 'Preferences Updated',
    'subject' => 'Preferences have been updated',
    'status_id' => 'Completed',
    'activity_date_time' => date('YmdHis'),
    'source_contact_id' => $parameters['cid'],
    'target_contact_id' => $parameters['cid'],
    'api_key'    => $mail_subscription_user_key,
  );
    
  if (!empty($title)) {
    //$form->assign('groupsContact', implode(',', $title));
    $activityParams['details'] = "Campaigns selected: " . implode(', ', $title);
  }
  else {
      //$form->assign('groupsContact', ts('No campaigns selected.'));
    $activityParams['details'] = 'No campaigns selected.';
  }
  $o = civicrm_api3('Activity', 'create', $activityParams);
    // Check if contact has consented previously.
    $consent = civicrm_api3('Contact', 'get', [
      'id' => $parameters['cid'],
      'sequential' => 1,
      'api_key'    => $mail_subscription_user_key,
      'return' => 'custom_' . CONSENT
    ])['values'];
    $isSent = FALSE;
    if (empty($consent[0]['custom_' . CONSENT])) {
      // Send email since this is the first time of visit.
      $email = civicrm_api3('Email', 'send', [
        'contact_id' => $parameters['cid'],
        'api_key'    => $mail_subscription_user_key,
        'template_id' => MSG_ID_CONSENT,
      ]);
      $email = civicrm_api3('Email', 'send', [
        'contact_id' => $parameters['cid'],
        'api_key'    => $mail_subscription_user_key,
        'template_id' => MSG_ID_THANKS,
      ]);
      $isSent = TRUE;
      if (!$email['is_error']) {
        civicrm_api3('CustomValue', 'create', [
          'entity_id' => $parameters['cid'],
          'api_key'    => $mail_subscription_user_key,
          'custom_' . CONSENT => 1,
        ]);
      }
    }
    if (!$isSent) {
      $email = civicrm_api3('Email', 'send', [
        'contact_id' => $parameters['cid'],
        'api_key'    => $mail_subscription_user_key,
        'template_id' => MSG_ID_THANKS,
      ]);
    }
}

?>
<!DOCTYPE html>
<html>
 <head>
  <meta charset="UTF-8">
  <title>Yee Hong Center for Geriatric Care</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">
<style type="text/css">
    body {
      margin: 0;
      padding: 0;
      font-family: Georgia, Helvetica, Arial, sans-serif;
    }

    .container {
        position: relative;
        width: 100%;
    }

    #info a {
      font-size: 1.821em;
      color: #fffeff;
      line-height: 1;
      font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
      text-decoration: none;
    }

    #info a:hover {
      text-decoration: underline;
    }

    .center {
      margin-left: auto;
      margin-right: auto;
      width: 970px;
      text-align: center;
    }

    p {
      font-family: "Open Sans", sans-serif;
      font-size: 160%;
    }

    #info {
      padding-top: 20px;
      vertical-align: top;
      text-align: center;
      width: 462px;
      height: 50px;
    }

    h1 {
      font-size: 2em;
    line-height: 1;
      font-weight: normal;
      font-family: Georgia, "Times New Roman", Times, serif;
    }
  </style>
<script type="text/javascript" src="sites/all/modules/civicrm/bower_components/jquery/dist/jquery.min.js?r=fyMo5">
</script>
<script type="text/javascript" src="sites/all/modules/civicrm/bower_components/jquery-ui/jquery-ui.min.js?r=fyMo5">
</script>
<script type="text/javascript" src="sites/all/modules/civicrm/bower_components/lodash-compat/lodash.min.js?r=fyMo5">
</script>
<script type="text/javascript" src="sites/all/modules/civicrm/packages/jquery/plugins/jquery.mousewheel.min.js?r=fyMo5">
</script>
<script type="text/javascript" src="sites/all/modules/civicrm/bower_components/select2/select2.min.js?r=fyMo5">
</script>
<script type="text/javascript" src="sites/all/modules/civicrm/packages/jquery/plugins/jquery.form.min.js?r=fyMo5">
</script>
<script type="text/javascript" src="sites/all/modules/civicrm/packages/jquery/plugins/jquery.timeentry.min.js?r=fyMo5">
</script>
<script type="text/javascript" src="sites/all/modules/civicrm/packages/jquery/plugins/jquery.blockUI.min.js?r=fyMo5">
</script>
<script type="text/javascript" src="sites/all/modules/civicrm/bower_components/datatables/media/js/jquery.dataTables.min.js?r=fyMo5">
</script>
<script type="text/javascript" src="sites/all/modules/civicrm/bower_components/jquery-validation/dist/jquery.validate.min.js?r=fyMo5">
</script>
<script type="text/javascript" src="sites/all/modules/civicrm/packages/jquery/plugins/jquery.ui.datepicker.validation.min.js?r=fyMo5">
</script>
<script type="text/javascript" src="sites/all/modules/civicrm/js/Common.js?r=fyMo5">
</script>
<script type="text/javascript" src="sites/all/modules/civicrm/js/crm.datepicker.js?r=fyMo5">
</script>
<script type="text/javascript" src="sites/all/modules/civicrm/js/crm.ajax.js?r=fyMo5">
</script>
<script type="text/javascript" src="sites/all/modules/civicrm/packages/jquery/plugins/jquery.tableHeader.js?r=fyMo5">
</script>
<script type="text/javascript" src="sites/all/modules/civicrm/packages/jquery/plugins/jquery.notify.min.js?r=fyMo5">
</script>
<script type="text/javascript" src="sites/all/modules/civicrm/bower_components/smartmenus/dist/jquery.smartmenus.min.js?r=fyMo5">
</script>
<script type="text/javascript" src="sites/all/modules/civicrm/bower_components/smartmenus/dist/addons/keyboard/jquery.smartmenus.keyboard.min.js?r=fyMo5">
</script>
<script type="text/javascript" src="sites/all/modules/civicrm/js/crm.multilingual.js?r=fyMo5">
</script>
<script type="text/javascript" src="sites/all/modules/civicrm/js/crm.optionEdit.js?r=fyMo5">
</script>
<link href="sites/all/modules/civicrm/css/crm-i.css?r=fyMo5" rel="stylesheet" type="text/css"/>
<link href="sites/all/modules/civicrm/bower_components/datatables/media/css/jquery.dataTables.min.css?r=fyMo5" rel="stylesheet" type="text/css"/>
<link href="sites/all/modules/civicrm/bower_components/font-awesome/css/font-awesome.min.css?r=fyMo5" rel="stylesheet" type="text/css"/>
<link href="sites/all/modules/civicrm/bower_components/jquery-ui/themes/smoothness/jquery-ui.min.css?r=fyMo5" rel="stylesheet" type="text/css"/>
<link href="sites/all/modules/civicrm/bower_components/select2/select2.min.css?r=fyMo5" rel="stylesheet" type="text/css"/>
<link href="sites/all/modules/civicrm/css/civicrm.css?r=fyMo5" rel="stylesheet" type="text/css"/>
<link href="sites/all/civicrm/extensions/org.civicrm.shoreditch/css/bootstrap.css?r=fyMo5" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="sites/all/civicrm/extensions/org.civicrm.shoreditch/base/js/button.js?r=fyMo5">
</script>
<script type="text/javascript" src="sites/all/civicrm/extensions/org.civicrm.shoreditch/base/js/collapse.js?r=fyMo5">
</script>
<script type="text/javascript" src="sites/all/civicrm/extensions/org.civicrm.shoreditch/base/js/dropdown.js?r=fyMo5">
</script>
<script type="text/javascript" src="sites/all/civicrm/extensions/org.civicrm.shoreditch/base/js/modal.js?r=fyMo5">
</script>
<script type="text/javascript" src="sites/all/civicrm/extensions/org.civicrm.shoreditch/base/js/scrollspy.js?r=fyMo5">
</script>
<script type="text/javascript" src="sites/all/civicrm/extensions/org.civicrm.shoreditch/base/js/tab.js?r=fyMo5">
</script>
<script type="text/javascript" src="sites/all/civicrm/extensions/org.civicrm.shoreditch/base/js/transition.js?r=fyMo5">
</script>
<script type="text/javascript" src="sites/all/civicrm/extensions/org.civicrm.shoreditch/js/noConflict.js?r=fyMo5">
</script>
<script type="text/javascript" src="sites/all/modules/civicrm/js/noconflict.js?r=fyMo5">
</script>
</head>
<body>
<div id="container">
    <div id="info" class="center" style="background-color: rgb(0, 128, 98);background-image: none;width:auto;color:#fff">
      <a href="http://www.yeehong.com/"><!--<?php echo $civiproxy_logo;?>-->Yee Hong Center</a>
    </div>
<?php

if (empty($_POST)) {
print '<h1 style="text-align:center">Your Preferences</h1>';
}

?>


<form method="post" name="Edit" id="Edit" class="CRM_Profile_Form_Edit" >
  
      
<input name="gid" type="hidden" value="15" />
    <div class="crm-profile-name-Your_Preferences_15">


    <div id="crm-container" class="crm-container crm-public" lang="en" xml:lang="en">

<?php 

if (empty($_POST)) {
  
      echo '<div id="editrow-email-Primary" class="crm-section editrow_email-Primary-section form-item"><div class="label"><label for="email-Primary">  Email

</label></div><div class="edit-value content">'; print $email; echo '<input maxlength="254" size="30" name="email-Primary" type="hidden" id="email-Primary" class="big crm-form-text required" value="'; print $email; echo '" /></div><div class="clear"></div></div><div id="editrow-group" class="crm-section editrow_group-section form-item"><div class="label"><label>Campaign(s)</label></div><div class="edit-value content">    <table class="form-layout-compressed crm-profile-tagsandgroups">
      <tr>
                          <td>
<div class="group-wrapper">
                  <input type="hidden" name="group[42]" value="" /><input skiplabel="1" id="group_42" name="group[42]" type="checkbox" value="1" class="crm-form-checkbox" />Yee Hong Care Ambassador
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[45]" value="" /><input skiplabel="1" id="group_45" name="group[45]" type="checkbox" value="1" class="crm-form-checkbox" />Yee Hong Caregiver Support Services
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[3]" value="" /><input skiplabel="1" id="group_3" name="group[3]" type="checkbox" value="1" class="crm-form-checkbox" />Yee Hong Finch Active Senior Program
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[9]" value="" /><input skiplabel="1" id="group_9" name="group[9]" type="checkbox" value="1" class="crm-form-checkbox" />Yee Hong Finch Centre Mailing
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[51]" value="" /><input skiplabel="1" id="group_51" name="group[51]" type="checkbox" value="1" class="crm-form-checkbox" />Yee Hong Garden Terrace Mailing
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[2]" value="" /><input skiplabel="1" id="group_2" name="group[2]" type="checkbox" value="1" class="crm-form-checkbox" />Yee Hong Macrobian Club
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[31]" value="" /><input skiplabel="1" id="group_31" name="group[31]" type="checkbox" value="1" class="crm-form-checkbox" />Yee Hong Markham Centre Mailing
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[32]" value="" /><input skiplabel="1" id="group_32" name="group[32]" type="checkbox" value="1" class="crm-form-checkbox" />Yee Hong Markham Family Contacts
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[7]" value="" /><input skiplabel="1" id="group_7" name="group[7]" type="checkbox" value="1" class="crm-form-checkbox" />Yee Hong Markham Infection Control Notice
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[4]" value="" /><input skiplabel="1" id="group_4" name="group[4]" type="checkbox" value="1" class="crm-form-checkbox" />Yee Hong McNicoll Centre Mailing
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[8]" value="" /><input skiplabel="1" id="group_8" name="group[8]" type="checkbox" value="1" class="crm-form-checkbox" />Yee Hong Mississauga Active Senior & Outreach Program
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[5]" value="" /><input skiplabel="1" id="group_5" name="group[5]" type="checkbox" value="1" class="crm-form-checkbox" />Yee Hong Mississauga Centre Mailing
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[23]" value="" /><input skiplabel="1" id="group_23" name="group[23]" type="checkbox" value="1" class="crm-form-checkbox" />Yee Hong Retirement Home Mailing
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[49]" value="" /><input skiplabel="1" id="group_49" name="group[49]" type="checkbox" value="1" class="crm-form-checkbox" />Yee Hong Volunteer & Advocacy
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[6]" value="" /><input skiplabel="1" id="group_6" name="group[6]" type="checkbox" value="1" class="crm-form-checkbox" />Yee Hong York Region Services Development
                                  </div>
                         
</div><div class="clear"></div></div><div class="crm-submit-buttons">                                      
                            
    <span class="crm-button crm-button-type-next crm-button_qf_Edit_next crm-i-button">
      <i class="crm-i fa-check"></i>
      <input class="crm-form-submit default validate" crm-icon="fa-check" name="_qf_Edit_next" value="Submit" type="submit" id="_qf_Edit_next" />
    </span></div>';
}
else {
  print '<h1 style="text-align:center">Your preferences have been updated!</h1><br/>';
  if (!empty($title)) {
    print '<div style="text-align:center">Campaign(s) selected: <div style="    text-align: left;align-items: center;display: flex;justify-content: center;"><ul><li>'. implode('</li><li>', $title) .'</li></ul></div></div>';
  }
  else {
    print '<div style="text-align:center">No Campaign(s) selected</div>';
  }
}
?>
</div> 
  
<script type="text/javascript">

CRM.$(function($) {
  var groups = <?php echo $gids; ?>;

  if (groups) {
    $(groups).each(function(index, val){
      $("#" + val).prop("checked", true);
    });
  }
});

</script>

</div>   
  </form>
</div>
</body>
</html>