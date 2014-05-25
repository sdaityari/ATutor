<?php

if (!defined('AT_INCLUDE_PATH')) {
    exit;
}

function api_module_status(){
    $enabled = queryDB("SELECT * FROM %smodules WHERE dir_name = '%s' and status = %d",
        array(TABLE_PREFIX, "_standard/api", 2));
    return $enabled;
}

function generate_urls($old_array, $prefix) {
    $new_array = array();
    foreach($old_array as $key => $value) {
        $new_array[$prefix.$key] = $value;
    }
    return $new_array;
}

function check_token_exists($token){
    $check = queryDB("SELECT * FROM %sapi WHERE access_token = %s", array(TABLE_PREFIX, $token));
    if ($check[0]) {
        return true;
    } else {
        http_response_code(401);
        exit;
    }
}

function get_access_token($headers) {
    $token = AT_print($headers['x-AT-API-TOKEN']);
    if (check_token_exists($token)){
        return $token;
    }
}

?>