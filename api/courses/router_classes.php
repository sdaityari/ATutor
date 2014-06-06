<?php

class CourseList {
    function get() {
        $log = generate_basic_log($_SERVER);
        $token = get_access_token(getallheaders(), TOKEN_ACCESS_LEVEL);

        $log["token"] = $token;

        // TODO Raise 404 error
        $response = get_courses($_GET);
        $log["response"] = $response;
        log_request($log);
        echo $response;
    }
}

class CourseDetails {
    function get($course_id) {
        $log = generate_basic_log($_SERVER);
        $token = get_access_token(getallheaders(), TOKEN_ACCESS_LEVEL);
        $log["token"] = $token;

        $response = get_course_details($course_id);
        $log["response"] = $response;
        log_request($log);
        echo $response;
    }

    function delete($course_id) {
        $log = generate_basic_log($_SERVER);
        $token = get_access_token(getallheaders(), ADMIN_ACCESS_LEVEL);

        $log["token"] = $token;

        $query = queryDB("DELETE FROM %scourses WHERE course_id = %d",
            array(TABLE_PREFIX, $course_id));
        if ($query == 0) {
            http_response_code(404);
            print_message(ERROR, "COURSE_DOES_NOT_EXIST");
        } else {
            print_message(SUCCESS, "COURSE_DELETED_SUCCESSFULLY", $log);
        }
    }
}

class CourseCategories {
    function get(){
        $log = generate_basic_log($_SERVER);
        $token = get_access_token(getallheaders(), TOKEN_ACCESS_LEVEL);

        $log["token"] = $token;

        $response = json_encode(queryDB("SELECT cat_id, cat_name, cat_parent, theme FROM %scourse_cats",
            array(TABLE_PREFIX)));
        $log["response"] = $response;
        log_request($log);
        echo $response;
    }

    function post(){
        $log = generate_basic_log($_SERVER);
        $token = get_access_token(getallheaders(), TOKEN_ACCESS_LEVEL);

        $log["token"] = $token;
        $name = $_POST["name"];
        $parent = $_POST["parent_id"] OR 0;
        $theme = $_POST["theme"];

        if (!$name) {
            print_message(ERROR, "INSUFFICIENT_INFORMATION_TO_CREATE_OBJECT");
        }

        $id = queryDB("INSERT INTO %scourse_cats(cat_name, cat_parent, theme) VALUES('%s', %d, '%s')",
            array(TABLE_PREFIX, $name, $parent, $theme), false, true, $callback_func = "mysql_insert_id");

        return_created_id($id, $log);
    }
}

class CourseCategoryDetails {
    function get($category_id) {
        $log = generate_basic_log($_SERVER);
        $token = get_access_token(getallheaders(), TOKEN_ACCESS_LEVEL);

        $log["token"] = $token;

        $id = $category_id;
        $query = queryDB("SELECT cat_id, cat_name, cat_parent, theme FROM %scourse_cats WHERE cat_id = %d",
            array(TABLE_PREFIX, $id), true);
        if (count($query) == 0){
            http_response_code(404);
            exit;
        }

        $response = json_encode($query);
        $log["response"] = $response;
        log_request($log);
        echo $response;
    }

    function put($category_id) {
        /*
        $log = generate_basic_log($_SERVER);
        $token = get_access_token(getallheaders(), TOKEN_ACCESS_LEVEL);

        $log["token"] = $token;
        $id = ($category_id);
        $name = ($_POST["name"]);
        $parent = ($_POST["parent_id"]);
        $theme = ($_POST["theme"]);

        $id = queryDB("UPDATE %scourse_cats SET cat_name = '%s', cat_parent = %d, theme = '%s' WHERE )",
            array(TABLE_PREFIX, $name, $parent, $theme), false, true, $callback_func = "mysql_insert_id");

        return_created_id($id, $log);*/
        exit;
    }

    function delete($category_id) {
        $log = generate_basic_log($_SERVER);
        $token = get_access_token(getallheaders(), ADMIN_ACCESS_LEVEL);

        $log["token"] = $token;

        $id = $category_id;

        $query = queryDB("DELETE FROM %scourse_cats WHERE cat_id = %d",
            array(TABLE_PREFIX, $id));
        if ($query == 0) {
            http_response_code(404);
            print_message(ERROR, "COURSE_CATEGORY_DOES_NOT_EXIST");
        } else {
            // Changing course category parents
            queryDB("UPDATE %scourse_cats SET cat_parent = 0 WHERE cat_parent = %d",
                array(TABLE_PREFIX, $id));

            // Updating courses with this course category
            queryDB("UPDATE %scourses SET cat_id = 0 WHERE cat_id = %d",
                array(TABLE_PREFIX, $id));
            print_message(SUCCESS, "COURSE_CATEGORY_DELETED_SUCCESSFULLY", $log);
        }
    }
}

?>