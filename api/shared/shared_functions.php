<?php

function get_courses_main($access_level = ADMIN_ACCESS_LEVEL, $clause = NULL, $course_id = -1, $member_id = -1) {
    $log = generate_basic_log($_SERVER);
    list($token, $token_member_id) = get_access_token(getallheaders(), $access_level, true);

    if ($member_id != -1 && // Checking if member id was passed
            $token_member_id != $member_id && // Checking if token belongs to member requested
            !is_admin($token)) { // Checking if request comes from admin
        http_response_code(404);
        exit;
    }

    $one_row = $course_id == -1? false : true;

    $query = "SELECT c.course_id, c.cat_id, cc.cat_name, c.created_date, ".
    "c.title, c.description, c.notify, c.copyright, c.icon, c.release_date, c.primary_language, ".
    "c.end_date, c.banner FROM %scourses c, %scourse_cats cc, %scourse_enrollment ce WHERE c.cat_id = cc.cat_id";

    if ($member_id != -1) {
        $query = $query." AND ce.member_id = ".$member_id;
    }

    if ($course_id != -1) {
        $query = $query." AND c.course_id = ".$course_id;
    }

    if ($clause) {
        $query = $query." AND ".$clause;
    }

    $courses = queryDB($query, array(TABLE_PREFIX, TABLE_PREFIX, TABLE_PREFIX), $one_row);

    if ($one_row && count($courses) == 0){
        http_response_code(404);
    }

    $response = json_encode($courses);
    $log["response"] = $response;
    log_request($log);
    echo $response;
}

?>