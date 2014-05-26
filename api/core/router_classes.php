<?php

class Authentication {
    function post() {
        $username = addslashes($_POST["username"]);
        $password = addslashes($_POST["password"]);

        $row = queryDB("SELECT member_id, status FROM %smembers WHERE (login = '%s' OR email = '%s') AND password = Sha1('%s')",
            array(TABLE_PREFIX, $username, $username, $password), true);
        $row_admin = queryDB("SELECT member_id, status FROM %sadmins WHERE (login = '%s' OR email = '%s') AND password = Sha1('%s')",
            array(TABLE_PREFIX, $username, $username, $password), true);

        if (!$row and !$row_admin) {
            print_error("WRONG_CREDENTIALS");
        } else if ($row['status'] == AT_STATUS_UNCONFIRMED) {
            print_error("NOT_CONFIRMED");
        } else if ($row['status'] == AT_STATUS_DISABLED) {
            print_error("ACCOUNT_DISABLED");
        } else {
            // Generating API token
            $now = time();
            $token = md5( $row["member_id"] . $now . rand() );

            $member_id = $row?$row['member_id']:$row_admin['login'];

            // TODO - Define access level with member_id
            $access_level = TOKEN_ACCESS_LEVEL;

            // Deleting old data if exists
            queryDB("DELETE FROM %sapi WHERE member_id = %d", array(TABLE_PREFIX, $member_id));

            queryDB("INSERT INTO %sapi(member_id, access_level, token, modified, expiry) VALUES(%d, %d, '%s', CURRENT_TIMESTAMP, NOW() + INTERVAL 1 DAY)",
                array(TABLE_PREFIX, $member_id, $access_level, $token));

            // Returning the access token
            echo json_encode(array(
                "access_token" => $token
            ));
        }

    }

    function get() {
        $token = get_access_token(getallheaders());

        queryDB("DELETE FROM %sapi WHERE token = '%s'", array(TABLE_PREFIX, $token));

        print json_encode(array(
            "successMessage" => "LOGGED_OUT_SUCCESSFULLY"
        ));
    }
}

?>