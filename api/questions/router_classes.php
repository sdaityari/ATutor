<?php

class Questions {
    function get($question_id) {
        if ($question_id) {
            $sql_array = array(
                "q.question_id" => $question_id
            );
        } else {
            $sql_array = array(
                "q.question" => $_GET["question"],
                "q.category_id" => $_GET["category_id"],
                "q.type" => $_GET["type"]
            );
        }

        $clause  = create_SQL_clause($sql_array);

        $query = "SELECT  q.question_id, qc.title, q.course_id, q.type, q.feedback, q.question, ".
            "q.properties, q.content_id, q.remedial_content FROM %stests_questions q ".
            "INNER JOIN %stests_questions_categories qc ".
            "ON q.category_id = qc.category_id ". $clause;

        $array = array(TABLE_PREFIX, TABLE_PREFIX);

        api_backbone(array(
             "request_type" => HTTP_GET,
             "access_level" => ADMIN_ACCESS_LEVEL,
             "query" => $query,
             "query_array" => $array,
             "one_row" => $question_id ? true : false
        ));
    }
}

class QuestionCategories {
    function get($category_id){

        if ($category_id) {
            $sql_array = array(
                "category_id" => $category_id
            );
        } else {
            $sql_array = array(
                "course_id" => $_GET["course_id"],
                "title" => $_GET["title"]
            );
        }


        $clause = create_SQL_clause($sql_array);

        $query = "SELECT category_id, course_id, title FROM %stests_questions_categories ".$clause;
        $array = array(TABLE_PREFIX);

        api_backbone(array(
            "request_type" => HTTP_GET,
            "access_level" => TOKEN_ACCESS_LEVEL,
            "query" => $query,
            "query_array" => $array,
            "one_row" => $category_id ? true : false
        ));
    }

    function post(){
        $title = $_POST["title"];
        $course_id = $_POST["course_id"] || 0;

        if (!$title) {
            print_message(ERROR, INSUFFICIENT_INFORMATION_TO_CREATE_OBJECT);
        }

        $query = "INSERT INTO %stests_questions_categories(course_id, title) VALUES(%d, '%s')";
        $array = array(TABLE_PREFIX, $course_id, $title);

        api_backbone(array(
            "request_type" => HTTP_POST,
            "access_level" => INSTRUCTOR_ACCESS_LEVEL,
            "query" => $query,
            "query_array" => $array,
            "returned_id_name" => true
        ));
    }

    function put($category_id) {
        $query_id_existence = "SELECT COUNT(*) FROM %stests_questions_categories WHERE category_id = %d";
        $query_id_existence_array = array(TABLE_PREFIX, $category_id);

        $clause = create_SQL_clause(array(
            "title" => $_REQUEST["title"],
            "course_id" => $_REQUEST["course_id"]
        ), "SET");

        if ($clause) {
            $query = "UPDATE %stests_questions_categories ".$clause. "WHERE category_id = %d ";
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
        $query_id_existence = "SELECT COUNT(*) FROM %stests_questions_categories WHERE category_id = %d";
        $query_id_existence_array = array(TABLE_PREFIX, $category_id);

        $query = "DELETE FROM %stests_questions_categories WHERE category_id = %d";
        $array = array(TABLE_PREFIX, $category_id);

        api_backbone(array(
            "request_type" => HTTP_DELETE,
            "access_level" => ADMIN_ACCESS_LEVEL,
            "query" => $query,
            "query_array" => $array,
            "query_id_existence" => $query_id_existence,
            "query_id_existence_array" => $query_id_existence_array
        ));

        queryDB("UPDATE %stests_questions SET category_id = 0 WHERE category_id = %d",
                    array(TABLE_PREFIX, $category_id));
    }
}

?>
