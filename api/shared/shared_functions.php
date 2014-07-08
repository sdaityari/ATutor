<?php

function get_courses_main($access_level = ADMIN_ACCESS_LEVEL, $clause = NULL, $course_id = -1, $member_id = -1) {
    /*
     * Function to generate query for course related GET calls
     * Makes call to api_get_backbone after generating query
     */

    return;

    // $token = get_access_token(getallheaders(), $access_level);

    // $query = "SELECT c.course_id, c.cat_id, cc.cat_name, c.created_date, ".
    //     "c.title, c.description, c.notify, c.copyright, c.icon, c.release_date, c.primary_language, ".
    //     "c.end_date, c.banner FROM %scourses c ".
    //     "INNER JOIN %scourse_cats cc ON c.cat_id = cc.cat_id";

    // if ($member_id != -1) {
    //     $query = $query." INNER JOIN %scourse_enrollment ce ON c.course_id = ce.course_id".
    //     " WHERE ce.member_id = ".addslashes($member_id);
    // }

    // if ($course_id != -1) {
    //     $query = $member_id == -1 ? $query . " WHERE " : $query . " AND ";
    //     $query = $query."c.course_id = ".addslashes($course_id);
    // }

    // if ($clause) {
    //     $query = ($member_id == -1 && $course_id == -1) ? $query." WHERE " : $query." AND ";
    //     $query = $query.$clause;
    // }

    // $array = $member_id != -1 ? array(TABLE_PREFIX, TABLE_PREFIX, TABLE_PREFIX) : array(TABLE_PREFIX, TABLE_PREFIX);

    // $one_row = $course_id == -1? false : true;

    // api_backbone(array(
    //     "request_type" => HTTP_GET,
    //     "access_level" => $access_level,
    //     "query" => $query,
    //     "query_array" => $array,
    //     "one_row" => $one_row,
    //     "member_id" => $member_id
    // ));
}

function get_members_main ($role, $access_level = ADMIN_ACCESS_LEVEL, $member_id = -1, $clause = "") {
    return;
    // $query = "SELECT member_id, login, email, first_name, last_name, website, gender, address, ".
    //     "postal, city, province, country, phone, language, last_login, creation_date FROM %smembers ".
    //     "WHERE status = %d";

    // if ($member_id != -1) {
    //     $query .= " AND member_id = ".addslashes($member_id);
    //     $one_row = true;
    // } else {
    //     $one_row = false;
    // }

    // if ($clause) {
    //     $query .= " AND " . $clause;
    // }

    // $array = array(TABLE_PREFIX, $role);

    // api_backbone(array(
    //     "request_type" => HTTP_GET,
    //     "access_level" => $access_level,
    //     "query" => $query,
    //     "query_array" => $array,
    //     "one_row" => $one_row,
    //     "member_id" => $member_id
    // ));
}

function get_enrollment_members($instructor_id, $course_id, $role){
    $query = "SELECT m.member_id, m.login, m.email, m.first_name, m.last_name, m.website, m.gender, m.address, ".
        "m.postal, m.city, m.province, m.country, m.phone, m.language, m.last_login, m.creation_date FROM %smembers m ".
        "INNER JOIN %scourse_enrollment ce ".
        "ON m.member_id = ce.member_id ".
        "WHERE m.status = %d AND ce.course_id = %d";

    $array = array(TABLE_PREFIX, TABLE_PREFIX, $role, $course_id);

    $query_id_existence = "SELECT COUNT(*) FROM %scourse_enrollment WHERE course_id = %d AND member_id = %d";
    $query_id_existence_array = array(TABLE_PREFIX, $course_id, $instructor_id);

    api_backbone(array(
        "request_type" => HTTP_GET,
        "access_level" => INSTRUCTOR_ACCESS_LEVEL,
        "query" => $query,
        "query_array" => $array,
        "query_id_existence" => $query_id_existence,
        "query_id_existence_array" => $query_id_existence_array
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
        if (!$existence["COUNT(*)"] || !$existence) {
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
    log_request($options["log"], $options["request_type"]);

    switch ($options["request_type"]) {
        case HTTP_GET:
            echo $response;
            break;

        case HTTP_POST:
            return_created_id($result, $options["log"]);
            return $result;
            break;

        case HTTP_PUT:
            print_message(SUCCESS, ACTION_COMPLETED_SUCCESSFULLY, $options["log"], $options["request_type"]);
            break;

        case HTTP_DELETE:
            if (!$result) {
                http_response_code(404);
                print_message(ERROR, RESOURCE_DOES_NOT_EXIST, $options["log"], $options["request_type"]);
            } else {
                print_message(SUCCESS, ACTION_COMPLETED_SUCCESSFULLY, $options["log"], $options["request_type"]);
            }
            break;

        default:
            break;
    }

}

?>
