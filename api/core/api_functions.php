<?php

if (!defined('AT_INCLUDE_PATH')) {
    exit;
}

function api_module_status() {
    // To check if the module is activated/activated
    $enabled = queryDB("SELECT * FROM %smodules WHERE dir_name = '%s' and status = %d",
        array(TABLE_PREFIX, "_standard/api", 2));
    return count($enabled)?true:false;
}

function generate_urls($old_array, $prefix) {
    // Add prefix to all indices of old array
    $new_array = array();
    foreach($old_array as $key => $value) {
        $new_array[$prefix.$key] = $value;
    }
    return $new_array;
}

function check_token($token, $minimum_access_level){
    $check = queryDB("SELECT access_level, member_id FROM %sapi WHERE token = '%s' AND expiry > CURRENT_TIMESTAMP",
        array(TABLE_PREFIX, $token), true);
    if (!$check) {
        http_response_code(401);
        print_error("TOKEN_DOES_NOT_EXIST");
        exit;
    } else if ($check["access_level"] > $minimum_access_level) {
        http_response_code(401);
        print_error("YOU_ARE_NOT_AUTHORIZED_TO_ACCESS_THIS_RESOURCE");
        exit;
    }

    // Update modified timestamp
    queryDB("UPDATE %sapi SET modified = CURRENT_TIMESTAMP, expiry = NOW() + INTERVAL 1 DAY WHERE token = '%s'", array(TABLE_PREFIX, $token));
    return $check["member_id"];
}

function get_access_token($headers, $minimum_access_level = ADMIN_ACCESS_LEVEL, $return_member_id = false) {
    $token = addslashes($headers[TOKEN_NAME]);
    $member_id = check_token($token, $minimum_access_level);

    if ($member_id && $return_member_id){
        return array($token, $member_id);
    } else if ($member_id) {
        return $token;
    } else {
        return false;
    }
}

function print_error($message) {
    print json_encode(array(
        "errorMessage" => $message
    ));
}

function generate_basic_log($request) {
    $log = array();
    $log["ip_address"] = $request["REMOTE_ADDR"];
    $log["request_uri"] = $request["REQUEST_URI"];
    $log["http_method"] = $request["REQUEST_METHOD"];
    return $log;
}

function log_request($log) {
    queryDB("INSERT INTO %sapi_logs(ip_address, request_uri, http_method, token, response) VALUES('%s', '%s', '%s', '%s', '%s')",
        array(TABLE_PREFIX, $log["ip_address"], $log["request_uri"], $log["http_method"], $log["token"], $log["response"]));
}

function get_courses_of_member($member_id){
    $courses = queryDB("SELECT x.course_id, x.cat_id, y.cat_name, x.created_date, ".
        "x.title, x.description, x.notify, x.copyright, x.icon, x.release_date, x.primary_language, ".
        "x.end_date, x.banner FROM %scourses x, %scourse_cats y, %scourse_enrollment z WHERE x.cat_id = y.cat_id ".
        "AND z.member_id = %d AND z.course_id = x.course_id", array(TABLE_PREFIX, TABLE_PREFIX, TABLE_PREFIX,
        $member_id));
    return json_encode($courses);
}

?>