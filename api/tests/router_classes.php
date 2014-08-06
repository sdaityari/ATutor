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

        $query = "SELECT
                      test_id
                    , course_id
                    , title
                    , format
                    , start_date
                    , end_date
                    , num_questions
                    , instructions
                    , content_id
                    , result_release
                    , random
                    , difficulty
                    , description
                  FROM
                    %stests ". $clause;

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

        $query = "SELECT
                      q.question_id
                    , qc.title
                    , q.course_id
                    , q.type
                    , q.feedback
                    , q.question
                    , q.properties
                    , q.content_id
                    , q.remedial_content
                  FROM
                    %stests_questions AS q
                  INNER JOIN
                    %stests_questions_categories AS qc
                        ON
                            q.category_id = qc.category_id
                  WHERE
                    q.question_id
                        IN
                            (SELECT
                                question_id
                             FROM
                                %stests_questions_assoc
                             WHERE
                                test_id = %d
                            ) ". $clause;

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

class TestQuestionsAssociation {
    function get($test_id, $question_id, $action) {
        $query_id_existence =   "SELECT
                                    COUNT(*)
                                FROM
                                    %stests_questions
                                WHERE
                                    question_id = %d
                                ";

        $query_id_existence_array = array(TABLE_PREFIX, $question_id);

        if ($action == "remove") {
            $query = "DELETE FROM
                        %stests_questions_assoc
                      WHERE
                        question_id = %d
                            AND
                        test_id = %d";
            $query_array = array(TABLE_PREFIX, $question_id, $test_id);
        } else if ($action == "add") {
            $weight = $_GET["weight"];
            $ordering = $_GET["ordering"];
            $required = $_GET["required"];

            $query = "INSERT INTO
                        %stests_questions_assoc(
                              test_id
                            , question_id
                            , weight
                            , ordering
                            , required
                        ) VALUES (
                              %d
                            , %d
                            , '%s'
                            , %d
                            , %d
                        )
                     ";
            $query_array = array(TABLE_PREFIX, $test_id, $question_id, $weight, $ordering,
                $required);
        } else {
            http_response_code(404);
            exit;
        }

        api_backbone(array(
             "request_type" => HTTP_GET,
             "access_level" => INSTRUCTOR_ACCESS_LEVEL,
             "query" => $query,
             "query_array" => $array,
             "query_id_existence" => $query_id_existence,
             "query_id_existence_array" => $query_id_existence_array
        ));

    }
}

?>
