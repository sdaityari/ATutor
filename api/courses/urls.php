<?php

//require(AT_INCLUDE_PATH."../api/core/api_functions.php");

$url_prefix = "/courses";

$course_base_urls = array(
    "/" => "CourseHome",
);

$course_urls = generate_urls($course_base_urls, $url_prefix);

?>