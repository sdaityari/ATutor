<?php

define("AT_INCLUDE_PATH", "../include/");

// All restricted areas would be handled using tokens, mangaed from within the API
$_user_location = "public";

include_once(AT_INCLUDE_PATH."../api/core/api_functions.php");
include_once(AT_INCLUDE_PATH."../api/core/constants.php");

include_once(AT_INCLUDE_PATH."vitals.inc.php");
include_once(AT_INCLUDE_PATH."lib/vital_funcs.inc.php");

// Enable CORS
header("Access-Control-Allow-Origin: *");


//XXX: Code to check if mod is enabled. Show 404 or error if not enabled.
if (!api_module_status()) {
    //header('HTTP/1.0 404 Not Found');
    print_message(ERROR, MODULE_DISABLED);
    exit;
}

require("lib/Toro.php");

// Core classes
include(AT_INCLUDE_PATH."../api/core/router_classes.php");
include(AT_INCLUDE_PATH."../api/shared/shared_functions.php");

// Courses
include(AT_INCLUDE_PATH."../api/courses/urls.php");
include(AT_INCLUDE_PATH."../api/courses/router_classes.php");

// Students
include(AT_INCLUDE_PATH."../api/students/urls.php");
include(AT_INCLUDE_PATH."../api/students/router_classes.php");

// Instructors
include(AT_INCLUDE_PATH."../api/instructors/urls.php");
include(AT_INCLUDE_PATH."../api/instructors/router_classes.php");

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
    "/test/:string" => "TestHandler",

    // Authentication
    "/login/" => "Authentication",
    "/logout/" => "Authentication",
);

// The URL routes
Toro::serve(array_merge(
    $base_urls,
    $course_urls,
    $student_urls,
    $instructor_urls
));

?>