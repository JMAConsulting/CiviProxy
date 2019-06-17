<?php

ini_set('include_path', dirname(dirname(__FILE__)));
require_once "proxy.php";

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
    }

    .container {
        position: relative;
        width: 100%;
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
    <div id="info" class="center">
      <a href="http://www.yeehong.com/"><?php echo $civiproxy_logo;?></a>
    </div>

<form method="post" name="Edit" id="Edit" class="CRM_Profile_Form_Edit" >
  
      
<input name="gid" type="hidden" value="15" />
    <div class="crm-profile-name-Your_Preferences_15">


    <div id="crm-container" class="crm-container crm-public" lang="en" xml:lang="en">
  
      <div id="editrow-email-Primary" class="crm-section editrow_email-Primary-section form-item"><div class="label"><label for="email-Primary">  Email
     <span class="crm-marker" title="This field is required.">*</span>

</label></div><div class="edit-value content"><input maxlength="254" size="30" name="email-Primary" type="text" id="email-Primary" class="big crm-form-text required" /></div><div class="clear"></div></div><div id="editrow-group" class="crm-section editrow_group-section form-item"><div class="label"><label>Campaign(s)</label></div><div class="edit-value content">    <table class="form-layout-compressed crm-profile-tagsandgroups">
      <tr>
                          <td>
                                          <div class="group-wrapper">
                  <input type="hidden" name="group[1]" value="" /><input skiplabel="1" id="group_1" name="group[1]" type="checkbox" value="1" class="crm-form-checkbox" />Administrators
                                      <div class="description">Contacts in this group are assigned Administrator role permissions.</div>
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[24]" value="" /><input skiplabel="1" id="group_24" name="group[24]" type="checkbox" value="1" class="crm-form-checkbox" />Contacts - BD Retirement Home Mailing
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[40]" value="" /><input skiplabel="1" id="group_40" name="group[40]" type="checkbox" value="1" class="crm-form-checkbox" />Contacts - BD Retirement Home Mailing (Individual)
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[46]" value="" /><input skiplabel="1" id="group_46" name="group[46]" type="checkbox" value="1" class="crm-form-checkbox" />Contacts - Caregiver Support Services
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[14]" value="" /><input skiplabel="1" id="group_14" name="group[14]" type="checkbox" value="1" class="crm-form-checkbox" />Contacts - Finch ASP
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[37]" value="" /><input skiplabel="1" id="group_37" name="group[37]" type="checkbox" value="1" class="crm-form-checkbox" />Contacts - Finch ASP Unsubscribe
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[13]" value="" /><input skiplabel="1" id="group_13" name="group[13]" type="checkbox" value="1" class="crm-form-checkbox" />Contacts - Finch Centre
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[38]" value="" /><input skiplabel="1" id="group_38" name="group[38]" type="checkbox" value="1" class="crm-form-checkbox" />Contacts - Finch Centre Unsubscribe
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[15]" value="" /><input skiplabel="1" id="group_15" name="group[15]" type="checkbox" value="1" class="crm-form-checkbox" />Contacts - Macrobian Club 
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[18]" value="" /><input skiplabel="1" id="group_18" name="group[18]" type="checkbox" value="1" class="crm-form-checkbox" />Contacts - Markham Centre
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[16]" value="" /><input skiplabel="1" id="group_16" name="group[16]" type="checkbox" value="1" class="crm-form-checkbox" />Contacts - McNicoll Centre
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[19]" value="" /><input skiplabel="1" id="group_19" name="group[19]" type="checkbox" value="1" class="crm-form-checkbox" />Contacts - Mississauga AS&O
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[17]" value="" /><input skiplabel="1" id="group_17" name="group[17]" type="checkbox" value="1" class="crm-form-checkbox" />Contacts - Mississauga Centre
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[48]" value="" /><input skiplabel="1" id="group_48" name="group[48]" type="checkbox" value="1" class="crm-form-checkbox" />Contacts - Volunteer Tutors
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[44]" value="" /><input skiplabel="1" id="group_44" name="group[44]" type="checkbox" value="1" class="crm-form-checkbox" />Contacts - Yee Hong Care Ambassador
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[34]" value="" /><input skiplabel="1" id="group_34" name="group[34]" type="checkbox" value="1" class="crm-form-checkbox" />Contacts - Yee Hong Markham Family Contacts
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[33]" value="" /><input skiplabel="1" id="group_33" name="group[33]" type="checkbox" value="1" class="crm-form-checkbox" />Contacts - Yee Hong Markham Newsletter
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[50]" value="" /><input skiplabel="1" id="group_50" name="group[50]" type="checkbox" value="1" class="crm-form-checkbox" />Contacts - YHGT Mailing
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[53]" value="" /><input skiplabel="1" id="group_53" name="group[53]" type="checkbox" value="1" class="crm-form-checkbox" />Contacts - YHVolunteer
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[20]" value="" /><input skiplabel="1" id="group_20" name="group[20]" type="checkbox" value="1" class="crm-form-checkbox" />Contacts - York Region Services Development
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[22]" value="" /><input skiplabel="1" id="group_22" name="group[22]" type="checkbox" value="1" class="crm-form-checkbox" />Contacts - York Region Services Development (Ad-hoc)
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[11]" value="" /><input skiplabel="1" id="group_11" name="group[11]" type="checkbox" value="1" class="crm-form-checkbox" />Pilot Group
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[12]" value="" /><input skiplabel="1" id="group_12" name="group[12]" type="checkbox" value="1" class="crm-form-checkbox" />Pilot Group 1
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[62]" value="" /><input skiplabel="1" id="group_62" name="group[62]" type="checkbox" value="1" class="crm-form-checkbox" />Test
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[10]" value="" /><input skiplabel="1" id="group_10" name="group[10]" type="checkbox" value="1" class="crm-form-checkbox" />Test JMA
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[55]" value="" /><input skiplabel="1" id="group_55" name="group[55]" type="checkbox" value="1" class="crm-form-checkbox" />Test Staging Group
                                  </div>
                              <div class="group-wrapper">
                  <input type="hidden" name="group[58]" value="" /><input skiplabel="1" id="group_58" name="group[58]" type="checkbox" value="1" class="crm-form-checkbox" />Unsubscribe Group
                                  </div>
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
                              <div class="group-wrapper">
                  <input type="hidden" name="group[52]" value="" /><input skiplabel="1" id="group_52" name="group[52]" type="checkbox" value="1" class="crm-form-checkbox" />YH Volunteer Testing
                                  </div>
                                    </td>
              </tr>
    </table>
</div><div class="clear"></div></div><div class="crm-submit-buttons" style=''>                                      
                            
    <span class="crm-button crm-button-type-next crm-button_qf_Edit_next crm-i-button">
      <i class="crm-i fa-check"></i>
      <input class="crm-form-submit default validate" crm-icon="fa-check" name="_qf_Edit_next" value="Submit" type="submit" id="_qf_Edit_next" />
    </span>
  </div>
</div> 
<script type="text/javascript">
  </script>
  
<script type="text/javascript">

CRM.$(function($) {
  cj('#selector tr:even').addClass('odd-row ');
  cj('#selector tr:odd ').addClass('even-row');
});

</script>

</div>   
  </form>
</div>
</body>
</html>
