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
             "access_level" => STUDENT_ACCESS_LEVEL,
             "query" => $query,
             "query_array" => $array,
             "one_row" => $test_id ? true : false
        ));
    }
}

?>
