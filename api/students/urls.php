<?php

if (!defined('AT_INCLUDE_PATH')) {
    exit;
}

$student_url_prefix = "/students";

$student_base_urls = array(
    "/" => "Students",
    "/:number/" => "Students",
    "/:number/courses/" => "StudentCourses",
    "/:number/courses/:number" => "StudentCourses",
    "/:number/courses/:number/tests" => "StudentTests",
    "/:number/courses/:number/tests/:number/questions" => "StudentsTestQuestions",
    "/:number/courses/:number/tests/:number/questions/:number" => "StudentsTestQuestions",
    "/:number/courses/:number/tests/:number" => "StudentTests"
);

$student_urls = generate_urls($student_base_urls, $student_url_prefix);

?>
