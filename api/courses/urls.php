<?php

if (!defined('AT_INCLUDE_PATH')) {
    exit;
}

$courses_url_prefix = "/courses";

$course_base_urls = array(
    "/" => "Courses",
    "/:number" => "Courses",
    "/categories/" => "CourseCategories",
    "/categories/:number" => "CourseCategories"
);

$course_urls = generate_urls($course_base_urls, $courses_url_prefix);
?>
