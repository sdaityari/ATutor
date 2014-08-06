<?php

class BoilerplateClass {
    function get() {
        // Make a call to the API function like this
        // Specify a request type, access level and query to be executed.

        // You may want to add certain WHERE parameters to your query (which may or may not be present)
        // An associative array is an argument - the keys are the names of the db columns as they appear in the query
        //                                     - the values are the variables that may or may not exist in the call
        // Uncomment the following call to generate the clause according to the variables that are present

        // create_SQL_clause(array(
        //     "title" => $_GET["title"],
        //     "language" => $_GET["language"]
        // ));

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
        // To get the ID of the newly created object, set returned_id_name to true

        // Uncomment the following for a sample call (make sure you set the variables first)
        /* api_backbone(array(
         *    "request_type" => HTTP_POST,
         *    "access_level" => INSTRUCTOR_ACCESS_LEVEL,
         *    "query" => $query,
         *    "query_array" => $array,
         *    "returned_id_name" => true
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

        // There are chances where you might want to execute something after the API call
        // For example, after you delete a course, you want to clear the enrollment tables
        // For this you need to manually use queryDB after you complete the call to api_backbone

        // queryDB($second_query, $second_query_array);

        print "Deleting boilerplace with id - ".$boilerplate_id;
    }
}

?>
