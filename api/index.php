<?php

//XXX: Code to check if mod is enabled. Show 404 or error if not enabled.
//XXX: Include important files and set include path.

require('lib/Toro.php');

//XXX: Classes/Handlers be included from a separate directories
class HelloHandler {
    function get() {
        echo "Hello, world";
    }
}

// The URL routes
Toro::serve(array(
    "/" => "HelloHandler",
));

?>