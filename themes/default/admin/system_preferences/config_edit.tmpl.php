<?php global $_config, $languageManager, $_config_defaults, $stripslashes;?>
<br />
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="form">
    <ul class="a11yAccordeon">
        <li class="a11yAccordeonItem">
            <div class="a11yAccordeonItemHeader">
                <strong>Website Settings</strong>
            </div>
            <div class="a11yAccordeonHideArea">
                <span class="required" title="<?php echo _AT('required_field'); ?>">*</span><label for="sitename"><?php echo _AT('site_name'); ?></label>
                <input type="text" name="site_name" size="40" maxlength="60" id="sitename" value="<?php if (!empty($_POST['site_name'])) { echo $stripslashes(htmlspecialchars($_POST['site_name'])); } else { echo $_config['site_name']; } ?>" />

                <br /><br /><label for="home_url"><?php echo _AT('home_url'); ?></label>
                <input type="text" name="home_url" size="50" maxlength="60" id="home_url" value="<?php if (!empty($_POST['home_url'])) { echo $stripslashes(htmlspecialchars($_POST['home_url'])); } else { echo $_config['home_url']; } ?>"  />

                <br /><br /><label for="default_lang"><?php echo _AT('default_language'); ?></label>
                <?php if (!empty($_POST['default_language'])) { 
                        $select_lang = $_POST['default_language']; 
                    } else { 
                        $select_lang = $_config['default_language'];
                    } ?>
                <?php if ($disabled): ?>
                    <select name="default_language" id="default_lang" disabled="disabled"><option><?php echo $select_lang; ?></option></select>
                <?php else: ?>
                    <?php $languageManager->printDropdown($select_lang, 'default_language', 'default_lang'); ?>
                <?php endif; ?>

                <br /><br /><span class="required" title="<?php echo _AT('required_field'); ?>">*</span><label for="cemail"><?php echo _AT('contact_email'); ?></label>
                <input type="text" name="contact_email" id="cemail" size="40" value="<?php if (!empty($_POST['email'])) { echo $stripslashes(htmlspecialchars($_POST['email'])); } else { echo $_config['contact_email']; } ?>"  />

                <br /><br /><label for="time_zone"><?php echo _AT('time_zone'); ?></label><br />

                <?php


                // Replace this hack to use the PHP timezone functions when the PHP requirement is raised to 5.3
                global $utc_timezones; // set in include/lib/constants.inc.php
                //$local_offset = ((date(Z)/3600));

                echo '<select name="time_zone" id="time_zone">';    
                    echo '<option value="0">'._AT('none').'</option>';
                foreach ($utc_timezones as $zone => $offset){
                    if(($offset[1]) == $_config['time_zone']){
                    echo '<option value="'.($offset[1]).'" selected="selected">'.$offset[0].' '.$offset[2].'</option>';
                    }else{
                    echo '<option value="'.($offset[1]).'">'.$offset[0].' '.$offset[2].'</option>';

                    }
                }
                echo "</select>";


                //echo '<input type="text" name="time_zone" value="'.$_config['time_zone'].'" size="5" maxlength="5"/> ';

                // If PHP 5+ generate a list of timezones
                /*
                if(phpversion() >= 5){
                    $timezone_names = timezone_identifiers_list();
                }else{
                // if less than PHP version 5, read a text file to generate the menu
                    $timezone_names = file("timezones.txt");
                }

                echo '<select name="time_zone">';
                foreach($timezone_names as $timezone_name){
                    if($timezone_name == $_config{'time_zone'}){
                        $selected = ' selected="selected"';
                    }
                    echo '<option'.$selected.'>'.$timezone_name.'</option>';
                    $selected = '';
                }
                echo '</select>';
                */

                echo AT_date(_AT('server_date_format'), '', AT_DATE_MYSQL_DATETIME);
                ?>

                <?php
                // disable this setting on ATutorSpaces
                global $db;
                $service_installed = queryDB('SELECT * from %smodules WHERE dir_name=%s && status =%s', Array(TABLE_PREFIX, '_core/services', '2'));
                if(!$service_installed){
                ?>
                <?php
                $timeout_arr = array(10,15,20,30,60,120,1440);
                ?>
                <br /><br /><label for="session_timeout"><?php echo _AT('session_timeout'); ?></label> (<?php echo _AT('default'); ?>: <?php echo $_config_defaults['session_timeout'].' '._AT('timeout_minutes'); ?>)
                <select name="session_timeout" id="session_timeout">
                <?php foreach($timeout_arr as $timeout_val){
                    
                     if($timeout_val == $_config['session_timeout']){
                     
                        if($timeout_val == '1440'){
                            echo '<option value="'.$timeout_val.'" selected="selected">'._AT('maximum').' 1 '. _AT('day').'</option>';
                        }else{
                            echo '<option value="'.$timeout_val.'" selected="selected">'.$timeout_val.'</option>';
                        }
                    }else{
                        if($timeout_val == '1440'){
                            echo '<option value="'.$timeout_val.'">'._AT('maximum').' 1 '. _AT('day').'</option>';
                        }else{
                            echo '<option value="'.$timeout_val.'">'.$timeout_val.'</option>';
                        }
                    }
                }
                ?>
                </select>
                
                <?php echo _AT('timeout_minutes'); ?>
                <?php } ?>

                <br /><br /><label for="maxfile"><?php echo _AT('maximum_file_size'); ?></label> (<?php echo _AT('default'); ?>: <?php echo $_config_defaults['max_file_size']; ?>)
                <input type="text" size="10" name="max_file_size" id="maxfile" value="<?php if (!empty($_POST['max_file_size'])) { echo $stripslashes(htmlspecialchars($_POST['max_file_size'])); } else { echo $_config['max_file_size']; } ?>"  /> <?php echo _AT('bytes'); ?>

                <br /><br /><label for="maximum_login_attempt"><?php echo _AT('maximum_login_attempt'); ?></label> (<?php echo _AT('default'); ?>: <?php echo $_config_defaults['max_login']; ?>)
                <input type="text" size="10" name="max_login" id="maximum_login_attempt" value="<?php if (!empty($_POST['max_login'])) { echo $stripslashes(htmlspecialchars($_POST['max_login'])); } else { echo $_config['max_login']; } ?>"  /> <?php echo _AT('times'); ?>

                <br /><br /><div class="row"><div style="float:left;"><?php echo _AT('use_captcha'); ?></div>
                    <div class="toggle-switch">
                        <?php if (extension_loaded('gd')): ?>
                        <input type="radio" name="use_captcha" value="1" id="use_captcha_y" <?php if($_config['use_captcha']) { echo 'checked="checked"'; }?>  /><label for="use_captcha_y"><?php echo _AT('enable'); ?></label> <input type="radio" name="use_captcha" value="0" id="use_captcha_n" <?php if(!$_config['use_captcha']) { echo 'checked="checked"'; }?>  /><label for="use_captcha_n"><?php echo _AT('disable'); ?></label>
                        <?php else: ?>
                        <input type="radio" name="use_captcha" value="1" id="use_captcha_y" disabled="disabled" /><label for="use_captcha_y"><?php echo _AT('enable'); ?></label> <input type="radio" name="use_captcha" value="0" id="use_captcha_n" checked="checked" /><label for="use_captcha_n"><?php echo _AT('disable'); ?></label>
                        <?php endif; ?>
                    </div>
                </div><br /><br />

            </div>
        </li>
        <li class="a11yAccordeonItem">
            <div class="a11yAccordeonItemHeader">
                <strong>Course Settings</strong>
            </div>
            <div class="a11yAccordeonHideArea">

                <div class="row">
                    <div style="float:left;"><?php echo _AT('allow_registration'); ?></div>
                    <div class="toggle-switch">
                        <input type="radio" name="allow_registration" value="1" id="reg_y" <?php if($_config['allow_registration']) { echo 'checked="checked"'; }?>  /><label for="reg_y"><?php echo _AT('enable'); ?></label> <input type="radio" name="allow_registration" value="0" id="reg_n" <?php if(!$_config['allow_registration']) { echo 'checked="checked"'; }?>  /><label for="reg_n"><?php echo _AT('disable'); ?></label>
                    </div>
                </div>

                <br /><br /><div class="row"><div style="float:left;"><?php echo _AT('allow_browse'); ?> </div>
                    <div class="toggle-switch">
                        <input type="radio" name="allow_browse" value="1" id="browse_y" <?php if($_config['allow_browse']) { echo 'checked="checked"'; }?>  /><label for="browse_y"><?php echo _AT('enable'); ?></label> <input type="radio" name="allow_browse" value="0" id="browse_n" <?php if(!$_config['allow_browse']) { echo 'checked="checked"'; }?>  /><label for="browse_n"><?php echo _AT('disable'); ?></label>
                    </div>
                </div>

                <br /><br /><div class="row"><div style="float:left;"><?php echo _AT('allow_unenroll'); ?></div>
                    <div class="toggle-switch">
                        <input type="radio" name="allow_unenroll" value="1" id="ene_y" <?php if($_config['allow_unenroll']) { echo 'checked="checked"'; }?>  /><label for="ene_y"><?php echo _AT('enable'); ?></label> <input type="radio" name="allow_unenroll" value="0" id="ene_n" <?php if(!$_config['allow_unenroll']) { echo 'checked="checked"'; }?>  /><label for="ene_n"><?php echo _AT('disable'); ?></label>
                    </div>
                </div>

                <br /><br /><div class="row">
                    <div style="float:left;"><?php echo _AT('require_email_confirmation'); ?></div>
                        <div class="toggle-switch">
                            <input type="radio" name="email_confirmation" value="1" id="ec_y" <?php if ($_config['email_confirmation']) { echo 'checked="checked"'; }?>  /><label for="ec_y"><?php echo _AT('enable'); ?></label> <input type="radio" name="email_confirmation" value="0" id="ec_n" <?php if(!$_config['email_confirmation']) { echo 'checked="checked"'; }?>  /><label for="ec_n"><?php echo _AT('disable'); ?></label>
                        </div>
                </div>

                <br /><br /><div class="row"><div style="float:left;"><?php echo _AT('allow_instructor_create_course'); ?></div>
                    <div class="toggle-switch">
                        <input type="radio" name="disable_create" value="0" id="create_n" <?php if(!$_config['disable_create']) { echo 'checked="checked"'; }?>  /><label for="create_n"><?php echo _AT('enable'); ?></label><input type="radio" name="disable_create" value="1" id="create_y" <?php if($_config['disable_create']) { echo 'checked="checked"'; }?>  /><label for="create_y"><?php echo _AT('disable'); ?></label>
                    </div>
                </div>

                <br /><br /><div class="row">
                    <div style="float:left;"><?php echo _AT('course_dir_name'); ?></div>
                    <div class="toggle-switch">
                            <input type="radio" name="course_dir_name" value="1" id="cdn_y" <?php if($_config['course_dir_name']) { echo 'checked="checked"'; }?> /><label for="cdn_y"><?php echo _AT('enable'); ?></label> <input type="radio" name="course_dir_name" value="0" id="cdn_n" <?php if(!$_config['course_dir_name']) { echo 'checked="checked"'; }?>  /><label for="cdn_n"><?php echo _AT('disable'); ?></label>
                    </div>
                </div>

                <br /><br /><div class="row">
                    <div style="float:left;"><?php echo _AT('master_list_authentication'); ?></div>
                    <div class="toggle-switch">
                        <input type="radio" name="master_list" value="1" id="ml_y" <?php if ($_config['master_list']) { echo 'checked="checked"'; }?>  /><label for="ml_y"><?php echo _AT('enable'); ?></label> 

                        <input type="radio" name="master_list" value="0" id="ml_n" <?php if(!$_config['master_list']) { echo 'checked="checked"'; }?>  /><label for="ml_n"><?php echo  _AT('disable'); ?></label>
                    </div>
                </div>

                <br /><br /><label for="course_backups"><?php echo _AT('course_backups'); ?></label> (<?php echo _AT('default'); ?>: <?php echo $_config_defaults['course_backups']; ?>)
                <input type="text" size="2" name="course_backups" id="course_backups" value="<?php if (!empty($_POST['course_backups'])) { echo $stripslashes(htmlspecialchars($_POST['course_backups'])); } else { echo $_config['course_backups']; } ?>"  />

                <br /><br /><label for="maxcourse"><?php echo _AT('maximum_course_size'); ?></label> (<?php echo _AT('default'); ?>: <?php echo $_config_defaults['max_course_size']; ?>)
                <input type="text" size="10" name="max_course_size" id="maxcourse" value="<?php if (!empty($_POST['max_course_size'])) { echo $stripslashes(htmlspecialchars($_POST['max_course_size'])); } else { echo $_config['max_course_size']; } ?>"  /> <?php echo _AT('bytes'); ?>

                <br /><br /><label for="float"><?php echo _AT('maximum_course_float'); ?></label> (<?php echo _AT('default'); ?>: <?php echo $_config_defaults['max_course_float']; ?>)
                <input type="text" size="10" name="max_course_float" id="float" value="<?php if (!empty($_POST['max_course_float'])) { echo $stripslashes(htmlspecialchars($_POST['max_course_float'])); } else { echo $_config['max_course_float']; } ?>"  /> <?php echo _AT('bytes'); ?>

                <br /><br /><label for="ext"><?php echo _AT('illegal_file_extensions'); ?></label><br />
                <textarea name="illegal_extentions" cols="24" id="ext" rows="2" class="formfield" ><?php if ($_config['illegal_extentions']) { echo str_replace('|',' ',$_config['illegal_extentions']); }?></textarea>

            </div>
        </li>

        <li class="a11yAccordeonItem">
            <div class="a11yAccordeonItemHeader">
                <strong>Instructor Settings</strong>
            </div>
            <div class="a11yAccordeonHideArea">

                <div class="row">
                    <div style="float:left;"><?php echo _AT('allow_instructor_registration'); ?> </div>
                    <div class="toggle-switch">
                        <input type="radio" name="allow_instructor_registration" value="1" id="enrollreg_y" <?php if($_config['allow_instructor_registration']) { echo 'checked="checked"'; }?>  /><label for="enrollreg_y"><?php echo _AT('enable'); ?></label> <input type="radio" name="allow_instructor_registration" value="0" id="enrollreg_n" <?php if(!$_config['allow_instructor_registration']) { echo 'checked="checked"'; }?>  /><label for="enrollreg_n"><?php echo _AT('disable'); ?></label>
                    </div>
                </div>

                <br /><br /><div class="row"><div style="float:left;"><?php echo _AT('allow_instructor_requests'); ?></div>
                    <div class="toggle-switch">
                        <input type="radio" name="allow_instructor_requests" value="1" id="air_y" <?php if($_config['allow_instructor_requests']) { echo 'checked="checked"'; }?>  /><label for="air_y"><?php echo _AT('enable'); ?></label> <input type="radio" name="allow_instructor_requests" value="0" id="air_n" <?php if(!$_config['allow_instructor_requests']) { echo 'checked="checked"'; }?>  /><label for="air_n"><?php echo _AT('disable'); ?></label>
                    </div>
                </div>

                <br /><br /><div class="row">
                <div style="float:left;"><?php echo _AT('instructor_request_email_notification'); ?></div>
                    <div class="toggle-switch">
                        <input type="radio" name="email_notification" value="1" id="en_y" <?php if ($_config['email_notification']) { echo 'checked="checked"'; }?>  /><label for="en_y"><?php echo _AT('enable'); ?></label> <input type="radio" name="email_notification" value="0" id="en_n" <?php if(!$_config['email_notification']) { echo 'checked="checked"'; }?>  /><label for="en_n"><?php echo _AT('disable'); ?></label>
                    </div>
                </div>

                <br /><br /><div class="row">
                    <div style="float:left;"><?php echo _AT('auto_approve_instructors'); ?></div>
                    <div class="toggle-switch">
                        <input type="radio" name="auto_approve_instructors" value="1" id="aai_y" <?php if($_config['auto_approve_instructors']) { echo 'checked="checked"'; }?>  /><label for="aai_y"><?php echo _AT('enable'); ?></label> <input type="radio" name="auto_approve_instructors" value="0" id="aai_n" <?php if(!$_config['auto_approve_instructors']) { echo 'checked="checked"'; }?>  /><label for="aai_n"><?php echo _AT('disable'); ?></label>
                    </div>
                </div>

                <br /><br /><div class="row">
                    <div><?php echo _AT('display_name_format'); ?> </div>
                    (<?php echo _AT('default'); ?>: <?php echo _AT($this->display_name_formats[$_config_defaults['display_name_format']], _AT('login_name'), _AT('first_name'), _AT('second_name'), _AT('last_name')); ?>)<br />
                    <?php foreach ($this->display_name_formats as $key => $value): ?>
                        <input type="radio" name="display_name_format" value="<?php echo $key; ?>" id="dnf<?php echo $key; ?>" <?php if ($_config['display_name_format'] == $key) { echo 'checked="checked"'; }?> /><label for="dnf<?php echo $key; ?>"><?php echo _AT($value, _AT('login_name'), _AT('first_name'), _AT('second_name'), _AT('last_name')); ?></label><br />
                    <?php endforeach; ?>
                </div>

                <br /><div class="row">
                    <div style="float:left;"><?php echo _AT('user_contributed_notes'); ?> </div>
                    <div class="toggle-switch">
                        <input type="radio" name="user_notes" value="1" id="un_y" <?php if($_config['user_notes']) { echo 'checked="checked"'; }?>  /><label for="un_y"><?php echo _AT('enable'); ?></label> <input type="radio" name="user_notes" value="0" id="un_n" <?php if(!$_config['user_notes']) { echo 'checked="checked"'; }?>  /><label for="un_n"><?php echo _AT('disable'); ?></label>
                    </div>
                </div><br />

            </div>
        </li>

        <li class="a11yAccordeonItem">
            <div class="a11yAccordeonItemHeader">
                <strong>Miscellaneous Settings</strong>
            </div>
            <div class="a11yAccordeonHideArea">

                <div class="row">
                    <div style="float:left;"><?php echo _AT('theme_specific_categories'); ?></div>
                    <div class="toggle-switch">
                        <input type="radio" name="theme_categories" value="1" id="tc_y" <?php if($_config['theme_categories']) { echo 'checked="checked"'; }?>  /><label for="tc_y"><?php echo _AT('enable'); ?></label> <input type="radio" name="theme_categories" value="0" id="tc_n" <?php if(!$_config['theme_categories']) { echo 'checked="checked"'; }?>  /><label for="tc_n"><?php echo _AT('disable'); ?></label>
                    </div>
                </div>

                <br /><br /><div class="row"><div style="float:left;"><?php echo _AT('show_current'); ?> </div>
                    <div class="toggle-switch">
                        <input type="radio" name="show_current" value="1" id="current_y" <?php if($_config['show_current']) { echo 'checked="checked"'; }?>  /><label for="current_y"><?php echo _AT('enable'); ?></label> <input type="radio" name="show_current" value="0" id="current_n" <?php if(!$_config['show_current']) { echo 'checked="checked"'; }?>  /><label for="current_n"><?php echo _AT('disable'); ?></label>
                    </div>
                </div>

                <br /><br /><label for="cache"><?php echo _AT('cache_directory'); ?></label>
                <input type="text" name="cache_dir" id="cache" size="40" value="<?php if (!empty($_POST['cache_dir'])) { echo $stripslashes(htmlspecialchars($_POST['cache_dir'])); } else { echo $_config['cache_dir']; } ?>"  />

                <br /><br /><label for="cache_lif"><?php echo _AT('cache_life'); ?></label>
                (<?php echo _AT('default'); ?>: <?php echo ($_config_defaults['cache_life']); ?>)
                <input type="text" name="cache_life" id="cache" size="8" maxlength="8" value="<?php if (!empty($_POST['cache_life'])) { echo $stripslashes(htmlspecialchars($_POST['cache_life'])); } else { echo $_config['cache_life']; } ?>"  />

                <br /><br /><label for="latex_server"><?php echo _AT('latex_server'); ?></label>
                <input type="text" name="latex_server" id="latex_server" size="40" value="<?php if (!empty($_POST['latex_server'])) { echo $stripslashes(htmlspecialchars($_POST['latex_server'])); } else { echo $_config['latex_server']; } ?>"  />

                 <br /><br /><label for="sent_msgs_ttl"><?php echo _AT('sent_msgs_ttl_text'); ?></label> (<?php echo _AT('default'); ?>: <?php echo $_config_defaults['sent_msgs_ttl']; ?>)
                <input type="text" size="3" name="sent_msgs_ttl" id="sent_msgs_ttl" value="<?php if (!empty($_POST['sent_msgs_ttl'])) { echo intval($_POST['sent_msgs_ttl']); } else { echo $_config['sent_msgs_ttl']; } ?>"  />

                <br /><br /><div class="row">
                    <div style="float:left;"><?php echo _AT('auto_check_new_version'); ?></div>
                    <div class="toggle-switch">
                        <input type="radio" name="check_version" value="1" id="cv_y" <?php if($_config['check_version']) { echo 'checked="checked"'; }?>  /><label for="cv_y"><?php echo _AT('enable'); ?></label> <input type="radio" name="check_version" value="0" id="cv_n" <?php if(!$_config['check_version']) { echo 'checked="checked"'; }?>  /><label for="cv_n"><?php echo _AT('disable'); ?></label>
                    </div>
                </div>

                <br /><br /><div class="row">
                    <div style="float:left;"><?php echo _AT('file_storage_version_control'); ?> </div>
                    <div class="toggle-switch">
                        <input type="radio" name="fs_versioning" value="1" id="cf_y" <?php if($_config['fs_versioning']) { echo 'checked="checked"'; }?>  /><label for="cf_y"><?php echo _AT('enable'); ?></label> <input type="radio" name="fs_versioning" value="0" id="cf_n" <?php if(!$_config['fs_versioning']) { echo 'checked="checked"'; }?>  /><label for="cf_n"><?php echo _AT('disable'); ?></label>
                    </div>
                </div>

                <br /><br /><div class="row">
                    <input type="hidden" name="old_enable_mail_queue" value="<?php echo $_config['enable_mail_queue']; ?>" />
                    <div style="float:left;"><?php echo _AT('enable_mail_queue'); ?></div>
                    <div class="toggle-switch">
                        <?php if (!$_config['last_cron'] || (time() - (int) $_config['last_cron'] > 2 * 60 * 60)): ?>
                            <input type="radio" name="enable_mail_queue" id="mq_y" value="1" disabled="disabled" />
                            <label for="mq_y"><?php echo _AT('enable'); ?></label> 
                            <input type="radio" name="enable_mail_queue" value="0" id="mq_n" checked="checked" />
                            <label for="mq_n"><?php echo _AT('disable'); ?></label>
                        <?php else: ?>
                            <input type="radio" name="enable_mail_queue" value="1" id="mq_y" <?php if($_config['enable_mail_queue']) { echo 'checked="checked"'; }?>  />
                            <label for="mq_y"><?php echo _AT('enable'); ?></label> 
                            <input type="radio" name="enable_mail_queue" value="0" id="mq_n" <?php if(!$_config['enable_mail_queue']) { echo 'checked="checked"'; }?>  />
                            <label for="mq_n"><?php echo _AT('disable'); ?></label>
                        <?php endif; ?>
                    </div>
                    <br /><?php echo _AT('mail_queue_cron'); ?>
                </div>

                <br /><div class="row">
                    <div style="float:left;"><?php echo _AT('auto_install_languages'); ?> </div>
                    <div class="toggle-switch">
                        <?php if (!$_config['last_cron'] || (time() - (int) $_config['last_cron'] > 2 * 60 * 60)): ?>
                            <input type="radio" name="auto_install_languages" id="ai_y" value="1" disabled="disabled" />
                            <label for="ai_y"><?php echo _AT('enable'); ?> </label>
                            <input type="radio" name="auto_install_languages" value="0" id="ai_n" checked="checked" />
                            <label for="ai_n"><?php echo _AT('disable'); ?></label>
                        <?php else: ?>
                            <input type="radio" name="auto_install_languages" value="1" id="ai_y" <?php if($_config['auto_install_languages']) { echo 'checked="checked"'; }?>  /><label for="ai_y"><?php echo _AT('enable'); ?></label> <input type="radio" name="auto_install_languages" value="0" id="ai_n" <?php if(!$_config['auto_install_languages']) { echo 'checked="checked"'; }?>  /><label for="ai_n"><?php echo _AT('disable'); ?></label>
                        <?php endif; ?>
                    </div>
                    <br /><?php echo _AT('auto_install_languages_cron'); ?>
                </div>

                <br /><div class="row">
                    <div style="float:left;"><?php echo _AT('pretty_url'); ?></div>
                     <div class="toggle-switch">
                        <input type="radio" name="pretty_url" value="1" id="pu_y" <?php if($_config['pretty_url']) { echo 'checked="checked"'; }?> onclick="apache_mod_rewrite_toggler(true);"/><label for="pu_y"><?php echo _AT('enable'); ?></label> <input type="radio" name="pretty_url" value="0" id="pu_n" <?php if(!$_config['pretty_url']) { echo 'checked="checked"'; }?> onclick="apache_mod_rewrite_toggler(false);"/><label for="pu_n"><?php echo _AT('disable'); ?></label>
                    </div>
                </div>

                <br /><br /><div class="row">
                    <div style="float:left;"><?php echo _AT('apache_mod_rewrite'); ?></div>
                        <?php if (function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules())): ?>
                            <div class="toggle-switch">
                                <input type="radio" name="apache_mod_rewrite" value="1" id="mr_y" <?php if($_config['apache_mod_rewrite']) { echo 'checked="checked"'; }?> /><label for="mr_y"><?php echo _AT('enable'); ?></label> <input type="radio" name="apache_mod_rewrite" value="0" id="mr_n" <?php if(!$_config['apache_mod_rewrite']) { echo 'checked="checked"'; }?>  /><label for="mr_n"><?php echo _AT('disable'); ?></label>
                            </div>
                        <?php else: ?>
                            <div class="toggle-switch">
                                <input type="radio" name="apache_mod_rewrite" value="1" id="mr_y" disabled="disabled" /><label for="mr_y"><?php echo _AT('enable'); ?></label> <input type="radio" name="apache_mod_rewrite" value="0" id="mr_n" checked="checked" /><label for="mr_n"><?php echo _AT('disable'); ?></label>
                            </div>
                        <?php endif; ?>
                </div><br />

            </div>
        </li>
    </ul>


    <div class="row buttons" style="text-align:right; margin-top:1em; padding-right:9.5%;">
            <input type="submit" name="submit" value="<?php echo _AT('save'); ?>" accesskey="s"  />
        <input type="submit" name="cancel" value="<?php echo _AT('cancel'); ?>"  />
    </div>
</form>

<link rel="stylesheet" href="<?php echo AT_BASE_HREF; ?>jscripts/a11yAccordeon/a11yAccordeon.css" type="text/css" />
<link rel="stylesheet" href="<?php echo AT_BASE_HREF; ?>jscripts/toggleSwitch/toggleSwitch.css" type="text/css" />
<script type="text/javascript" src="<?php echo AT_BASE_HREF; ?>jscripts/a11yAccordeon/a11yAccordeon.min.js"></script>
<script type="text/javascript" src="<?php echo AT_BASE_HREF; ?>jscripts/toggleSwitch/toggleSwitch.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    a11yAccordeon({
        container: ".a11yAccordeon",
        hiddenLinkDescription: "This contains settings",
        showSearch: true
    });
    createToggleSwitch({
        color: "yellow",
        className: "toggle-switch",
    });
});
</script>
