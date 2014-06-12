<?php

class InstructorCoursesList {
    function get($instructor_id) {
        /*$log = generate_basic_log($_SERVER);
        list($token, $member_id) = get_access_token(getallheaders(), INSTRUCTOR_ACCESS_LEVEL, true);
        if ($instructor_id != $member_id) {
            http_response_code(404);
            exit;
        }*/

        $clause = create_SQL_clause(array(
            "title" => "c.title",
            "category_id" => "c.cat_id",
            "primary_language" => "c.primary_language"
        ), $_GET);

        $response = get_courses_main(INSTRUCTOR_ACCESS_LEVEL, $clause, -1, $instructor_id);
/*        $log["response"] = $response;
        log_request($log);
        echo $response;*/
    }
}

class InstructorCoursesDetails {
    function get($instructor_id, $course_id) {
        get_courses_main(INSTRUCTOR_ACCESS_LEVEL, NULL, $course_id, $instructor_id);
    }
}