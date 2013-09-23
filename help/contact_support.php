<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2010                                      */
/* Inclusive Design Institute                                   */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id$

$_user_location	= 'public';

define('AT_INCLUDE_PATH', '../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
require(AT_INCLUDE_PATH."securimage/securimage.php");
    $img = new Securimage();
if (isset($_POST['cancel'])) {
	$msg->addFeedback('CANCELLED');
	header('Location: index.php');
	exit;
}

$onload = 'document.form.from.focus();';
require(AT_INCLUDE_PATH.'header.inc.php');

if ($_SESSION['member_id']) {
	$sql	= "SELECT first_name, last_name, email FROM %smembers WHERE member_id=%d";
	$row = queryDB($sql, array(TABLE_PREFIX,$_SESSION['member_id']), TRUE);
	if (isset($row['first_name']) && $row['first_name'] != '') {
		$student_name = AT_print($row['last_name'], 'members.last_name');
		$student_name .= (AT_print($row['first_name'], 'members.first_name') ? ', '.AT_print($row['first_name'], 'members.first_name') : '');

		$student_email = AT_print($row['email'], 'members.email');
	}
}

if (!$_config['contact_email']) {
	$msg->printErrors('CONTACT_INFO_NOT_FOUND');
	require(AT_INCLUDE_PATH.'footer.inc.php');
	exit;
}

if (isset($_POST['submit'])) {
	$missing_fields = array();

	$_POST['subject'] = trim($_POST['subject']);
	$_POST['body']	  = trim($_POST['body']);


    $valid = $img->check($_POST['secret']);
    if (!$valid){
        $msg->addError('SECRET_ERROR');
    }
	
	if ($_POST['from'] == '') {
		$missing_fields[] = _AT('from_name');
	}

	if ($_POST['from_email'] == '') {
		$missing_fields[] = _AT('from_email');
	} else if (!preg_match("/^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,6}$/i", $_POST['from_email'])) {
		$msg->addError('EMAIL_INVALID');
	}

	if ($_POST['subject'] == '') {
		$missing_fields[] = _AT('subject');
	}
		
	if ($_POST['body'] == '') {
		$missing_fields[] = _AT('body');
	}

	if ($missing_fields) {
		$missing_fields = implode(', ', $missing_fields);
		$msg->addError(array('EMPTY_FIELDS', $missing_fields));
	}

	if (!$msg->containsErrors()) {

		require(AT_INCLUDE_PATH . 'classes/phpmailer/atutormailer.class.php');

		$mail = new ATutorMailer;

		$mail->From     = $_POST['from_email'];
		$mail->FromName = $stripslashes($_POST['from']);
		$mail->AddAddress($_config['contact_email']);
		$mail->Subject = $stripslashes($_POST['subject']);
		$mail->Body    = $stripslashes($_POST['body']);

		if(!$mail->Send()) {
		   $msg->printErrors('SENDING_ERROR');
		   exit;
		}
		unset($mail);
		
		$msg->printFeedbacks('ACTION_COMPLETED_SUCCESSFULLY');
		require(AT_INCLUDE_PATH.'footer.inc.php');
		exit;
	}
}

$msg->printErrors();
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="form">
<div class="input-form">
	<div class="row">
		<span class="required" title="<?php echo _AT('required_field'); ?>">*</span><label for="from"><?php echo _AT('from_name'); ?></label><br />
		<input type="text" name="from" id="from" size="40" value="<?php echo htmlspecialchars($stripslashes($_POST['from'])); ?>" />
	</div>

	<div class="row">
		<span class="required" title="<?php echo _AT('required_field'); ?>">*</span><label for="from_email"><?php echo _AT('from_email'); ?></label><br />
		<input type="text" name="from_email" id="from_email" size="40" value="<?php echo htmlspecialchars($stripslashes($_POST['from_email'])); ?>" />
	</div>

	<div class="row">
		<span class="required" title="<?php echo _AT('required_field'); ?>">*</span><label for="subject"><?php echo _AT('subject'); ?></label><br />
		<input type="text" name="subject" id="subject" size="40" value="<?php echo htmlspecialchars($stripslashes($_POST['subject'])); ?>" />
	</div>

	<div class="row">
		<span class="required" title="<?php echo _AT('required_field'); ?>">*</span><label for="body_text"><?php echo _AT('body'); ?></label><br />
		<textarea cols="55" rows="15" id="body_text" name="body"><?php echo htmlspecialchars($stripslashes($_POST['body'])); ?></textarea>
	</div>
	<div class="row">
		<span class="required" title="<?php echo _AT('required_field'); ?>">*</span><br/>
		<label for="secret">
		<img src="include/securimage/securimage_show.php?sid=<?php echo md5(uniqid(time())); ?>" id="simage" align="left" /></label>
		<a href="include/securimage/securimage_play.php" title="<?php echo _AT('audible_captcha'); ?>"><img src="include/securimage/images/audio_icon.gif" alt="<?php echo _AT('audible_captcha'); ?>" onclick="this.blur()" align="top" border="0"></a><br>
		<a href="#" title="<?php echo _AT('refresh_image'); ?>" onclick="document.getElementById('simage').src = 'include/securimage/securimage_show.php?sid=' + Math.random(); return false"><img src="include/securimage/images/refresh.gif" alt="<?php echo _AT('refresh_image'); ?>" onclick="this.blur()" align="bottom" border="0"></a>

		<br />
		<p><br /><?php echo _AT('image_validation_text'); ?><br />
		<input id="secret" name="secret" type="text" size="6" maxlength="6" value="" />
		<br />
	</div>
	<div class="row buttons">
		<input type="submit" name="submit" value="<?php echo _AT('send'); ?>" accesskey="s" /> 
		<input type="submit" name="cancel" value="<?php echo _AT('cancel'); ?>" />
	</div>
</div>
</form>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>