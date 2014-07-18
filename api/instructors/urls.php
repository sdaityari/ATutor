<?php

if (!defined('AT_INCLUDE_PATH')) {
    exit;
}

$instructor_url_prefix = "/instructors";

$instructor_base_urls = array(
    "/" => "Instructors",
    "/:number/" => "Instructors",
    "/:number/courses/" => "InstructorCourses",
    "/:number/courses/:number" => "InstructorCourses",
    "/:number/courses/:number/tests" => "InstructorsTests",
    "/:number/courses/:number/tests/:number" => "InstructorsTests",
    "/:number/courses/:number/tests/:number/questions" => "InstructorsTestQuestions",
    "/:number/courses/:number/tests/:number/questions/:number" => "InstructorsTestQuestions",
    "/:number/courses/:number/instructors" => "CourseInstructorList",
    "/:number/courses/:number/students" => "CourseEnrolledList"
);

$instructor_urls = generate_urls($instructor_base_urls, $instructor_url_prefix);

?>
