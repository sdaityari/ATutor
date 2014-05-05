<?php

define('AT_INCLUDE_PATH', '../include/');

// All restricted areas would be handled using tokens, mangaed from within the API
$_user_location = 'public';

include_once(AT_INCLUDE_PATH.'../api/core/api_functions.php');
include_once(AT_INCLUDE_PATH.'vitals.inc.php');
include_once(AT_INCLUDE_PATH.'lib/vital_funcs.inc.php');

//XXX: Code to check if mod is enabled. Show 404 or error if not enabled.
if (!api_module_status()) {
    //header('HTTP/1.0 404 Not Found');
    echo "Module is not enabled! Please contact the ATutor administrator.";
    exit;
}

require("lib/Toro.php");

// Courses app
include("courses/urls.php");
include("courses/router_classes.php");

//XXX: Classes/Handlers be included from a separate directories
class TestHandler {
    // This class is an example of how different variables are going to be handled
    function get() {
        // Some message to be shown if someone opens the base URL
        echo "Hello, world";
    }
    function post($someString) {
        // Get all headers in the request
        $headers = getallheaders();
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $data = array(
            // GET/POST parameters
            "firstName" => AT_print($firstName),
            "lastName" => AT_print($lastName),
            // Parameter in URL
            "sentString" => AT_print($someString),
            // Parameter in custom header 'x-API-Token'
            "token" => AT_print($headers['x-API-Token']));
        print json_encode($data);
    }
}

$base_urls = array(
    "/" => "TestHandler",
    // Example of variable in the URL
    "/test/:string" => "TestHandler"
);

// The URL routes
Toro::serve(array_merge(
    $base_urls,
    $course_urls
));

?>