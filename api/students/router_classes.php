<?php

class StudentCoursesList {
    function get($student_id) {
        $log = generate_basic_log($_SERVER);
        list($token, $member_id) = get_access_token(getallheaders(), STUDENT_ACCESS_LEVEL, true);
        if ($student_id != $member_id) {
            // Some error message
        }

        $response = get_courses_of_member($student_id);
        $log["response"] = $response;
        log_request($log);
        echo $response;       
    }
}