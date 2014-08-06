<?php

if (!defined('AT_INCLUDE_PATH')) {
    exit;
}

/*
 * Support for PHP < 5.4
 * More info - http://stackoverflow.com/questions/3258634/php-how-to-send-http-response-code
 */

if (!function_exists('http_response_code'))
{
    function http_response_code($newcode = NULL)
    {
        static $code = 200;
        if($newcode != NULL)
        {
            header('X-PHP-Response-Code: '.$newcode, true, $newcode);
            if(!headers_sent())
                $code = $newcode;
        }
        return $code;
    }
}

function api_module_status() {
    // To check if the module is activated/activated
    $enabled = queryDB("SELECT
                            *
                        FROM
                            %smodules
                        WHERE
                            dir_name = '%s'
                        AND
                            status = %d",
                array(TABLE_PREFIX,
                        "_standard/api",
                        2));

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

function check_token($token, $minimum_access_level){
    $check = queryDB("SELECT
                          access_level
                        , member_id
                    FROM
                        %sapi
                    WHERE
                        token = '%s'
                    AND
                        expiry > CURRENT_TIMESTAMP",
        array(TABLE_PREFIX,
            $token), true);

    if (!$check) {
        http_response_code(401);
        print_message(ERROR, TOKEN_DOES_NOT_EXIST);
        exit;
    } else if ($check["access_level"] > $minimum_access_level) {
        http_response_code(401);
        print_message(ERROR, YOU_ARE_NOT_AUTHORIZED_TO_ACCESS_THIS_RESOURCE);
        exit;
    }

    $query = "UPDATE
                %sapi
              SET
                  modified = CURRENT_TIMESTAMP
                , expiry = NOW() + INTERVAL %d DAY
              WHERE
                token = '%s'";

    $query_array = array(TABLE_PREFIX,
                            TOKEN_EXPIRY,
                            $token);

    if (DEBUG) {
        print vsprintf($query, $query_array);
        print "\n\n";
    }

    // Update modified timestamp
    queryDB($query, $query_array);

    return $check["member_id"];
}

function check_access_level($token, $access_level = ADMIN_ACCESS_LEVEL) {
    $check = queryDB("SELECT
                        COUNT(*)
                      FROM
                        %sapi
                      WHERE
                        token = '%s'
                      AND
                        access_level <= %d",
        array(TABLE_PREFIX,
                $token,
                $access_level), true);

    return $check > 0 ? true : false;
}

function get_access_token($headers, $minimum_access_level = ADMIN_ACCESS_LEVEL,
    $return_member_id = false) {

    /**
     * $headers - assoc array of headers
     * $minimum_access_level - the user with the lowest permissions that can access this
     * $return_member_id - whether to return a tuple or token and member_id
     */

    $token = addslashes($headers[TOKEN_NAME]);
    $member_id = check_token($token, $minimum_access_level);

    if ($member_id && $return_member_id){
        return array($token, $member_id);
    } else if ($member_id) {
        return $token;
    } else {
        return false;
    }
}

function print_message($type, $message, $log = array(), $http_method = HTTP_GET) {
    if (!$log) {
        $log = generate_basic_log($_SERVER);
        $headers = getallheaders();
        $log["token"] = $headers[TOKEN_NAME];
    }
    $key = $type == ERROR ? "errorMessage" : "successMessage";
    $response = json_encode(array(
        $key => $message
    ));
    $log["response"] = $response;
    log_request($log, $http_method, $type == ERROR);
    echo $response;
    exit;
}

function generate_basic_log($request) {
    $log = array();
    $log["ip_address"] = $request["REMOTE_ADDR"];
    $log["request_uri"] = $request["REQUEST_URI"];
    $log["http_method"] = $request["REQUEST_METHOD"];
    $log["user_agent"] = $request["HTTP_USER_AGENT"];
    return $log;
}

function log_request($log = array(), $http_method = HTTP_GET, $error = false) {
    if ((LOGGING_LEVEL == NO_LOGGING) ||
        ($http_method == HTTP_GET && LOGGING_LEVEL == LOGGING_EXCEPT_GET && !$error)) {
        return;
    }

    $query = "INSERT INTO %sapi_logs(
                  ip_address
                , user_agent
                , request_uri
                , http_method
                , token
                , response)
              VALUES(
                  '%s'
                , '%s'
                , '%s'
                , '%s'
                , '%s'
                , '%s')";

    $query_array = array(TABLE_PREFIX,
        $log["ip_address"],
        $log["user_agent"],
        $log["request_uri"],
        $log["http_method"],
        $log["token"],
        $log["response"]
    );

    if (DEBUG) {
        print vsprintf($query, $query_array);
        print "\n\n";
    }

    queryDB($query, $query_array);


}

function return_created_id($id, $log) {
    $response = json_encode(array(
        "successMessage" => ACTION_COMPLETED_SUCCESSFULLY,
        "id" => $id
    ));
    echo $response;
}

function create_SQL_clause($terms, $prefix = "WHERE", $sanitize = true) {
    /*
     * Function to create SQL clause
     * $terms is an associative array
     * The keys of $terms represent the column names as they appear in the SQL
     * For example, create_SQL_clause(array(
     *                  "title" => "My Course",
     *                  "language" => "en")) should return
     * "WHERE c.title = 'My Course' AND c.language = 'en'"
     */
    $query = "";
    $prefix = $prefix ? $prefix . " " : "";
    foreach ($terms as $key => $value) {
        if ($value) {
            if ($query != "")
                $query = $query."AND ";
            $query = $sanitize ? $query.$key." = '". addslashes($value) ."' " : $query.$key." = '". $value ."' ";
        }
    }
    if ($query != ""){
        $query = $prefix . $query;
    }
    return $query;
}

?>
