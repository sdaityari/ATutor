<?php
/************************************************************************/
/* ATutor                                                               */
/************************************************************************/
/* Copyright (c) 2002-2010                                              */
/* Inclusive Design Institute                                           */
/* http://atutor.ca                                                     */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/

if (!defined('AT_INCLUDE_PATH')) {
    exit;
}

/* AJAX check  */
if(!strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    //Uncomment the following line for debugging
    //print strtolower($_SERVER['HTTP_X_REQUESTED_WITH']);
    die('AJAX_REQUEST_NOT_PRESENT');
}

?>
