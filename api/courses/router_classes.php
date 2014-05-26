<?php

class CourseHome {
    function get() {
        $token = get_access_token(getallheaders());
        echo "Hello, courses!";
    }
}

?>