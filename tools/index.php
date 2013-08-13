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

define('AT_INCLUDE_PATH', '../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');

require(AT_INCLUDE_PATH.'header.inc.php');
$module_list = $moduleFactory->getModules(AT_MODULE_STATUS_ENABLED, 0, TRUE);
$keys = array_keys($module_list);

echo '<div id="masonry-tools">';
foreach ($keys as $module_name) {
	$module = $module_list[$module_name];
	if ($module->getPrivilege() && authenticate($module->getPrivilege(), AT_PRIV_RETURN) && ($parent = $module->getChildPage('tools/index.php')) && page_available($parent)) {
		echo '<div class="masonry-box"><div class="masonry-title"><a href="' . $parent . '">' . $module->getName() . '</a></div>';
		if (isset($_pages[$parent]['children'])) {
			echo '<ul class = "masonry-links">';
			foreach ($_pages[$parent]['children'] as $child) {
				if (page_available($child)) {
					echo '<li class="child-tool"><a href="'.$child.'">'._AT($_pages[$child]['title_var']).'</a></li>';
				}
			}
			echo '</ul>';
		}
		echo '</div>';
	}
}
echo '</div>';
?>

<link rel="stylesheet" href="<?php echo AT_BASE_HREF.'jscripts/masonry/masonry.css'; ?>" type="text/css" />
<script type="text/javascript" src="<?php echo AT_BASE_HREF; ?>jscripts/masonry/masonry.min.js"></script>
<script type="text/javascript">
    var msnry = new Masonry( document.querySelector('#masonry-tools'), {
        // options
        itemSelector: '.masonry-box',
        gutter: 10
    });
</script>

<?php
require(AT_INCLUDE_PATH.'footer.inc.php');
?>
