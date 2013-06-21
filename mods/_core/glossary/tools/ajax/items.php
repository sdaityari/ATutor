<?php
/****************************************************************************/
/* ATutor                                                                    */
/****************************************************************************/
/* Copyright (c) 2002-2013                                                  */
/* Inclusive Design Institute                                               */
/* http://atutor.ca                                                            */
/*                                                                            */
/* This program is free software. You can redistribute it and/or            */
/* modify it under the terms of the GNU General Public License                */
/* as published by the Free Software Foundation.                            */
/****************************************************************************/

define('AT_INCLUDE_PATH', '../../../../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
include_once(AT_INCLUDE_PATH . 'lib/vital_funcs.inc.php');

if (!check_ajax_request()) {
    exit;
}

if (!authenticate(AT_PRIV_GLOSSARY)) {
    echo "ACCESS_DENIED";
    exit;
}

if ($_POST['deleteSubmit']) {

    $gid = intval($_POST['gid']);

    queryDB('DELETE FROM %sglossary WHERE word_id=%d AND course_id=%d', array(TABLE_PREFIX, $gid, $_SESSION['course_id']));

    if (mysql_affected_rows($db) != 1) {
        echo 'PAGE_NOT_FOUND';
        exit;
    }

    queryDB('UPDATE %sglossary SET related_word_id=0 where related_word_id=%d and course_id=%d',
        array(TABLE_PREFIX, $gid, $_SESSION['course_id']));

    echo 'ACTION_COMPLETED_SUCCESSFULLY';
    exit;
}

?>
