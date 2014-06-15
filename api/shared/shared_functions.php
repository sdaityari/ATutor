<?php

function get_courses_main($access_level = ADMIN_ACCESS_LEVEL, $clause = NULL, $course_id = -1, $member_id = -1) {
    /*
     * Function to generate query for course related GET calls
     * Makes call to api_get_backbone after generating query
     */
    list($token, $token_member_id) = get_access_token(getallheaders(), $access_level, true);

    if ($member_id != -1 && // Checking if member id was passed
            $token_member_id != $member_id && // Checking if token belongs to member requested
            !check_access_level($token)) { // Checking if request comes from admin
        http_response_code(404);
        exit;
    }

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

    $array = array(TABLE_PREFIX, TABLE_PREFIX, TABLE_PREFIX);

    $one_row = $course_id == -1? false : true;

    api_get_backbone($token, $access_level, $query, $array, $one_row);
}

function api_get_backbone($token, $access_level, $query, $array, $one_row = false) {
    /*
     * Function to perform all GET API calls
     * Every call has a token, checks access level and performs a query
     * This function takes those as argument and logs the request
     */
    $log = generate_basic_log($_SERVER);

    if (!check_access_level($token, $access_level)) {
        http_response_code(404);
        exit;
    }

    $log["token"] = $token;

    $result = queryDB($query, $array, $one_row);

    if ($one_row && count($result) == 0){
        http_response_code(404);
        exit;
    }

    $response = json_encode($result);
    $log["response"] = $response;
    log_request($log);
    echo $response;
}

?>