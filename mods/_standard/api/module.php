<?php
/*******
 * doesn't allow this file to be loaded with a browser.
 */
if (!defined('AT_INCLUDE_PATH')) { exit; }

/******
 * this file must only be included within a Module obj
 */
if (!isset($this) || (isset($this) && (strtolower(get_class($this)) != 'module'))) {
    exit(__FILE__ . ' is not a Module');
}


/******
* modules sub-content to display on course home detailed view
*/
//$this->_list['hello_world'] = array('title_var'=>'hello_world','file'=>'mods/hello_world/sublinks.php');

/*******
 * assign the instructor and admin privileges to the constants.
 */
define('AT_PRIV_api',       $this->getPrivilege());
define('AT_ADMIN_PRIV_api', $this->getAdminPrivilege());

/*******
 * create a side menu box/stack.
 */
$this->_stacks['api'] = array('title_var'=>'api', 'file'=>'mods/api/side_menu.inc.php');
// ** possible alternative: **
// $this->addStack('api', array('title_var' => 'api', 'file' => './side_menu.inc.php');

/*******
 * if this module is to be made available to students on the Home or Main Navigation.
 */
//$_student_tool = 'mods/api/index.php';
// ** possible alternative: **
// $this->addTool('./index.php');

/*******
 * add the admin pages when needed.
 */
if (admin_authenticate(AT_ADMIN_PRIV_api, TRUE) || admin_authenticate(AT_ADMIN_PRIV_ADMIN, TRUE)) {
    $this->_pages[AT_NAV_ADMIN] = array('mods/api/index_admin.php');
    $this->_pages['mods/api/index_admin.php']['title_var'] = 'api';
    $this->_pages['mods/api/index_admin.php']['parent']    = AT_NAV_ADMIN;
}

/*******
 * instructor Manage section:
 */
$this->_pages['mods/api/index_instructor.php']['title_var'] = 'api';
$this->_pages['mods/api/index_instructor.php']['parent']   = 'tools/index.php';
// ** possible alternative: **
// $this->pages['./index_instructor.php']['title_var'] = 'api';
// $this->pages['./index_instructor.php']['parent']    = 'tools/index.php';

/*******
 * student page.
 */
//$this->_pages['mods/api/index.php']['title_var'] = 'api';
//$this->_pages['mods/api/index.php']['img']       = 'mods/api/api.jpg';

/*******
 * public pages
 */
/*$this->_pages[AT_NAV_PUBLIC] = array('mods/api/index_public.php');
$this->_pages['mods/api/index_public.php']['title_var'] = 'api';
$this->_pages['mods/api/index_public.php']['parent'] = 'login.php';
$this->_pages['login.php']['children'] = array('mods/api/index_public.php');*/

/*******
 * my start page pages
 */
/*$this->_pages[AT_NAV_START]  = array('mods/api/index_mystart.php');
$this->_pages['mods/api/index_mystart.php']['title_var'] = 'api';
$this->_pages['mods/api/index_mystart.php']['parent'] = 'users/index.php';
$this->_pages['users/index.php']['children'] = array('mods/api/index_mystart.php');*/

/*******
 * Add a new tool into the tools icon bar on the content editor page
 * Only need to use one of the two methods listed below to add a tool.
 */

// The execution of the php code specified in 'file' field returns an array of the rows that are
// desired to be displayed in the popup window mods/_core/tool_manager/index.php
// @see mods/_core/tool_manager/index.php
// @see mods/_core/tool_manager/forums_tool.php
// $this->_tool['api'] = array('title_var'=>'api','file'=>'mods/api/action.php');

// ** possible alternative: **
// Provides more flexibility in terms of controlling the action at clicking on the tool icon.
// Rather than always poping up mods/_core/tool_manager/index.php defined in $this->_tool, 
// "js" field defines the listener for the click event on the new tool icon.
//$this->_content_tools[] = array("id"=>"api",
//                                "class"=>"fl-col clickable",
//                                "src"=>AT_BASE_HREF."mods/api/images/api.png",
//                                "title"=>_AT('api'),
//                                "alt"=>_AT('api'),
//                                "text"=>_AT('api_text'),
//                                "js"=>AT_BASE_HREF."mods/api/api.js");

?>