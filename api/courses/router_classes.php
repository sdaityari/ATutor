<?php

class CourseList {
    function get() {
        $log = generate_basic_log($_SERVER);
        $token = get_access_token(getallheaders(), STUDENT_ACCESS_LEVEL);

        $log["token"] = $token;

        // TODO Raise 404 error
        $response = get_courses($_GET);
        $log["response"] = $response;
        log_request($log);
        echo $response;
    }
}

class CourseDetails {
    function get($course_id) {
        $log = generate_basic_log($_SERVER);
        $token = get_access_token(getallheaders(), STUDENT_ACCESS_LEVEL);
        $log["token"] = $token;

        $response = get_course_details($course_id);
        $log["response"] = $response;
        log_request($log);
        echo $response;
    }

    function delete($course_id) {
        $log = generate_basic_log($_SERVER);
        $token = get_access_token(getallheaders(), ADMIN_ACCESS_LEVEL);

        $log["token"] = $token;

        $query = queryDB("DELETE FROM %scourses WHERE course_id = %d",
            array(TABLE_PREFIX, $course_id));
        if ($query == 0) {
            http_response_code(404);
            print_error("COURSE_DOES_NOT_EXIST");
        } else {
            print_success("COURSE_DELETED_SUCCESSFULLY", $log);
        }
    }
}

?>