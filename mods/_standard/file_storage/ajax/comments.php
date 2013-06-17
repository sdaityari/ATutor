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
include_once(AT_INCLUDE_PATH . 'lib/vital_funcs.inc.php');

if (!check_ajax_request()) {
    exit;
}

$owner_type = abs($_REQUEST['ot']);
$owner_id   = abs($_REQUEST['oid']);
$owner_arg_prefix = '?ot='.$owner_type.SEP.'oid='.$owner_id. SEP;

if ($_REQUEST['addSubmit']) {
    $comment = trim($_REQUEST['comment']);
    $file_id = abs($_REQUEST['fileId']);

    $response = array();

    $file = queryDB('SELECT file_id FROM %sfiles WHERE file_id = %d', array(TABLE_PREFIX, $file_id), true);

    if (!$file) {
        $response['message'] = 'PAGE_NOT_FOUND';
        echo json_encode($response);
        exit;
    }

    if (!$comment) {
        $response['message'] = 'COMMENT_EMPTY';
        echo json_encode($response);
        exit;

    }

    $add_comment = queryDB('INSERT INTO %sfiles_comments VALUES (NULL, %d, %d, NOW(), "%s")', array(TABLE_PREFIX, $file_id, $_SESSION['member_id'], $comment));
    $response['id'] = mysql_insert_id($db);

	if (mysql_affected_rows($db) == 1) {
        $update_comments = queryDB('UPDATE %sfiles SET num_comments=num_comments+1, date=date WHERE file_id = %d', array(TABLE_PREFIX, $file_id));

        $row = queryDB('SELECT * FROM %sfiles_comments WHERE comment_id = %d', array(TABLE_PREFIX, $response['id']), true);

        $response['name'] = get_display_name($row['member_id']);
        $response['date'] = AT_date(_AT('filemanager_date_format'), $row['date'], AT_DATE_MYSQL_DATETIME);
        $response['comment'] = nl2br(htmlspecialchars($row['comment']));
        $response['message'] = 'ACTION_COMPLETED_SUCCESSFULLY';

        echo json_encode($response);
    }
    exit;
}

$comment = queryDB('SELECT * FROM %sfiles_comments WHERE comment_id = %d', array(TABLE_PREFIX, $_REQUEST['id']), true);
if (!$comment) {
    echo 'PAGE_NOT_FOUND';
    exit;
}

//Since $owner_id is a GET variable, this query is to check that the owner_id is provided correctly
$file = queryDB('SELECT file_id FROM %sfiles WHERE file_id = %d and owner_id = %d', array(TABLE_PREFIX, $comment['file_id'], $owner_id), true);

if (!($comment['member_id'] == $_SESSION['member_id'] || $owner_id == $_SESSION['member_id']) || !$file) {
    print 'ACCESS_DENIED';
    exit;
}

$id = abs($_REQUEST['id']);

if (isset($_POST['deleteSubmit'])) {
    //If comment is to be deleted
	$_POST['fileId'] = abs($_POST['fileId']);
	$_POST['id'] = abs($_POST['id']);

    $sql = queryDB('DELETE FROM %sfiles_comments WHERE file_id= %d AND comment_id = %d', array(TABLE_PREFIX, $_REQUEST['fileId'], $_REQUEST['id']));
	if (mysql_affected_rows($db) == 1) {
        $update_comments = queryDB('UPDATE %sfiles SET num_comments=num_comments-1, date=date WHERE owner_type = %d AND owner_id = %d AND file_id = %d', array(TABLE_PREFIX, $owner_type, $owner_id, $_REQUEST['fileId']));
	    echo 'ACTION_COMPLETED_SUCCESSFULLY';
	}

	exit;
}

if (isset($_POST['editSubmit'])) {
    //If comment is to be edited
	$comment = trim($_POST['comment']);
    if (!$comment) {
        echo 'COMMENT_EMPTY';
        exit;
    }
    $update_comment = queryDB("UPDATE %sfiles_comments SET comment='%s', date=date WHERE member_id=%d AND comment_id=%d", array(TABLE_PREFIX, $comment, $_SESSION["member_id"], $id));
	if (mysql_affected_rows($db) == 1) {
        echo 'ACTION_COMPLETED_SUCCESSFULLY';
    }
	exit;
}

?>
