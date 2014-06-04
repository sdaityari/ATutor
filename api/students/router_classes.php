<?php

class StudentCoursesList {
    function get($student_id) {
        $log = generate_basic_log($_SERVER);
        list($token, $member_id) = get_access_token(getallheaders(), STUDENT_ACCESS_LEVEL, true);
        if ($student_id != $member_id) {
            http_response_code(404);
            exit;
        }

        $response = get_courses($_GET, $student_id);
        $log["response"] = $response;
        log_request($log);
        echo $response;       
    }
}

class StudentCoursesDetails {
    function get($student_id, $course_id) {
        $log = generate_basic_log($_SERVER);
        list($token, $member_id) = get_access_token(getallheaders(), INSTRUCTOR_ACCESS_LEVEL, true);
        if ($student_id != $member_id) {
            http_response_code(404);
            exit;
        }

        $response = get_course_details($course_id, $student_id);
        $log["response"] = $response;
        log_request($log);
        echo $response;
    }
}

?>