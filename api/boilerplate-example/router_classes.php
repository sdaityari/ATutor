<?php

class BoilerplateClass {
    function get() {
        // Make a call to the API function like this
        // Specify a request type, access level and query to be executed.

        // Uncomment the following for a sample call (make sure you set the variables first)

        /* api_backbone(array(
         *    "request_type" => HTTP_GET,
         *    "access_level" => INSTRUCTOR_ACCESS_LEVEL,
         *    "query" => $query,
         *    "query_array" => $array
         * ));
         */
        print "You are at the boilerplate home!";
    }

    function post() {
        // Make a call to the API function like this
        // Specify a request type, access level and query to be executed.
        // We create an object and return the ID with this call.
        // Uncomment the following for a sample call (make sure you set the variables first)
        /* api_backbone(array(
         *    "request_type" => HTTP_POST,
         *    "access_level" => INSTRUCTOR_ACCESS_LEVEL,
         *    "query" => $query,
         *    "query_array" => $array
         * ));
         */
        print "You are at the boilerplate home! Creating an object.";
    }
}

class BoilerplateClassWithUrlParameter{
    function get($boilerplate_id) {
        // Make a call to the API function like this
        // Since we want a single object, we specify one_row to be true
        /* api_backbone(array(
         *    "request_type" => HTTP_GET,
         *    "access_level" => ADMIN_ACCESS_LEVEL,
         *    "query" => $query,
         *    "query_array" => $array,
         *    "one_row" => true
         * ));
         */
        print "Checking boilerplace with id - ".$boilerplate_id;
    }

    function put($boilerplate_id) {
        // Make a call to the API function like this
        // We update an object here, so we need to check if it exists first, which is accomplished by query_id_existence
        // No need to specify one_row because we perform an update operation here
        // Uncomment the following for a sample call (make sure you set the variables first)
        /* api_backbone(array(
         *    "request_type" => HTTP_PUT,
         *    "access_level" => ADMIN_ACCESS_LEVEL,
         *    "query_id_existence" => $query_id_existence,
         *    "query_id_existence_array" => $query_id_existence_array,
         *    "query" => $query,
         *    "query_array" => $array
         * ));
         */
        print "Updating boilerplace object with id - ".$boilerplate_id;
    }

    function delete($boilerplate_id) {
        // Make a call to the API function like this
        // We delete an object here, so we need to check if it exists first, which is accomplished by query_id_existence
        // Uncomment the following for a sample call (make sure you set the variables first)
        /* api_backbone(array(
         *    "request_type" => HTTP_DELETE,
         *    "access_level" => ADMIN_ACCESS_LEVEL,
         *    "query_id_existence" => $query_id_existence,
         *    "query_id_existence_array" => $query_id_existence_array,
         *    "query" => $query,
         *    "query_array" => $array
         * ));
         */
        print "Deleting boilerplace with id - ".$boilerplate_id;
    }
}

?>
