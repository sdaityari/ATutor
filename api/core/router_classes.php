<?php

class Authentication {
    function post() {
        $username = addslashes($_POST["username"]);
        $password = addslashes($_POST["password"]);

        $data = queryDB("SELECT member_id, status FROM %smembers WHERE (login = '%s' OR email = '%s') AND password = Sha1('%s')",
            array(TABLE_PREFIX, $username, $username, $password));

        $row = $data[0];

        if (!$row) {
            // TODO - Make a messaging system
            echo "WRONG_CREDENTIALS";
        } else if ($row['status'] == AT_STATUS_UNCONFIRMED) {
            echo "NOT_CONFIRMED";
        } else if ($row['status'] == AT_STATUS_DISABLED) {
            echo "ACCOUNT_DISABLED";
        } else {
            // Generating API token
            $now = time();
            $token = md5( $row["member_id"] . $now . rand() );

            // TODO - Define access level with member_id
            $access_level = TOKEN_ACCESS_LEVEL;

            // Deleting old data if exists
            queryDB("DELETE FROM %sapi WHERE member_id = %d", array(TABLE_PREFIX, $row['member_id']));

            queryDB("INSERT INTO %sapi(member_id, access_level, token, modified, expiry) VALUES(%d, %d, '%s', CURRENT_TIMESTAMP, NOW() + INTERVAL 1 DAY)",
                array(TABLE_PREFIX, $row["member_id"], $access_level, $token));

            // Returning the access token
            echo json_encode(array(
                "access_token" => $token
            ));
        }

    }

    function get() {
        $token = get_access_token(getallheaders());

        queryDB("DELETE FROM %sapi WHERE access_token = %s", array(TABLE_PREFIX, $token));

        echo "LOGGED_OUT_SUCCESSFULLY";
    }
}

?>