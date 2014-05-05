<?php

if (!defined('AT_INCLUDE_PATH')) {
    exit;
}

$url_prefix = "/courses";

$course_base_urls = array(
    "/" => "CourseHome",
);

$course_urls = generate_urls($course_base_urls, $url_prefix);

?>