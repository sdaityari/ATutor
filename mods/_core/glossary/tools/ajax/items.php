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

if ($_POST['addSubmit'] || $_POST['editSubmit']) {
    $word       = trim($_POST['word']);
    $definition = addslashes(trim($_POST['definition']));
    //60 is defined by the sql
    $word       = addslashes(validate_length($word, 60));
    $response   = array();

    if (!$word || !$definition) {
        $response['message'] = 'CONTENT_EMPTY';
        echo json_encode($response);
        exit;
    }

    $related_term = addslashes(intval($_POST['relatedTerm']));

    // Checking if related term exists, else remove the related term
    $related_word = queryDB('SELECT * FROM %sglossary WHERE word_id=%d',
                array(TABLE_PREFIX, $related_term), true);
    if (!$related_word) {

        $related_term = 0;
    } else {
        $response['related'] = $related_word['word'];
    }


    if ($_POST['addSubmit']) {
        // Checking if term already exists
        if (queryDB('SELECT * FROM %sglossary WHERE word=\'%s\' AND course_id=%d',
                array(TABLE_PREFIX, $word, $_SESSION['course_id']), true)) {

            $response['message'] = 'TERM_EXISTS';
            echo json_encode($response);
            exit;
        }

        queryDB('INSERT INTO %sglossary VALUES(NULL, %d, \'%s\', \'%s\', %d)',
                array(TABLE_PREFIX, $_SESSION['course_id'], $word, $definition, $related_term));

        $response['id'] = mysql_insert_id($db);
        $response['message'] = 'ACTION_COMPLETED_SUCCESSFULLY';
        echo json_encode($response);
        exit;
    }

    if ($_POST['editSubmit']) {
        $gid = intval($_POST['gid']);

        // Checking if term already exists
        if (queryDB('SELECT * FROM %sglossary WHERE word=\'%s\' AND course_id=%d AND word_id<>%d',
                array(TABLE_PREFIX, $word, $_SESSION['course_id'], $gid), true)) {

            $response['message'] = 'TERM_EXISTS';
            echo json_encode($response);
            exit;
        }

        queryDB('UPDATE %sglossary SET word=\'%s\', definition=\'%s\', related_word_id=%d WHERE word_id=%d AND course_id=%d', array(TABLE_PREFIX, $word, $definition, $related_term, $gid, $_SESSION['course_id']));
        $response['id'] = $gid;
        $response['message'] = 'ACTION_COMPLETED_SUCCESSFULLY';
        echo json_encode($response);
        exit;
    }
}

?>
