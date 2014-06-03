<?php

class CourseList {
    function get() {
        $log = generate_basic_log($_SERVER);
        $token = get_access_token(getallheaders(), STUDENT_ACCESS_LEVEL);

        $log["token"] = $token;

        // GET parameters
        $garbage_int = rand(100000, 500000);
        $garbage = md5($garbage_int);
        $title = $_GET["title"]?addslashes($_GET["title"]):$garbage;
        //$release_date = $_GET["release_date"]?addslashes($_GET["release_date"]):$garbage;
        //$end_date = $_GET["end_date"]?addslashes($_GET["end_date"]):$garbage;
        $category_id = $_GET["category_id"]?addslashes($_GET["category_id"]):$garbage_int;
        $primary_language = $_GET["primary_language"]?addslashes($_GET["primary_language"]):$garbage;

        $courses = queryDB("SELECT c.course_id, c.cat_id, cc.cat_name, c.created_date, ".
            "c.title, c.description, c.notify, c.copyright, c.icon, c.release_date, c.primary_language, ".
            "c.end_date, c.banner FROM %scourses c, %scourse_cats cc WHERE c.cat_id = cc.cat_id ".
            "AND ('%s' = '%s' OR c.title like '%%%s%%')".
            //"AND ('%s' = '%s' OR c.release_date = %s) ".
            //"AND ('%s' = '%s' OR c.end_date = %s) ".
            "AND (%d = %d OR c.cat_id = %d) ".
            "AND ('%s' = '%s' OR c.primary_language = '%s')",
            array(TABLE_PREFIX, TABLE_PREFIX,
                  $title, $garbage, $title,
                  //$release_date, $garbage, $release_date,
                  //$end_date, $garbage, $end_date,
                  $category_id, $garbage_int, $category_id,
                  $primary_language, $garbage, $primary_language,)
            );

        // TODO Raise 404 error
        $response = json_encode($courses);
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
        $course = queryDB("SELECT c.course_id, c.cat_id, cc.cat_name, c.created_date, ".
            "c.title, c.description, c.notify, c.copyright, c.icon, c.release_date, c.end_date, ".
            "c.banner FROM %scourses c, %scourse_cats cc WHERE c.cat_id = cc.cat_id AND c.course_id = %d",
            array(TABLE_PREFIX, TABLE_PREFIX, $course_id), true);

        // TODO Raise 404 error
        $response = json_encode($course);
        $log["response"] = $response;
        log_request($log);
        echo $response;
    }
}

?>