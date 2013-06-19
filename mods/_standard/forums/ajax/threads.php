<?php
/****************************************************************/
/* ATutor                                                       */
/****************************************************************/
/* Copyright (c) 2002-2010                                      */
/* Inclusive Design Institute                                   */
/* http://atutor.ca                                             */
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.                */
/****************************************************************/

define('AT_INCLUDE_PATH', '../../../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
include_once(AT_INCLUDE_PATH . 'lib/vital_funcs.inc.php');
include(AT_INCLUDE_PATH.'../mods/_standard/forums/lib/forums.inc.php');

if (!check_ajax_request()) {
    exit;
}

$pid  = intval($_REQUEST['pid']); //post_id
$ppid = intval($_REQUEST['ppid']); //parent_post_id; 0 if post itself is a thread
$fid  = intval($_REQUEST['fid']); //forum_id

if (!valid_forum_user($fid)) {
    echo 'PAGE_NOT_FOUND';
    exit;
}

if (isset($_POST['deleteSubmit'])) {
    if ($ppid == 0) {   /* If deleting an entire post */
        /* First get number of comments from specific post */
        $row = queryDB('SELECT * FROM %sforums_threads WHERE post_id=%d AND forum_id=%d', array(TABLE_PREFIX, $pid, $fid), true);
        if (!$row) {
            echo 'PAGE_NOT_FOUND';
            exit;
		} // else:

        /* Decrement count for number of posts and topics*/
        $decrement = queryDB('UPDATE %sforums SET num_posts=num_posts-1-%d, num_topics=num_topics-1, last_post=last_post WHERE forum_id=%d', array(TABLE_PREFIX, $row['num_comments'], $fid));

        $remove_thread = queryDB('DELETE FROM %sforums_threads WHERE (parent_id=%d OR post_id=%d) AND forum_id=%d',array(TABLE_PREFIX, $pid, $pid, $fid));

        $remove_replies = queryDB('DELETE FROM %sforums_accessed WHERE post_id=%d', array(TABLE_PREFIX, $pid));

        echo 'ACTION_COMPLETED_SUCCESSFULLY';
        exit;

    } else {   /* Just deleting a single thread */
        $remove_reply = queryDB('DELETE FROM %sforums_threads WHERE post_id=%d AND forum_id=%d AND parent_id=%d', array(TABLE_PREFIX, $pid, $fid, $ppid));

		if (mysql_affected_rows($db) == 0) {
			echo 'PAGE_NOT_FOUND';
			exit;
		}

        /* Decrement count of comments in forums_threads table*/
        $decrement_thread = queryDB('UPDATE %sforums_threads SET num_comments=num_comments=1, last_comment=last_comment, date=date WHERE post_id=%d', array(TABLE_PREFIX, $ppid));

        /* Decrement count of posts in forums table */
        $decrement_forums = queryDB('UPDATE %sforums SET num_posts=num_posts-1, last_post=last_post WHERE forum_id=%d', array(TABLE_PREFIX, $fid));

        echo 'ACTION_COMPLETED_SUCCESSFULLY';
        exit;

	}
}


?>
