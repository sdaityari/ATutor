<?php

class InstructorCoursesList {
    function get($instructor_id) {
        $log = generate_basic_log($_SERVER);
        list($token, $member_id) = get_access_token(getallheaders(), INSTRUCTOR_ACCESS_LEVEL, true);
        if ($instructor_id != $member_id) {
            http_response_code(404);
            exit;
        }

        $response = get_courses_of_member($instructor_id);
        $log["response"] = $response;
        log_request($log);
        echo $response;
    }
}

class InstructorCoursesDetails {
    function get($instructor_id, $course_id) {
        $log = generate_basic_log($_SERVER);
        list($token, $member_id) = get_access_token(getallheaders(), INSTRUCTOR_ACCESS_LEVEL, true);
        if ($instructor_id != $member_id) {
            http_response_code(404);
            exit;
        }

        $response = get_course_details($instructor_id, $course_id);
        $log["response"] = $response;
        log_request($log);
        echo $response;
    }
}