<?php

function api_backbone($options) {
    /*
     * Function to perform all API calls
     * Every call has a token, checks access level and performs a query
     * This function takes those as argument and logs the request
     */

    if (DEBUG) {
        print vsprintf($options["query"], $options["query_array"]);
        print "\n\n";
    }

    $defaults = array(
        "request_type" => HTTP_GET,
        "access_level" => ADMIN_ACCESS_LEVEL,
        "member_id" => -1
    );

    $options = array_merge($defaults, $options);

    list($options["token"], $token_member_id) = get_access_token(getallheaders(), $options["access_level"], true);

    if ($options["member_id"] != -1 && // Checking if member id was passed
            $token_member_id != $options["member_id"] && // Checking if token belongs to member requested
            !check_access_level($options["token"])) { // Checking if request comes from admin
        http_response_code(404);
        exit;
    }

    $options["log"] = generate_basic_log($_SERVER);
    $options["log"]["token"] = $options["token"];

    // Checking if object with ID exists
    if ($options["query_id_existence"]) {
        $existence = queryDB($options["query_id_existence"], $options["query_id_existence_array"], true);
        if (!$existence["COUNT(*)"] || !$existence) {
            http_response_code(404);
            print_message(ERROR, RESOURCE_DOES_NOT_EXIST);
            exit;
        }
    }

    $callback_func = $options["returned_id_name"] ? "mysql_insert_id" : "mysql_affected_rows";

    $result = queryDB($options["query"], $options["query_array"], $options["one_row"], true, $callback_func);

    if ($options["one_row"] && $options["request_type"] == HTTP_GET && count($result) == 0){
        http_response_code(404);
        exit;
    }

    $response = json_encode($result);
    $options["log"]["response"] = $response;
    log_request($options["log"], $options["request_type"]);

    switch ($options["request_type"]) {
        case HTTP_GET:
            echo $response;
            break;

        case HTTP_POST:
            return_created_id($result, $options["log"]);
            return $result;
            break;

        case HTTP_PUT:
            print_message(SUCCESS, ACTION_COMPLETED_SUCCESSFULLY, $options["log"], $options["request_type"]);
            break;

        case HTTP_DELETE:
            if (!$result) {
                http_response_code(404);
                print_message(ERROR, RESOURCE_DOES_NOT_EXIST, $options["log"], $options["request_type"]);
            } else {
                print_message(SUCCESS, ACTION_COMPLETED_SUCCESSFULLY, $options["log"], $options["request_type"]);
            }
            break;

        default:
            break;
    }

}

function set_multiple_options(){
    // Yet to decide on the functionality
}

?>
