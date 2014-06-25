<?php

if (!defined('AT_INCLUDE_PATH')) {
    exit;
}

$instructor_url_prefix = "/instructors";

$instructor_base_urls = array(
    "/" => "InstructorList",
    "/:number/" => "InstructorDetails",
    "/:number/courses/" => "InstructorCoursesList",
    "/:number/courses/:number" => "InstructorCoursesDetails",
    "/:number/courses/:number/instructors" => "CourseInstructorList",
    "/:number/courses/:number/students" => "CourseEnrolledList"
);

$instructor_urls = generate_urls($instructor_base_urls, $instructor_url_prefix);

?>