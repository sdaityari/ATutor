<?php

if (!defined('AT_INCLUDE_PATH')) {
    exit;
}

// The prefix common to all URLs in this boilerplate example app
$question_url_prefix = "/questions";

$question_base_urls = array(
    "/" => "Questions",
    "/:number" => "Questions",
    "/categories/" => "QuestionCategories",
    "/categories/:number" => "QuestionCategories"
);

$question_urls = generate_urls($question_base_urls, $question_url_prefix);

?>
