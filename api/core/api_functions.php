<?php

if (!defined('AT_INCLUDE_PATH')) {
    exit;
}

function api_module_status() {
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

function check_token($token){
    $check = queryDB("SELECT * FROM %sapi WHERE token = '%s' AND expiry > CURRENT_TIMESTAMP", array(TABLE_PREFIX, $token), token);
    if (!$check) {
        http_response_code(401);
        print_error("TOKEN_DOES_NOT_EXIST");
        exit;
    }

    // Update modified timestamp
    queryDB("UPDATE %sapi SET modified = CURRENT_TIMESTAMP, expiry = NOW() + INTERVAL 1 DAY WHERE token = '%s'", array(TABLE_PREFIX, $token));
    return true;
}

function get_access_token($headers) {
    $token = addslashes($headers['x-AT-API-TOKEN']);
    return check_token($token)?$token:false;
}

function print_error($message) {
    print json_encode(array(
        "errorMessage" => $message
    ));
}

?>