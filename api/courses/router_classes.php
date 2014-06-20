<?php

class CourseList {
    function get() {
        $clause = create_SQL_clause(array(
            "c.title" => $_GET["title"],
            "c.cat_id" => $_GET["category_id"],
            "c.primary_language" => $_GET["primary_language"]
        ));

        get_courses_main(TOKEN_ACCESS_LEVEL, $clause);
    }

}

class CourseDetails {
    function get($course_id) {
        get_courses_main(TOKEN_ACCESS_LEVEL, NULL, $course_id);
    }

    function delete($course_id) {
        $access_level = ADMIN_ACCESS_LEVEL;

        $query = "DELETE FROM %scourses WHERE course_id = %d";
        $array = array(TABLE_PREFIX, $course_id);

        api_backbone(array(
            "request_type" => HTTP_DELETE, 
            "access_level" => $access_level,
            "query" => $query,
            "query_array" => $array
        ));
    }
}

class CourseCategories {
    function get(){
        $access_level = TOKEN_ACCESS_LEVEL;

        $query = "SELECT cat_id, cat_name, cat_parent, theme FROM %scourse_cats";
        $array = array(TABLE_PREFIX);

        api_backbone(array(
            "request_type" => HTTP_GET, 
            "access_level" => $access_level,
            "query" => $query,
            "query_array" => $array
        ));
    }

    function post(){
        $access_level = INSTRUCTOR_ACCESS_LEVEL;

        $name = $_POST["name"];
        $parent = $_POST["parent_id"] OR 0;
        $theme = $_POST["theme"];

        if (!$name) {
            print_message(ERROR, INSUFFICIENT_INFORMATION_TO_CREATE_OBJECT);
        }

        $query = "INSERT INTO %scourse_cats(cat_name, cat_parent, theme) VALUES('%s', %d, '%s')";
        $array = array(TABLE_PREFIX, $name, $parent, $theme);

        api_backbone(array(
            "request_type" => HTTP_POST, 
            "access_level" => $access_level,
            "query" => $query,
            "query_array" => $array,
            "returned_id_name" => true
        ));
    }
}

class CourseCategoryDetails {
    function get($category_id) {
        $access_level = TOKEN_ACCESS_LEVEL;

        $query = "SELECT cat_id, cat_name, cat_parent, theme FROM %scourse_cats WHERE cat_id = %d";
        $array = array(TABLE_PREFIX, $category_id);

        api_backbone(array(
            "request_type" => HTTP_GET,
            "access_level" => $access_level,
            "query" => $query,
            "query_array" => $array,
            "one_row" => true
        ));
    }

    function put($category_id) {
        $access_level = TOKEN_ACCESS_LEVEL;
        $token = get_access_token(getallheaders(), $access_level);

        $query_id_existence = "SELECT COUNT(*) FROM %scourse_cats WHERE cat_id = %d";
        $query_id_existence_array = array(TABLE_PREFIX, $category_id);

        $clause = create_SQL_clause(array(
            "cat_name" => $_REQUEST["name"],
            "cat_parent" => $_REQUEST["parent"],
            "theme" => $_REQUEST["theme"]
        ));

        $query = "UPDATE %scourse_cats SET ".$clause. "WHERE cat_id = %d";
        $array = array(TABLE_PREFIX, $category_id);

        api_backbone(array(
            "request_type" => HTTP_PUT,
            "access_level" => $access_level,
            "query" => $query,
            "query_array" => $array,
            "query_id_existence" => $query_id_existence,
            "query_id_existence_array" => $query_id_existence_array
        ));
    }

    function delete($category_id) {
        $access_level = ADMIN_ACCESS_LEVEL;

        $query_id_existence = "SELECT COUNT(*) FROM %scourse_cats WHERE cat_id = %d";
        $query_id_existence_array = array(TABLE_PREFIX, $category_id);

        $query = "DELETE FROM %scourse_cats WHERE cat_id = %d";
        $array = array(TABLE_PREFIX, $category_id);

        api_backbone(array(
            "request_type" => HTTP_DELETE,
            "access_level" => $access_level,
            "query" => $query,
            "query_array" => $array,
            "query_id_existence" => $query_id_existence,
            "query_id_existence_array" => $query_id_existence_array,
            "queries_after" => $queries_after
        ));

        // Changing course category parents and updating courses with this course category
        queryDB("UPDATE %scourse_cats SET cat_parent = 0 WHERE cat_parent = %d",
                    array(TABLE_PREFIX, $category_id));
        queryDB("UPDATE %scourses SET cat_id = 0 WHERE cat_id = %d",
                    array(TABLE_PREFIX, $category_id));
    }
}

?>