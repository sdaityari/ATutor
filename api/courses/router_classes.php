<?php

class CourseList {
    function get() {
        $clause = create_SQL_clause(array(
            "title" => "c.title",
            "category_id" => "c.cat_id",
            "primary_language" => "c.primary_language"
        ), $_GET);

        get_courses_main(TOKEN_ACCESS_LEVEL, $clause);
    }
}

class CourseDetails {
    function get($course_id) {
        get_courses_main(TOKEN_ACCESS_LEVEL, NULL, $course_id);
    }

    function delete($course_id) {
        $access_level = ADMIN_ACCESS_LEVEL;
        $token = get_access_token(getallheaders(), $access_level);

        $query = "DELETE FROM %scourses WHERE course_id = %d";
        $array = array(TABLE_PREFIX, $course_id);

        api_backbone(HTTP_DELETE, $token, $access_level, $query, $array);
    }
}

class CourseCategories {
    function get(){
        $access_level = TOKEN_ACCESS_LEVEL;
        $token = get_access_token(getallheaders(), $access_level);

        $query = "SELECT cat_id, cat_name, cat_parent, theme FROM %scourse_cats";
        $array = array(TABLE_PREFIX);

        api_backbone(HTTP_GET, $token, $access_level, $query, $array);
    }

    function post(){
        $access_level = INSTRUCTOR_ACCESS_LEVEL;
        $token = get_access_token(getallheaders(), $access_level);

        $name = $_POST["name"];
        $parent = $_POST["parent_id"] OR 0;
        $theme = $_POST["theme"];

        if (!$name) {
            print_message(ERROR, INSUFFICIENT_INFORMATION_TO_CREATE_OBJECT);
        }

        $query = "INSERT INTO %scourse_cats(cat_name, cat_parent, theme) VALUES('%s', %d, '%s')";
        $array = array(TABLE_PREFIX, $name, $parent, $theme);
        $callback_func = "mysql_insert_id";

        api_backbone(HTTP_POST, $token, $access_level, $query, $array, false, $callback_func);
    }
}

class CourseCategoryDetails {
    function get($category_id) {
        $access_level = TOKEN_ACCESS_LEVEL;
        $token = get_access_token(getallheaders(), $access_level);

        $query = "SELECT cat_id, cat_name, cat_parent, theme FROM %scourse_cats WHERE cat_id = %d";
        $array = array(TABLE_PREFIX, $category_id);

        api_backbone(HTTP_GET, $token, $access_level, $query, $array, true);
    }

    function put($category_id) {
        $access_level = TOKEN_ACCESS_LEVEL;
        $token = get_access_token(getallheaders(), $access_level);

        $sql = create_SQL_clause(array(
            "name" => "cat_name",
            "parent" => "cat_parent",
            "theme" => "theme"), $_REQUEST);

        $existing = queryDB("SELECT * FROM %scourse_cats WHERE cat_id = %d",
            array(TABLE_PREFIX, $category_id), true);

        if (!$existing) {
            http_response_code(404);
            print_message(ERROR, RESOURCE_DOES_NOT_EXIST);
        }

        $query = "UPDATE %scourse_cats SET ".$sql. "WHERE cat_id = %d";
        $array = array(TABLE_PREFIX, $category_id);

        api_backbone(HTTP_PUT, $token, $access_level, $query, $array);
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
            print_message(ERROR, RESOURCE_DOES_NOT_EXIST);
        } else {
            // Changing course category parents
            queryDB("UPDATE %scourse_cats SET cat_parent = 0 WHERE cat_parent = %d",
                array(TABLE_PREFIX, $id));

            // Updating courses with this course category
            queryDB("UPDATE %scourses SET cat_id = 0 WHERE cat_id = %d",
                array(TABLE_PREFIX, $id));
            print_message(SUCCESS, ACTION_COMPLETED_SUCCESSFULLY, $log);
        }
    }
}

?>