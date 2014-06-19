<?php

function get_courses_main($access_level = ADMIN_ACCESS_LEVEL, $clause = NULL, $course_id = -1, $member_id = -1) {
    /*
     * Function to generate query for course related GET calls
     * Makes call to api_get_backbone after generating query
     */

    $token = get_access_token(getallheaders(), $access_level);

    $query = "SELECT c.course_id, c.cat_id, cc.cat_name, c.created_date, ".
        "c.title, c.description, c.notify, c.copyright, c.icon, c.release_date, c.primary_language, ".
        "c.end_date, c.banner FROM %scourses c ".
        "INNER JOIN %scourse_cats cc ON c.cat_id = cc.cat_id";

    if ($member_id != -1) {
        $query = $query." INNER JOIN %scourse_enrollment ce ON c.course_id = ce.course_id".
        " WHERE ce.member_id = ".$member_id;
    }

    if ($course_id != -1) {
        $query = $member_id == -1 ? $query . " WHERE " : $query . " AND ";
        $query = $query."c.course_id = ".$course_id;
    }

    if ($clause) {
        $query = ($member_id == -1 && $course_id == -1) ? $query." WHERE " : $query." AND ";
        $query = $query.$clause;
    }

    $array = $member_id != -1 ? array(TABLE_PREFIX, TABLE_PREFIX, TABLE_PREFIX) : array(TABLE_PREFIX, TABLE_PREFIX);

    $one_row = $course_id == -1? false : true;

    api_backbone(array(
        "request_type" => HTTP_GET, 
        "access_level" => $access_level,
        "query" => $query,
        "query_array" => $array,
        "one_row" => $one_row,
        "member_id" => $member_id
    ));
}

function api_backbone($options) {
    /*
     * Function to perform all API calls
     * Every call has a token, checks access level and performs a query
     * This function takes those as argument and logs the request
     */

    if (DEBUG) {
        print vsprintf($options["query"], $options["query_array"]);
        print "\n\n";
    }

    $defaults = array(
        "request_type" => HTTP_GET,
        "access_level" => ADMIN_ACCESS_LEVEL,
        "member_id" => -1
    );

    $options = array_merge($defaults, $options);

    list($options["token"], $token_member_id) = get_access_token(getallheaders(), $options["access_level"], true);

    if ($options["member_id"] != -1 && // Checking if member id was passed
            $token_member_id != $options["member_id"] && // Checking if token belongs to member requested
            !check_access_level($options["token"])) { // Checking if request comes from admin
        http_response_code(404);
        exit;
    }

    $options["log"] = generate_basic_log($_SERVER);
    $options["log"]["token"] = $options["token"];

    // Checking if object with ID exists
    if ($options["query_id_existence"]) {
        $existence = queryDB($options["query_id_existence"], $options["query_id_existence_array"], true);
        if (!$existence) {
            http_response_code(404);
            print_message(ERROR, RESOURCE_DOES_NOT_EXIST);
            exit;
        }
    }

    $callback_func = $options["returned_id_name"] ? "mysql_insert_id" : "mysql_affected_rows";

    $result = queryDB($options["query"], $options["query_array"], $options["one_row"], true, $callback_func);

    if ($options["one_row"] && $options["request_type"] == HTTP_GET && count($result) == 0){
        http_response_code(404);
        exit;
    }

    $response = json_encode($result);
    $options["log"]["response"] = $response;
    log_request($options["log"]);

    switch ($options["request_type"]) {
        case HTTP_GET:
            echo $response;
            break;

        case HTTP_POST:
            return_created_id($result, $options["log"]);
            break;

        case HTTP_PUT:
            print_message(SUCCESS, ACTION_COMPLETED_SUCCESSFULLY, $options["log"]);
            break;

        case HTTP_DELETE:
            if (!$result) {
                http_response_code(404);
                print_message(ERROR, RESOURCE_DOES_NOT_EXIST, $options["log"]);
            } else {
                print_message(SUCCESS, ACTION_COMPLETED_SUCCESSFULLY, $options["log"]);
            }
            break;

        default:
            break;
    }
}

?>