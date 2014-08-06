<?php

if (!defined('AT_INCLUDE_PATH')) {
    exit;
}

// The prefix common to all URLs in this boilerplate example app
$test_url_prefix = "/tests";

$test_base_urls = array(
    "/" => "Tests",
    "/:number" => "Tests",
    "/:number/questions" => "TestQuestions",
    "/:number/questions/:number" => "TestQuestions",
    "/:number/questions/:number/:alpha/" => "TestQuestionsAssociation"
);

$test_urls = generate_urls($test_base_urls, $test_url_prefix);

?>
