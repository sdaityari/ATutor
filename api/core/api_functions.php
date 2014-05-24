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

function get_access_token($headers) {
    return AT_print($headers['x-AT-API-TOKEN']);
}

?>