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

define('AT_INCLUDE_PATH', '../../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
require(AT_INCLUDE_PATH.'../mods/_standard/file_storage/file_storage.inc.php');

$owner_type = abs($_REQUEST['ot']);
$owner_id   = abs($_REQUEST['oid']);
$owner_arg_prefix = '?ot='.$owner_type.SEP.'oid='.$owner_id. SEP;
if (!fs_authenticate($owner_type, $owner_id)) {
    $msg->addError('ACCESS_DENIED');
    header('Location: '.url_rewrite('mods/_standard/file_storage/index.php', AT_PRETTY_URL_IS_HEADER));
    exit;
}

if (isset($_GET['done'])) {
    header('Location: '.url_rewrite('mods/_standard/file_storage/index.php'.$owner_arg_prefix.'folder='.abs($_GET['folder']), AT_PRETTY_URL_IS_HEADER));
    exit;
}

require(AT_INCLUDE_PATH.'header.inc.php');

$id = abs($_GET['id']);

$files = fs_get_revisions($id, $owner_type, $owner_id);
if (!$files) {
    $msg->printErrors('FILE_NOT_FOUND');
    require(AT_INCLUDE_PATH.'footer.inc.php');
    exit;
}
?>

<?php if ($_config['fs_versioning']): ?>
    <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" name="ot" value="<?php echo $owner_type; ?>" />
    <input type="hidden" name="oid" value="<?php echo $owner_id; ?>" />
    <div class="input-form" style="width: 95%">
        <div class="row">
            <select name="id" size="<?php echo min(count($files), 5);?>">
                <?php foreach ($files as $file): ?>
					<?php
						$selected = '';
						if ($file['file_id'] == $id) {
							$current_file = $file;
							$selected = ' selected="selected"';
						}
					?>
					<option value="<?php echo $file['file_id'];?>" <?php echo $selected; ?>><?php echo _AT('revision'); ?> <?php echo $file['num_revisions']; ?>. <?php echo htmlentities_utf8($file['file_name']); ?> - <?php echo $file['num_comments']; ?> <?php echo _AT('comments'); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="row buttons">
			<input type="submit" name="comments" value="<?php echo _AT('comments'); ?>" />
			<input type="submit" name="done" value="<?php echo _AT('done'); ?>" />
		</div>
	</div>
	<input type="hidden" name="folder" value="<?php echo $current_file['folder_id']; ?>" />
	</form>
<?php else: ?>
	<?php $current_file = current($files); ?>
<?php endif; ?>

<div class="input-form">
	<div class="row">
		<h3><?php echo htmlentities_utf8($current_file['file_name']); ?> <small> - <?php echo _AT('revision'); ?> <?php echo $current_file['num_revisions']; ?></small></h3>
		<span style="font-size: small"><?php echo get_display_name($current_file['member_id']); ?> - <?php echo AT_date(_AT('filemanager_date_format'), $current_file['date'], AT_DATE_MYSQL_DATETIME); ?></span>
		<p><?php echo nl2br(htmlspecialchars($current_file['description'])); ?></p>
	</div>
</div>

<?php
    $_GET['comment_id'] = isset($_GET['comment_id']) ? intval($_GET['comment_id']) : 0;
	$sql = "SELECT * FROM %sfiles_comments WHERE file_id=%d ORDER BY date ASC";
	$rows_files_comments = queryDB($sql, array(TABLE_PREFIX, $id));
	
	if(count($rows_files_comments) > 0): ?>
	
	<?php foreach($rows_files_comments as $row){ ?>
		<div class="input-form" id="comment<?php echo $row['comment_id']; ?>">
			<div class="row">
				<h4><?php echo get_display_name($row['member_id']); ?> - <?php echo AT_date(_AT('filemanager_date_format'), $row['date'], AT_DATE_MYSQL_DATETIME); ?></h4>
				<p id="comment-description-<?php echo $row['comment_id']; ?>"><?php echo nl2br(htmlspecialchars($row['comment'])); ?></p>

                <?php if ($row['member_id'] == $_SESSION['member_id']): ?>
                    <div style="width:100%; display:none;" id="edit-comment-<?php echo $row['comment_id']; ?>" >
                        <textarea id="textarea-<?php echo $row['comment_id']; ?>"><?php echo $row['comment']; ?></textarea>
                        <div style="text-align:right; font-size: smaller">
                            <a href="javascript:null();" onclick="ATutor.fileStorage.editCommentSubmit({ot: '<?php echo $owner_type; ?>', oid:'<?php echo $owner_id; ?>', fileId: '<?php echo $id; ?>', id: '<?php echo $row['comment_id']; ?>'});"><?php echo _AT('submit'); ?></a> |
                            <a href="javascript:null();" onclick="ATutor.fileStorage.editCommentHide('<?php echo $row['comment_id']; ?>');"><?php echo _AT('cancel'); ?></a>
                        </div>
                    </div>
					<div style="text-align:right; font-size: smaller" id="comment-edit-delete-<?php echo $row['comment_id']; ?>">
                        <a href="javascript:null();" onclick="ATutor.fileStorage.editCommentShow('<?php echo $row['comment_id']; ?>');"><?php echo _AT('edit') ?></a> | <a href="javascript:null();" onclick="ATutor.fileStorage.deleteComment({ot: '<?php echo $owner_type; ?>', oid: '<?php echo $owner_id; ?>', fileId: '<?php echo $id; ?>', id: '<?php echo $row['comment_id']; ?>'});" ><?php echo _AT('delete'); ?></a>
                    </div>
				<?php endif; ?>

                <?php if ($row['member_id'] != $_SESSION['member_id'] && $current_file['member_id'] == $_SESSION['member_id']): ?>
                    <div style="text-align:right; font-size: smaller" id="comment-edit-delete-<?php echo $row['comment_id']; ?>">
					    <a href="javascript:null();" onclick="ATutor.fileStorage.deleteComment({ ot: '<?php echo $owner_type; ?>', oid: '<?php echo $owner_id; ?>', fileId: '<?php echo $id; ?>', id: '<?php echo $row['comment_id']; ?>'});"><?php echo _AT('delete'); ?></a>

						</div>
				    <?php endif; ?>
				</div>
		    </div>
	<?php }  ?>
<?php elseif(0): ?>
	<div class="input-form">
		<div class="row">
			<p><?php echo _AT('none_found'); ?></p>
		</div>
	</div>
<?php endif; ?>

<?php if ($_SESSION['is_guest'] == 0): ?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'].$owner_arg_prefix; ?>id=<?php echo $id; ?>" id="comment-add-form">
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" name="folder" value="<?php echo $current_file['folder_id']; ?>" />
<div class="input-form">
	<div class="row">
		<span class="required" title="<?php echo _AT('required_field'); ?>">*</span><label for="comment"><?php echo _AT('comment'); ?></label><br />
		<textarea cols="40" rows="4" id="comment" name="comment"></textarea>
	</div>

	<div class="row buttons">
		<input type="button" name="submit" onclick="ATutor.fileStorage.addComment({ot: '<?php echo $owner_type; ?>', oid:'<?php echo $owner_id; ?>', fileId: '<?php echo $id; ?>'});" value="<?php echo _AT('post'); ?>" />
		<input type="button" name="cancel" onclick="ATutor.fileStorage.cancelAddComment();" value="<?php echo _AT('cancel'); ?>" />
	</div>
</div>
</form>
<?php endif; ?>
<script type="text/javascript" src="<?php echo AT_BASE_HREF; ?>jscripts/ajax/FileStorage.js"></script>
<script type="text/javascript" src="<?php echo AT_BASE_HREF; ?>jscripts/ajax/Functions.js"></script>
<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>
