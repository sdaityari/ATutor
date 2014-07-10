<?php

class Courses {
    function get($course_id) {

        $clause = create_SQL_clause(array(
            "c.title" => $_GET["title"],
            "c.cat_id" => $_GET["category_id"],
            "c.primary_language" => $_GET["primary_language"]
        ));

        $clause_with_id = create_SQL_clause(array(
            "c.course_id" => $course_id
        ));

        $query = "SELECT c.course_id, c.cat_id, cc.cat_name, c.created_date, ".
            "c.title, c.description, c.notify, c.copyright, c.icon, c.release_date, c.primary_language, ".
            "c.end_date, c.banner FROM %scourses c ".
            "INNER JOIN %scourse_cats cc ON c.cat_id = cc.cat_id ";

        $array = array(TABLE_PREFIX, TABLE_PREFIX);

        $query .= $course_id ? $clause_with_id : $clause;
        $one_row = $course_id ? true : false;

        api_backbone(array(
            "request_type" => HTTP_GET,
            "access_level" => TOKEN_ACCESS_LEVEL,
            "query" => $query,
            "query_array" => $array,
            "one_row" => $one_row
        ));
    }

    function delete($course_id) {
        $query = "DELETE FROM %scourses WHERE course_id = %d";
        $array = array(TABLE_PREFIX, $course_id);

        api_backbone(array(
            "request_type" => HTTP_DELETE,
            "access_level" => ADMIN_ACCESS_LEVEL,
            "query" => $query,
            "query_array" => $array
        ));
    }
}

class CourseCategories {
    function get($category_id){
        $clause = create_SQL_clause(array(
            "cat_name" => $_REQUEST["name"],
            "cat_parent" => $_REQUEST["parent"],
            "theme" => $_REQUEST["theme"]
        ));

        $clause_with_id = create_SQL_clause(array(
            "cat_id" => $category_id
        ));

        $query = "SELECT cat_id, cat_name, cat_parent, theme FROM %scourse_cats ";
        $array = array(TABLE_PREFIX);

        $query .= $category_id ? $clause_with_id : $clause;
        $one_row = $category_id ? true : false;

        api_backbone(array(
            "request_type" => HTTP_GET,
            "access_level" => TOKEN_ACCESS_LEVEL,
            "query" => $query,
            "query_array" => $array,
            "one_row" => $one_row
        ));
    }

    function post(){
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
            "access_level" => INSTRUCTOR_ACCESS_LEVEL,
            "query" => $query,
            "query_array" => $array,
            "returned_id_name" => true
        ));
    }

    function put($category_id) {

        $query_id_existence = "SELECT COUNT(*) FROM %scourse_cats WHERE cat_id = %d";
        $query_id_existence_array = array(TABLE_PREFIX, $category_id);

        $clause = create_SQL_clause(array(
            "cat_name" => $_REQUEST["name"],
            "cat_parent" => $_REQUEST["parent"],
            "theme" => $_REQUEST["theme"]
        ), "SET");

        if (!$clause) {
            $query = "UPDATE %scourse_cats ".$clause. "WHERE cat_id = %d ";
            $array = array(TABLE_PREFIX, $category_id);
        } else {
            $query = "";
            $array = array();
        }

        api_backbone(array(
            "request_type" => HTTP_PUT,
            "access_level" => INSTRUCTOR_ACCESS_LEVEL,
            "query" => $query,
            "query_array" => $array,
            "query_id_existence" => $query_id_existence,
            "query_id_existence_array" => $query_id_existence_array
        ));
    }

    function delete($category_id) {
        $query_id_existence = "SELECT COUNT(*) FROM %scourse_cats WHERE cat_id = %d";
        $query_id_existence_array = array(TABLE_PREFIX, $category_id);

        $query = "DELETE FROM %scourse_cats WHERE cat_id = %d";
        $array = array(TABLE_PREFIX, $category_id);

        api_backbone(array(
            "request_type" => HTTP_DELETE,
            "access_level" => ADMIN_ACCESS_LEVEL,
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
