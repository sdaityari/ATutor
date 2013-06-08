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

define('AT_INCLUDE_PATH', '../../../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
require(AT_INCLUDE_PATH.'../mods/_standard/file_storage/file_storage.inc.php');
require(AT_INCLUDE_PATH.'lib/ajax.inc.php');

$owner_type = abs($_REQUEST['ot']);
$owner_id   = abs($_REQUEST['oid']);
$owner_arg_prefix = '?ot='.$owner_type.SEP.'oid='.$owner_id. SEP;
if (!($owner_status = fs_authenticate($owner_type, $owner_id)) || !query_bit($owner_status, WORKSPACE_AUTH_WRITE)) { 
    echo 'ACCESS_DENIED';
	exit;
}

$id = abs($_REQUEST['id']);

if (isset($_POST['submit_yes'])) {
	$_POST['file_id'] = abs($_POST['file_id']);
	$_POST['id'] = abs($_POST['id']);

	$sql = "DELETE FROM ".TABLE_PREFIX."files_comments WHERE file_id=$_POST[file_id] AND comment_id=$_POST[id] AND member_id=$_SESSION[member_id]";
	$result = mysql_query($sql, $db);
	if (mysql_affected_rows($db) == 1) {
		$sql = "UPDATE ".TABLE_PREFIX."files SET num_comments=num_comments-1, date=date WHERE owner_type=$owner_type AND owner_id=$owner_id AND file_id=$_POST[file_id]";
		$result = mysql_query($sql, $db);
	    echo 'ACTION_COMPLETED_SUCCESSFULLY';
	}

	exit;
}

?>
