<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2004 by Greg Gay & Joel Kronenberg        */
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
if (!defined('AT_INCLUDE_PATH')) { exit; }


function write_config_file($filename, $comments) {

	global $config_template;

	$tokens = array('{USER}',
					'{PASSWORD}',
					'{HOST}',
					'{PORT}',
					'{DBNAME}',
					'{ADMIN_USERNAME}',
					'{ADMIN_PASSWORD}',
					'{ADMIN_EMAIL}',
					'{EMAIL_NOTIFY}',
					'{INSTRUCTOR_REQUESTS}',
					'{APPROVE_INSTRUCTORS}',
					'{MAX_FILE_SIZE}',
					'{MAX_COURSE_SIZE}',
					'{MAX_COURSE_FLOAT}',
					'{ILL_EXT}',
					'{SITE_NAME}',
					'{HEADER_IMG}',
					'{HEADER_LOGO}',
					'{HOME_URL}',
					'{TABLE_PREFIX}',
					'{BACKWARDS_COMPATIBILITY}',
					'{GENERATED_COMMENTS}',
					'{CACHE_DIR}');

	if ($_POST['step1']['old_path'] != '') {
		$values = array(urldecode($_POST['step1']['db_login']),
					addslashes(urldecode($_POST['step1']['db_password'])),
					$_POST['step1']['db_host'],
					$_POST['step1']['db_port'],
					$_POST['step1']['db_name'],
					urldecode($_POST['step3']['admin_username']),
					addslashes(urldecode($_POST['step3']['admin_password'])),
					urldecode($_POST['step3']['admin_email']),
					$_POST['step3']['email_notification'],
					$_POST['step3']['allow_instructor_requests'],
					$_POST['step3']['auto_approve_instructors'],
					$_POST['step3']['max_file_size'],
					$_POST['step3']['max_course_size'],
					$_POST['step3']['max_course_float'],
					urldecode($_POST['step3']['ill_ext']),
					addslashes(urldecode($_POST['step3']['site_name'])),
					addslashes(urldecode($_POST['step3']['header_img'])),
					addslashes(urldecode($_POST['step3']['header_logo'])),
					addslashes(urldecode($_POST['step3']['home_url'])),
					$_POST['step1']['tb_prefix'],
					'FALSE',
					$comments,
					addslashes(urldecode($_POST['step3']['cache_dir'])));
	} else {	
		$values = array(urldecode($_POST['step2']['db_login']),
					addslashes(urldecode($_POST['step2']['db_password'])),
					$_POST['step2']['db_host'],
					$_POST['step2']['db_port'],
					$_POST['step2']['db_name'],
					urldecode($_POST['step3']['admin_username']),
					addslashes(urldecode($_POST['step3']['admin_password'])),
					urldecode($_POST['step3']['admin_email']),
					$_POST['step3']['email_notification'],
					$_POST['step3']['allow_instructor_requests'],
					$_POST['step3']['auto_approve_instructors'],
					$_POST['step3']['max_file_size'],
					$_POST['step3']['max_course_size'],
					$_POST['step3']['max_course_float'],
					urldecode($_POST['step3']['ill_ext']),
					addslashes(urldecode($_POST['step3']['site_name'])),
					addslashes(urldecode($_POST['step3']['header_img'])),
					addslashes(urldecode($_POST['step3']['header_logo'])),
					addslashes(urldecode($_POST['step3']['home_url'])),
					$_POST['step2']['tb_prefix'],
					'FALSE',
					$comments,
					addslashes(urldecode($_POST['step3']['cache_dir'])));
	}

	$config_template = str_replace($tokens, $values, $config_template);

	if (!$handle = fopen($filename, 'wb')) {
         return 0;
    }
	ftruncate($handle,0);
    if (!fwrite($handle, $config_template, strlen($config_template))) {
		return 0;
    }
        
    fclose($handle);
	return 1;				
}

$config_template = "<"."?php 
/************************************************************************/
/* ATutor                                                               */
/************************************************************************/
/* Copyright (c) 2002-2004 by Greg Gay, Joel Kronenberg, Heidi Hazelton */
/* http://atutor.ca                                                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
{GENERATED_COMMENTS}
/************************************************************************/
/************************************************************************/

/* the database user name                                               */
define('DB_USER',                      '{USER}');

/* the database password                                                */
define('DB_PASSWORD',                  '{PASSWORD}');

/* the database host                                                    */
define('DB_HOST',                      '{HOST}');

/* the database tcp/ip port                                             */
define('DB_PORT',                      '{PORT}');

/* the database name                                                    */
define('DB_NAME',                      '{DBNAME}');

/* The prefix to add to table names to avoid conflicts with existing    */
/* tables. Default: AT_                                                 */
define('TABLE_PREFIX',                 '{TABLE_PREFIX}');

/* your (ATutor system admin) username to let you add new instructors   */
define('ADMIN_USERNAME',               '{ADMIN_USERNAME}');


/* your (ATutor system admin) password to let you add new instructors   */
define('ADMIN_PASSWORD',               '{ADMIN_PASSWORD}');

/* your (admin) email address                                           */
define('ADMIN_EMAIL',                  '{ADMIN_EMAIL}');

/* do you want to receive emails when new instructor accounts           */
/* require approval                                                     */
define('EMAIL_NOTIFY',                 {EMAIL_NOTIFY});

/* allow regular account users to request their account to be           */
/* upgraded to instructor accounts.                                     */
define('ALLOW_INSTRUCTOR_REQUESTS',    {INSTRUCTOR_REQUESTS});

/* If ALLOW_INSTRUCTOR_REQUESTS is true then you can have the           */
/* requests approved instantly, otherwise each request will             */
/* have to be approved manually by the admin.                           */
define('AUTO_APPROVE_INSTRUCTORS',     {APPROVE_INSTRUCTORS});

/************************************************************************/
/* File manager options:                                                */

/* Default maximum allowable file size in Bytes to upload:              */
/* Will not override the upload_max_filesize in php.ini                 */
\$MaxFileSize   =   {MAX_FILE_SIZE}; /* 1 MB */

/* Default total maximum allowable course size in Bytes:                */
/* When this number is exceeded, no more uploads will be allowed        */
\$MaxCourseSize =  {MAX_COURSE_SIZE}; /* 10 MB */

/* Soft limit threshold:                                                */
/* How much a course can be over, while still allowing the              */
/* upload to finish.                                                    */
/* Therefore the real course limit is                                   */
/* \$MaxCourseSize + \$MaxCourseFloat, but when the float gets          */
/* used then no more uploads will be allowed.                           */
\$MaxCourseFloat =  {MAX_COURSE_FLOAT}; /* 2 MB */

/* Illegal file types, by extension. Include any extensions             */
/* you do not want to allow for uploading. (Just the extention          */
/* without the leading dot.)                                            */
\$IllegalExtentions = array({ILL_EXT});

/* The name of your course website.                                     */
/* Example: Acme University's Course Server                             */
/* Single quotes will have to be escaped with a slash: \'.              */
define('SITE_NAME',						'{SITE_NAME}');

/* Public area variables.   */
/* Top left header image  - approximately w:230 x h:90					*/
/* Default: images/pub_default.jpg										*/	
define('HEADER_IMAGE',					'{HEADER_IMG}');

/* Top right logo default: images/at-logo.gif */
define('HEADER_LOGO',					'{HEADER_LOGO}');

/* link for the 'home' menu item.  Will not show if empty */
define('HOME_URL',						'{HOME_URL}');

/* Default language to use, if not browser-defined or                   */
/* user-defined. 'en' is always available. Any other language           */
/* specified must already exist in the database.                        */
/* Default language: en                                                 */
define('DEFAULT_LANGUAGE',             'en');

/* Where the cache directory should be created. On a Windows            */
/* machine the path should look like C:\Windows\temp\. Path             */
/* must end in a slash. The directory must already exist.               */
/* Make empty or comment out to disable cacheing.                       */
/* Back slashes must be escaped if at the end: ex: ..tmp\\');           */
define('CACHE_DIR', '{CACHE_DIR}');

/* If you upgrading from a previous version you may want to				*/ 
/* keep backwards compatability on. It is recommended that if			*/ 
/* you do not have too many content files then you should slowly		*/ 
/* convert your content to 1.3 and disable backwards					*/ 
/* compatability.														*/ 
/* Default: false														*/ 
define('BACKWARDS_COMPATIBILITY',       {BACKWARDS_COMPATIBILITY});

/* DO NOT ALTER THIS LAST LINE                                          */
define('AT_INSTALL', true);

?".">";

?>