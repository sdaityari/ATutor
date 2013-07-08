<?php
/****************************************************************/
/* ATutor                                                        */
/****************************************************************/
/* Copyright (c) 2002-2010                                      */
/* Inclusive Design Institute                                   */
/* http://atutor.ca                                                */
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.                */
/****************************************************************/
// $Id$
$_user_location    = 'public';

define('AT_INCLUDE_PATH', '../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');

$current_url = url_rewrite('help/index.php');

require (AT_INCLUDE_PATH.'header.inc.php');

?>

<div id="subnavlistcontainer">
    <div id="subnavbacktopage"></div>
    <ul id="subnavlist">
        <li id="nav-basic" class="active"><a href="<?php echo $current_url; ?>#basic-help" onclick="ATutor.helpPage.changeActive('basic');"><?php echo _AT('help'); ?></a></li>
        <li id="nav-external"><a href="<?php echo $current_url; ?>#external-help" onclick="ATutor.helpPage.changeActive('external');"><?php echo _AT('external_help') ?></a></li>
        <li id="nav-accessibility"><a href="<?php echo $current_url; ?>#accessibility" onclick="ATutor.helpPage.changeActive('accessibility');"><?php echo _AT('accessibility') ?></a></li>
        <li><a href="<?php echo url_rewrite('help/contact_support.php'); ?>"><?php echo _AT('contact_support') ?></a></li>
    </ul>
</div>

<div id="basic-help" class="input-form">
    <fieldset class="group_form">
        <legend class="group_form"><?php echo _AT('help'); ?></legend>
        <ul>
            <li style="padding-bottom: 20px;"><a href="documentation/index_list.php?lang=<?php echo $_SESSION['lang']; ?>" onclick="ATutor.poptastic('<?php echo AT_BASE_HREF; ?>documentation/index_list.php?lang=<?php echo $_SESSION['lang']; ?>'); return false;" target="_new"><?php echo _AT('atutor_handbook');?></a><br />
            <?php echo _AT('general_help', AT_GUIDES_PATH); ?></li>

            <li style="padding-bottom: 20px;"><a href="help/accessibility.php"><?php echo _AT('accessibility_features'); ?></a>
                <br /><?php echo _AT('accessibility_features_text'); ?></li>

            <li><a href="help/contact_support.php"><?php echo _AT('contact_support'); ?></a></li>
        </ul>
        <div style="text-align:right; font-size: smaller"><a href="<?php echo $current_url; ?>#content">Back to Top</a></div>
    </fieldset>
</div>

<div id="external-help" class="input-form">
    <fieldset class="group_form">
        <legend class="group_form"><?php echo _AT('external_help'); ?></legend>
        <ul>
            <li style="padding-bottom: 20px;"><?php echo _AT('request_services'); ?>
                <br /><?php echo _AT('request_services_text'); ?></li>

            <li style="padding-bottom: 20px;"><?php echo _AT('howto_course'); ?>
            <br /><?php echo _AT('howto_course_text'); ?></li>

            <li><a href="http://www.atutor.ca/forum/7/1.html"><?php echo _AT('tech_support_forum'); ?></a>
                <br /><?php echo _AT('tech_support_forum_text'); ?></li>
        </ul>
        <div style="text-align:right; font-size: smaller"><a href="<?php echo $current_url; ?>#content">Back to Top</a></div>
    </fieldset>
</div>

<div id="accessibility" class="input-form">
    <fieldset class="group_form">
        <legend class="group_form"><?php echo _AT('accessibility'); ?></legend>
        <? echo _AT('atutor_accessibility_text'); ?>
        <div style="text-align:right; font-size: smaller"><a href="<?php echo $current_url; ?>#content">Back to Top</a></div>
     </fieldset>
</div>
<script type="text/javascript" src="<?php echo AT_BASE_HREF; ?>jscripts/infusion/lib/jquery/core/js/jquery.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo AT_BASE_HREF; ?>jscripts/helpPage.js"></script>
<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>
