<?php

define("AT_INCLUDE_PATH", "../../../../include/");

include_once(AT_INCLUDE_PATH."vitals.inc.php");
include_once(AT_INCLUDE_PATH."lib/vital_funcs.inc.php");

$logs = queryDB("SELECT id, ip_address, request_uri, http_method, token, response, request_time ".
    "FROM %sapi_logs;", array(TABLE_PREFIX));

echo json_encode($logs);

?>