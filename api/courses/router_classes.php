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

        $courses = queryDB("SELECT x.course_id, x.cat_id, y.cat_name, x.created_date, ".
            "x.title, x.description, x.notify, x.copyright, x.icon, x.release_date, x.primary_language, ".
            "x.end_date, x.banner FROM %scourses x, %scourse_cats y WHERE x.cat_id = y.cat_id ".
            "AND ('%s' = '%s' OR x.title like '%%%s%%')".
            //"AND ('%s' = '%s' OR x.release_date = %s) ".
            //"AND ('%s' = '%s' OR x.end_date = %s) ".
            "AND (%d = %d OR x.cat_id = %d) ".
            "AND ('%s' = '%s' OR x.primary_language = '%s')",
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
        $course = queryDB("SELECT x.course_id, x.cat_id, y.cat_name, x.created_date, ".
            "x.title, x.description, x.notify, x.copyright, x.icon, x.release_date, x.end_date, ".
            "x.banner FROM %scourses x, %scourse_cats y WHERE x.cat_id = y.cat_id AND x.course_id = %d",
            array(TABLE_PREFIX, TABLE_PREFIX, $course_id), true);

        // TODO Raise 404 error
        $response = json_encode($course);
        $log["response"] = $response;
        log_request($log);
        echo $response;
    }
}

?>