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

?>
