<?php

if (!defined('AT_INCLUDE_PATH')) {
    exit;
}

function api_module_status(){
    $enabled = queryDB("SELECT * FROM %smodules WHERE dir_name = '%s' and status = %d",
        array(TABLE_PREFIX, "_standard/api", 2));
    return $enabled[0]?true:false;
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
    // TODO - check if token expired as well
    // TODO - modify modified column in db
    $check = queryDB("SELECT * FROM %sapi WHERE access_token = %s", array(TABLE_PREFIX, $token));
    if ($check[0]) {
        return true;
    } else {
        http_response_code(401);
        exit;
    }
}

function get_access_token($headers) {
    $token = allslashes($headers['x-AT-API-TOKEN']);
    return check_token($token)?$token:false;
}

?>