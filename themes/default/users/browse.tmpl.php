<?php require(AT_INCLUDE_PATH.'header.inc.php'); ?>
<div style="text-align:right; font-size: smaller; padding-right:5%;">
	<form method="get" id="browse-courses-form">
        <input type="text" name="search" id="search" class="search-bar" placeholder="<?php echo _AT('search'); ?>" /> <br />
        <a href="javascript:null();" id="advanced-search" onclick="ATutor.browseCourses.toggleAdvanced();">[+] Advanced</a>
        <span style="display:none;" id="match-buttons-row"> <br />
            <?php echo _AT('search_match'); ?>:
            <input type="radio" name="include" value="all" id="match_all" <?php echo $this->checked_include_all; ?> /><label for="match_all"><?php echo _AT('search_all_words'); ?></label> 
            <input type="radio" name="include" value="one" id="match_one" <?php echo $this->checked_include_one; ?> /><label for="match_one"><?php echo _AT('search_any_word'); ?></label>
        </span>
	    <span style="display:none;" id="access-row" class="row">
		    <br /><?php echo _AT('access'); ?>
		    <input type="radio" name="access" value="private" id="s1" /><label for="s1"><?php echo _AT('private'); ?></label> 
		    <input type="radio" name="access" value="protected" id="s2" /><label for="s2"><?php echo _AT('protected'); ?></label>
		    <input type="radio" name="access" value="public" id="s3" /><label for="s3"><?php echo _AT('public'); ?></label>
		    <input type="radio" name="access" value="" id="s" <?php if ($_GET['access'] == '') { echo 'checked="checked"'; } ?> /><label for="s"><?php echo _AT('all'); ?></label>
	    </span>
	    <?php if ($this->has_categories): ?>
		    <span class="row">
			    <label for="category"><?php echo _AT('category'); ?></label><br/>
			    <select name="category" id="category">
				    <option value="-1">- - - <?php echo _AT('cats_all'); ?> - - -</option>
				    <option value="0" <?php if ($_GET['category'] == 0) { echo 'selected="selected"'; } ?>>- - - <?php echo _AT('cats_uncategorized'); ?> - - -</option>
				    <?php echo $this->categories_select; ?>
			    </select>
		    </span>
	    <?php endif; ?>
    </form>
</div>
<div class="container" style="width:95%; margin:auto;">

<ul class="a11yAccordeon" style="margin-bottom:1em;">
	<?php foreach ($this->courses_rows as $row){ ?>
    <li class="a11yAccordeonItem" id="accordeon_<?php echo $row['course_id']; ?>">
        <div class="a11yAccordeonItemHeader">
            <img src="<?php echo AT_INCLUDE_PATH."../mods/_standard/photos/images/";
                if ($row['access'] == 'public') {
                    echo 'unlocked.png';
                } else {
                    echo 'locked.png';
                }
            ?>" alt="<?php echo $row['access']; ?>" style="float:left; vertical-align:middle; height:1.5em;" />
            <a href="<?php echo url_rewrite('bounce.php?course='.$row['course_id'], true); ?>"><strong><?php echo htmlentities_utf8($row['title']); ?></strong></a>
        </div>
        <div class="accordeonA11yHideArea">
            <?php if ($row['icon']) { // if a course icon is available, display it here. ?>
            <div style="float:left; width:15%; max-width:100px;">
                <?php
                    $style_for_title = 'style="height: 1.5em;"';

                    //Check if this is a custom icon, if so, use get_course_icon.php to get it
                    //Otherwise, simply link it from the images/
                    $path = AT_CONTENT_DIR.$row['course_id']."/custom_icons/";
                    if (file_exists($path.$row['icon'])) {
                        if (defined('_ATFORCE_GET_FILE') && AT_FORCE_GET_FILE) {
                            $course_icon = 'get_course_icon.php/?id='.$row['course_id'];
                        } else {
                            $course_icon = 'content/' . $row['course_id'] . '/';
                        }
                    } else {
                        $course_icon = 'images/courses/'.$row['icon'];
                    }
                ?>
                <a href="<?php echo url_rewrite('bounce.php?course='.$row['course_id'], true); ?>"><img src="<?php echo $course_icon; ?>" class="headicon" alt="<?php echo htmlentities_utf8($row['title']); ?>" style="float:left;margin-right:.5em;"/></a>
            </div>
                <?php } //endif ?>
            <div style="width:85%; float:left;">
            <ul>
                <?php if ($row['description']): ?>
                <li>
                    <?php echo _AT('description'); ?>:
                    <?php echo substr(htmlentities_utf8($row['description'], true),0,150);
                    if(strlen($row['description']) > 150) {
                        echo "...";
                    }
                    ?>
                </li>
                <?php endif; ?>
                <?php if (is_array($this->cats) && $row['cat_id'] != 0): ?>
                    <li>
                        <?php echo _AT('category'); ?>: <a href="<?php echo $_SERVER['PHP_SELF'].'?'.$page_string.SEP; ?>category=<?php echo $row['cat_id']; ?>"><?php echo $this->cats[$row['cat_id']]; ?></a>
                    </li>
                <?php endif; ?>
                <li>
                    <?php echo _AT('instructor'); ?>: <a href="<?php echo AT_BASE_HREF; ?>contact_instructor.php?id=<?php echo $row['course_id']; ?>"><?php echo get_display_name($row['member_id']); ?></a>
                </li>

                <li>
                    <?php echo _AT('access'); ?>: <?php echo _AT($row['access']); ?>
                </li>
                <?php
                    // insert enrolment link if allowed
                    if (isset($row['enroll_link'])) : ?>
                        <li>
                            <?php echo _AT('shortcuts'); ?>: - <small><?php echo $row['enroll_link']; ?></small>
                        </li>
                <?php endif; ?>
            </ul>
            </div>
            <div style="clear:both;"></div>
        </div>
    </li>
	<?php } // end foreach ?>
</ul>
<div id="no-results-found" class="input-form" style="<?php if ($this->courses_rows) { echo 'display:none;';} ?> text-align:center; margin:1em;"><strong><?php echo _AT('no_results_available'); ?></strong></div>
</div>
<script type="text/javascript">
//<!--
var ATutor = ATutor || {};
ATutor.courseInfo = <?php echo json_encode($this->courses_rows); ?>;
//-->
</script>
<script type="text/javascript" src="<?php echo AT_BASE_HREF; ?>jscripts/infusion/lib/jquery/core/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo AT_BASE_HREF; ?>jscripts/ATutorBrowseCourses.js"></script>
<script type="text/javascript" src="<?php echo AT_BASE_HREF; ?>jscripts/a11yAccordeon.js"></script>
<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>
