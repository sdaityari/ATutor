<?php

define("AT_INCLUDE_PATH", "../../../include/");

include_once(AT_INCLUDE_PATH."vitals.inc.php");
include_once(AT_INCLUDE_PATH."lib/vital_funcs.inc.php");

if ($_POST) {
    $api_token_expiry = $_POST["token-expiry"];
    $api_logging_level = $_POST["logging-level"];

    if (in_array($api_logging_level, array("1", "2", "3"))) {
        // Checking if logging_level is in the required range
        $_config['api_logging_level'] = $api_logging_level;
        queryDB("UPDATE %sconfig SET value = %d WHERE name = 'api_logging_level'",
            array(TABLE_PREFIX, $api_logging_level));
    }
    if (intval($api_token_expiry)) {
        $_config['api_token_expiry'] = $api_token_expiry;
        queryDB("UPDATE %sconfig SET value = %d WHERE name = 'api_token_expiry'",
            array(TABLE_PREFIX, $api_token_expiry));
    }
}

?>

<div>
    <a href="custom/download_log.php">Download API Log</a>
</div>

<div>
    <a href="custom/clear_log.php">Clear API logs</a>
</div>

<div>
    <form method="post">
        Token Expiry (Days): <input type="text" name="token-expiry" value="<?php echo $_config['api_token_expiry']; ?>" /><br />
        Logging Level: <select name="logging-level">
            <option value="1" <?php
                if ($_config['api_logging_level'] == 1) {
                    echo "selected = 'true'";
                }
            ?> >Log all requests</option>
            <option value="2" <?php
                if ($_config['api_logging_level'] == 2) {
                    echo "selected = 'true'";
                }
            ?>>Log all requests except GET</option>
            <option value="3" <?php
                if ($_config['api_logging_level'] == 3) {
                    echo "selected = 'true'";
                }
            ?> >No Logging</option>
        </select><br />
        <input type="submit" value="Submit">
    </form>
</div>
