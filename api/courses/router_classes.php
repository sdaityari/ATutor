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
        /*$course = queryDB("SELECT c.course_id, c.cat_id, cc.cat_name, c.created_date, ".
            "c.title, c.description, c.notify, c.copyright, c.icon, c.release_date, c.end_date, ".
            "c.banner FROM %scourses c, %scourse_cats cc WHERE c.cat_id = cc.cat_id AND c.course_id = %d",
            array(TABLE_PREFIX, TABLE_PREFIX, $course_id), true);*/

        // TODO Raise 404 error
        $response = get_course_details($course_id);
        $log["response"] = $response;
        log_request($log);
        echo $response;
    }
}

?>