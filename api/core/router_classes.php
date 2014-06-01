<?php

class Authentication {
    function post() {
        $log = generate_basic_log($_SERVER);
        $username = addslashes($_POST["username"]);
        $password = addslashes($_POST["password"]);

        $row = queryDB("SELECT member_id, status FROM %smembers WHERE (login = '%s' OR email = '%s') AND password = Sha1('%s')",
            array(TABLE_PREFIX, $username, $username, $password), true);
        $row_admin = queryDB("SELECT login, privileges FROM %sadmins WHERE (login = '%s' OR email = '%s') AND password = Sha1('%s')",
            array(TABLE_PREFIX, $username, $username, $password), true);

        if (!$row and !$row_admin) {
            print_error("WRONG_CREDENTIALS");
        } else if ($row['status'] == AT_STATUS_UNCONFIRMED and !$row_admin) {
            print_error("NOT_CONFIRMED");
        } else if ($row['status'] == AT_STATUS_DISABLED and !$row_admin) {
            print_error("ACCOUNT_DISABLED");
        } else {
            // Generating API token
            $now = time();
            $member_id = $row_admin ? $row_admin['login'] : $row['member_id'];
            $token = md5( $member_id . $now . rand() );

            if ($row_admin) {
                $access_level = ADMIN_ACCESS_LEVEL;
            } else if ($row['status'] == 3) {
                $access_level = INSTRUCTOR_ACCESS_LEVEL;
            } else if ($row['status'] == 2) {
                $access_level = STUDENT_ACCESS_LEVEL;
            } else {
                $access_level = TOKEN_ACCESS_LEVEL;
            }

            // Deleting old data if exists
            queryDB("DELETE FROM %sapi WHERE member_id = %d", array(TABLE_PREFIX, $member_id));

            queryDB("INSERT INTO %sapi(member_id, access_level, token, modified, expiry) VALUES('%s', %d, '%s', CURRENT_TIMESTAMP, NOW() + INTERVAL 1 DAY)",
                array(TABLE_PREFIX, $member_id, $access_level, $token));

            // Returning the access token
            $response = json_encode(array(
                "access_token" => $token
            ));
            $log["response"] = $response;
            log_request($log);
            echo $response;
        }

    }

    function get() {
        $log = generate_basic_log($_SERVER);
        $token = get_access_token(getallheaders());

        $log["token"] = $token;

        queryDB("DELETE FROM %sapi WHERE token = '%s'", array(TABLE_PREFIX, $token));

        $response = json_encode(array(
            "successMessage" => "LOGGED_OUT_SUCCESSFULLY"
        ));
        $log["response"] = $response;
        log_request($log);
        echo $response;
    }
}

?>