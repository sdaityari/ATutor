<?php

class Tests {
    function get($test_id) {
        if ($test_id) {
            $sql_array = array(
                "test_id" => $test_id
            );
        } else {
            $sql_array = array(
                "title" => $_GET["title"],
                "start_date" => $_GET["start_date"],
                "else_date" => $_GET["end_date"]
            );
        }

        $clause  = create_SQL_clause($sql_array);

        $query = "SELECT test_id, course_id, title, format, start_date, end_date, num_questions ".
            "instructions, content_id, result_release, random, difficulty, description ".
            "FROM %stests ". $clause;

        $array = array(TABLE_PREFIX);

        api_backbone(array(
             "request_type" => HTTP_GET,
             "access_level" => ADMIN_ACCESS_LEVEL,
             "query" => $query,
             "query_array" => $array,
             "one_row" => $test_id ? true : false
        ));
    }
}

class TestQuestions {
    function get($test_id, $question_id) {
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

        $clause  = create_SQL_clause($sql_array, "AND");

        $query = "SELECT  q.question_id, qc.title, q.course_id, q.type, q.feedback, q.question, ".
            "q.properties, q.content_id, q.remedial_content FROM %stests_questions q ".
            "INNER JOIN %stests_questions_categories qc ".
            "ON q.category_id = qc.category_id WHERE q.question_id IN ".
            "(SELECT question_id FROM %stests_questions_assoc WHERE test_id = %d) ". $clause;

        $array = array(TABLE_PREFIX, TABLE_PREFIX, TABLE_PREFIX, $test_id);

        api_backbone(array(
             "request_type" => HTTP_GET,
             "access_level" => ADMIN_ACCESS_LEVEL,
             "query" => $query,
             "query_array" => $array,
             "one_row" => $question_id ? true : false
        ));
    }
}

?>
