<?php

define('AT_INCLUDE_PATH', '../include/');

require(AT_INCLUDE_PATH.'../api/core/api_functions.php');

//XXX: Code to check if mod is enabled. Show 404 or error if not enabled.
//XXX: Include important files and set include path.
require("lib/Toro.php");

include("courses/urls.php");
include("courses/router_classes.php");

//XXX: Classes/Handlers be included from a separate directories
class HelloHandler {
    function get() {
        // Some message to be shown if someone opens the base URL
        echo "Hello, world";
    }
}

$base_urls = array(
    "/" => "HelloHandler",
);

// The URL routes
Toro::serve(array_merge(
    $base_urls,
    $course_urls
));

?>