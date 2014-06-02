<?php

class InstructorCoursesList {
    function get($instructor_id) {
        $log = generate_basic_log($_SERVER);
        list($token, $member_id) = get_access_token(getallheaders(), STUDENT_ACCESS_LEVEL, true);
        if ($instructor_id != $member_id) {
            // Some error message and 404 raised
        }

        $response = get_courses_of_member($instructor_id);
        $log["response"] = $response;
        log_request($log);
        echo $response;       
    }
}