<?php

class Instructors {
    function get($instructor_id) {

        if ($instructor_id) {
            $sql_array = array(
                "member_id" => $instructor_id
            );
        } else {
            $sql_array = array(
                "email" => $_GET["email"],
                "first_name" => $_GET["first_name"],
                "last_name" => $_GET["last_name"],
                "login" => $_GET["login"]
            );
        }

        $clause = create_SQL_clause($sql_array, "AND");

        $query = "SELECT
                      member_id
                    , login
                    , email
                    , first_name
                    , last_name
                    , website
                    , gender
                    , address
                    , postal
                    , city
                    , province
                    , country
                    , phone
                    , language
                    , last_login
                    , creation_date
                FROM
                    %smembers
                WHERE
                    status = %d ".$clause;

        $array = array(TABLE_PREFIX, INSTRUCTOR_ROLE);

        api_backbone(array(
            "request_type" => HTTP_GET,
            "access_level" => INSTRUCTOR_ACCESS_LEVEL,
            "query" => $query,
            "query_array" => $array,
            "one_row" => $instructor_id ? true : false
        ));
    }

    function post() {
        $login = $_POST["login"];
        $password = $_POST["password"];
        $email = $_POST["email"];
        $website = $_POST["website"];
        $first_name = $_POST["first_name"];
        $second_name = $_POST["second_name"];
        $last_name = $_POST["last_name"];
        $gender = $_POST["gender"];
        $dob = $_POST["dob"];
        $address = $_POST["address"];
        $postal = $_POST["postal"];
        $city = $_POST["city"];
        $province = $_POST["province"];
        $country = $_POST["country"];
        $phone = $_POST["phone"];
        $language = $_POST["language"];

        if (!($login && $password && $email && $first_name && $last_name)) {
            print_message(ERROR, INSUFFICIENT_INFORMATION_TO_CREATE_OBJECT);
        }

        $checks =   queryDB("SELECT
                                COUNT(*)
                            FROM
                                %smembers
                            WHERE
                                login = '%s'
                                    OR
                                email = '%s'",
            array(TABLE_PREFIX, $login, $email), true);

        if ($checks["COUNT(*)"] != "0"){
            print_message(ERROR, EMAIL_OR_USERNAME_EXISTS);
        };

        $query =    "INSERT INTO
                        %smembers(
                              login
                            , password
                            , email
                            , website
                            , first_name
                            , second_name
                            , last_name
                            , gender
                            , dob
                            , address
                            , postal
                            , city
                            , province
                            , country
                            , phone
                            , language
                            , status
                        )
                    VALUES(
                              '%s'
                            , Sha1('%s')
                            , '%s'
                            , '%s'
                            , '%s'
                            , '%s'
                            , '%s'
                            , '%s'
                            , '%s'
                            , '%s'
                            , '%s'
                            , '%s'
                            , '%s'
                            , '%s'
                            , '%s'
                            , '%s'
                            , %d
                        )";
        $array = array(TABLE_PREFIX, $login, $password, $email, $website, $first_name,
            $second_name, $last_name, $gender, $dob, $address, $postal, $city, $province,
            $country, $phone, $language, INSTRUCTOR_ROLE);

        api_backbone(array(
            "request_type" => HTTP_POST,
            "access_level" => ADMIN_ACCESS_LEVEL,
            "query" => $query,
            "query_array" => $array,
            "returned_id_name" => true
        ));

    function put($instructor_id) {
        $clause_check = create_SQL_clause(array(
            login => $_REQUEST["login"],
            email => $_REQUEST["email"]
        ));

        if ($clause_check != "") {
            $checks =   queryDB("SELECT
                                    COUNT(*)
                                 FROM
                                    %smembers ". $clause,
                array(TABLE_PREFIX), true);

            if ($checks["COUNT(*)"] != "0"){
                print_message(ERROR, EMAIL_OR_USERNAME_EXISTS);
            };
        }

        $clause = create_SQL_clause(array(
            login => $_REQUEST["login"],
            password => $_REQUEST["password"],
            email => $_REQUEST["email"],
            website => $_REQUEST["website"],
            first_name => $_REQUEST["first_name"],
            second_name => $_REQUEST["second_name"],
            last_name => $_REQUEST["last_name"],
            gender => $_REQUEST["gender"],
            dob => $_REQUEST["dob"],
            address => $_REQUEST["address"],
            postal => $_REQUEST["postal"],
            city => $_REQUEST["city"],
            province => $_REQUEST["province"],
            country => $_REQUEST["country"],
            phone => $_REQUEST["phone"],
            language => $_REQUEST["language"]
        ), "SET");

        $query_id_existence =   "SELECT
                                    COUNT(*)
                                FROM
                                    %smembers
                                WHERE
                                    member_id = %d
                                        AND
                                    status = %d";
        $query_id_existence_array = array(TABLE_PREFIX, $instructor_id, INSTRUCTOR_ROLE);

        if ($clause) {
            $query = "UPDATE %smembers " . $clause . "WHERE member_id = %d AND status = %d";
            $array = array(TABLE_PREFIX, $instructor_id, INSTRUCTOR_ROLE);
        }

        api_backbone(array(
            "request_type" => HTTP_PUT,
            "access_level" => ADMIN_ACCESS_LEVEL,
            "query" => $query,
            "query_array" => $array,
            "query_id_existence" => $query_id_existence,
            "query_id_existence_array" => $query_id_existence_array
        ));
    }

    function delete($instructor_id) {
        $query = "DELETE FROM
                    %smembers
                  WHERE
                    member_id = %d";
        $array = array(TABLE_PREFIX, $instructor_id);

        $query_id_existence =   "SELECT
                                    COUNT(*)
                                 FROM
                                    %smembers
                                 WHERE
                                    member_id = %d
                                        AND
                                    status = %d";
        $query_id_existence_array = array(TABLE_PREFIX, $instructor_id, INSTRUCTOR_ROLE);

        api_backbone(array(
            "request_type" => HTTP_DELETE,
            "access_level" => ADMIN_ACCESS_LEVEL,
            "query" => $query,
            "query_array" => $array,
            "query_id_existence" => $query_id_existence,
            "query_id_existence_array" => $query_id_existence_array
        ));
    }

    }
}

class CourseInstructorList {
    function get($instructor_id, $course_id) {
        $query = "SELECT
                      m.member_id
                    , m.login
                    , m.email
                    , m.first_name
                    , m.last_name
                    , m.website
                    , m.gender
                    , m.address
                    , m.postal
                    , m.city
                    , m.province
                    , m.country
                    , m.phone
                    , m.language
                    , m.last_login
                    , m.creation_date
                  FROM
                    %smembers AS m
                  INNER JOIN
                    %scourse_enrollment AS ce
                    ON
                        m.member_id = ce.member_id
                  WHERE
                    m.status = %d
                        AND
                    ce.course_id = %d";

        $array = array(TABLE_PREFIX, TABLE_PREFIX, INSTRUCTOR_ROLE, $course_id);

        $query_id_existence =   "SELECT
                                    COUNT(*)
                                 FROM
                                    %scourse_enrollment
                                WHERE
                                    course_id = %d
                                        AND
                                    member_id = %d";
        $query_id_existence_array = array(TABLE_PREFIX, $course_id, $instructor_id);

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

class CourseEnrolledList {
    function get($instructor_id, $course_id) {
        $query = "SELECT
                      m.member_id
                    , m.login
                    , m.email
                    , m.first_name
                    , m.last_name
                    , m.website
                    , m.gender
                    , m.address
                    , m.postal
                    , m.city
                    , m.province
                    , m.country
                    , m.phone
                    , m.language
                    , m.last_login
                    , m.creation_date
                  FROM
                    %smembers AS m
                  INNER JOIN
                    %scourse_enrollment AS ce
                        ON
                            m.member_id = ce.member_id
                  WHERE
                    m.status = %d
                        AND
                    ce.course_id = %d";

        $array = array(TABLE_PREFIX, TABLE_PREFIX, STUDENT_ROLE, $course_id);

        $query_id_existence =   "SELECT
                                    COUNT(*)
                                 FROM
                                    %scourse_enrollment
                                 WHERE
                                    course_id = %d
                                        AND
                                    member_id = %d";
        $query_id_existence_array = array(TABLE_PREFIX, $course_id, $instructor_id);

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

class InstructorCourses {
    function get($instructor_id, $course_id) {

        if ($course_id) {
            $sql_array = array(
                "c.course_id" => $course_id
            );
        } else {
            $sql_array = array(
                "c.title" => $_GET["title"],
                "c.cat_id" => $_GET["category_id"],
                "c.primary_language" => $_GET["primary_language"]
            );
        }

        $clause = create_SQL_clause($sql_array, "AND");

        $query = "SELECT
                      c.course_id
                    , c.cat_id
                    , cc.cat_name
                    , c.created_date
                    , c.title
                    , c.description
                    , c.notify
                    , c.copyright
                    , c.icon
                    , c.release_date
                    , c.primary_language
                    , c.end_date
                    , c.banner
                  FROM
                    %scourses AS c
                  LEFT OUTER JOIN
                    %scourse_cats AS cc
                        ON
                            c.cat_id = cc.cat_id
                  INNER JOIN
                    %scourse_enrollment AS ce
                        ON
                            c.course_id = ce.course_id
                  WHERE
                    ce.member_id = %d ".$clause;

        $array = array(TABLE_PREFIX, TABLE_PREFIX, TABLE_PREFIX, $instructor_id);

        api_backbone(array(
            "request_type" => HTTP_GET,
            "access_level" => INSTRUCTOR_ACCESS_LEVEL,
            "query" => $query,
            "query_array" => $array,
            "one_row" => $course_id ? true : false,
            "member_id" => $instructor_id
        ));
    }

    function post($instructor_id) {
        $access_level = INSTRUCTOR_ACCESS_LEVEL;

        $title = $_REQUEST["title"];
        $access = $_REQUEST["access"];
        $category_id = $_REQUEST["category_id"];
        $release_date = $_REQUEST["release_date"];
        $description = $_REQUEST["description"];
        $notification = $_REQUEST["notification"];
        $copyright = $_REQUEST["copyright"];
        $icon = $_REQUEST["icon"];
        $end_date = $_REQUEST["end_date"];
        $banner = $_REQUEST["banner"];

        if (!$title || !$access || !$instructor_id) {
            print_message(ERROR, INSUFFICIENT_INFORMATION_TO_CREATE_OBJECT);
        }

        $query = "INSERT INTO
                    %scourses(
                          member_id
                        , cat_id
                        , access
                        , title
                        , description
                        , primary_language
                        , icon
                        , release_date
                        , end_date
                        , banner
                    )
                    VALUES(
                          %d
                        , %d
                        , '%s'
                        , '%s'
                        , '%s'
                        , '%s'
                        , '%s'
                        , '%s'
                        , '%s'
                        , '%s'
                    )";
        $array = array(TABLE_PREFIX, $instructor_id, $category_id, $access, $title,
            $description, $primary_language, $icon, $release_date, $end_date, $banner);

        $course_id = api_backbone(array(
            "request_type" => HTTP_POST,
            "access_level" => $access_level,
            "query" => $query,
            "query_array" => $array,
            "returned_id_name" => true
        ));

        queryDB("INSERT INTO
                    %scourse_enrollment(
                          member_id
                        , course_id
                        , approved
                        , privileges
                        , role
                        , last_cid
                    )
                    VALUES(
                          %d
                        , %d
                        , 'n'
                        , 0
                        , 'Instructor'
                        , 0
                    )",
            array(TABLE_PREFIX, $instructor_id, $course_id));

    }

    function put($instructor_id, $course_id) {
        $access_level = INSTRUCTOR_ACCESS_LEVEL;

        $query_id_existence =   "SELECT
                                    COUNT(*)
                                 FROM
                                    %scourses AS c
                                 INNER JOIN
                                    course_enrollment AS ce
                                        ON
                                            c.course_id = cc.course_id
                                 WHERE
                                    c.course_id = %d
                                        AND
                                    ce.member_id = %d";
        $query_id_existence_array = array(TABLE_PREFIX, $course_id, $instructor_id);

        $clause = create_SQL_clause(array(
            "title" => $_REQUEST["title"],
            "access" => $_REQUEST["access"],
            "cat_id" => $_REQUEST["category_id"],
            "release_date" => $_REQUEST["release_date"],
            "description" => $_REQUEST["description"],
            "notification" => $_REQUEST["notification"],
            "copyright" => $_REQUEST["copyright"],
            "icon" => $_REQUEST["icon"],
            "end_date" => $_REQUEST["end_date"],
            "banner" => $_REQUEST["banner"]
        ), "SET");

        if ($clause){
            $query = "UPDATE %scourses ".$clause. "WHERE course_id = %d ". $clause;
            $array = array(TABLE_PREFIX, $course_id);
        } else {
            $query = "";
            $array = array();
        }

        api_backbone(array(
            "request_type" => HTTP_PUT,
            "access_level" => $access_level,
            "query" => $query,
            "query_array" => $array,
            "query_id_existence" => $query_id_existence,
            "query_id_existence_array" => $query_id_existence_array
        ));
    }
}

class InstructorsTests {
    function get($instructor_id, $course_id, $test_id) {
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

        $clause  = create_SQL_clause($sql_array, "AND");

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
                    %stests
                  WHERE
                    course_id = %d ". $clause;

        $array = array(TABLE_PREFIX, $course_id);

        $query_id_existence =   "SELECT
                                    COUNT(*)
                                 FROM
                                    %scourse_enrollment
                                 WHERE
                                    member_id = %d
                                 AND
                                    course_id = %d";

        $query_id_existence_array = array(TABLE_PREFIX, $instructor_id, $course_id);

        api_backbone(array(
             "request_type" => HTTP_GET,
             "access_level" => INSTRUCTOR_ACCESS_LEVEL,
             "query_id_existence" => $query_id_existence,
             "query_id_existence_array" => $query_id_existence_array,
             "query" => $query,
             "query_array" => $array,
             "one_row" => $test_id ? true : false,
             "member_id" => $instructor_id
        ));
    }
}

class InstructorsTestQuestions {
    function get($instructor_id, $course_id, $test_id, $question_id) {

        $query_id_existence =   "SELECT
                                    COUNT(*)
                                 FROM
                                    %scourse_enrollment
                                 WHERE
                                    member_id = %d
                                        AND
                                    course_id = %d";

        $query_id_existence_array = array(TABLE_PREFIX, $instructor_id, $course_id);

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
                  LEFT OUTER JOIN
                    %stests_questions_categories AS qc
                        ON
                            q.category_id = qc.category_id
                  WHERE
                    q.course_id = %d
                        AND
                    q.question_id
                        IN
                            (SELECT
                                question_id
                             FROM
                                %stests_questions_assoc
                             WHERE
                                test_id = %d
                            ) ". $clause;

        $array = array(TABLE_PREFIX, TABLE_PREFIX, $course_id, TABLE_PREFIX, $test_id);

        api_backbone(array(
             "request_type" => HTTP_GET,
             "access_level" => INSTRUCTOR_ACCESS_LEVEL,
             "query" => $query,
             "query_array" => $array,
             "one_row" => $question_id ? true : false,
             "query_id_existence" => $query_id_existence,
             "query_id_existence_array" => $query_id_existence_array,
             "member_id" => $instructor_id
        ));
    }
}

?>
