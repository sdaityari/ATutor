<?php

define("AT_INCLUDE_PATH", "../../../../include/");

include_once(AT_INCLUDE_PATH. "vitals.inc.php");
include_once(AT_INCLUDE_PATH. "lib/vital_funcs.inc.php");

$query = $_GET["status"] == "inactive" ? "DELETE FROM %sapi WHERE expiry < NOW()" : "DELETE FROM %sapi_logs";

queryDB($query,
    array(TABLE_PREFIX));

print AT_print("ACTION_COMPLETED_SUCCESSFULLY");

?>
