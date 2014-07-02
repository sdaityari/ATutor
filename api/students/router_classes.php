<?php

class StudentList {
    function get() {
        $clause = create_SQL_clause(array(
            "email" => $_GET["email"],
            "first_name" => $_GET["first_name"],
            "last_name" => $_GET["last_name"],
            "login" => $_GET["login"]));
        get_members_main(STUDENT_ROLE, STUDENT_ACCESS_LEVEL, -1, $clause);
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

        $checks = queryDB("SELECT COUNT(*) FROM %smembers WHERE login = '%s' OR email = '%s'",
            array(TABLE_PREFIX, $login, $email), true);

        if ($checks["COUNT(*)"] != "0"){
            print_message(ERROR, EMAIL_OR_USERNAME_EXISTS);
        };

        $query = "INSERT INTO %smembers(login, password, email, website, first_name, second_name, ".
            "last_name, gender, dob, address, postal, city, province, country, phone, language, status) VALUES(".
            "'%s', Sha1('%s'), '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %d)";
        $array = array(TABLE_PREFIX, $login, $password, $email, $website, $first_name, $second_name, $last_name,
            $gender, $dob, $address, $postal, $city, $province, $country, $phone, $language, STUDENT_ROLE);

        api_backbone(array(
            "request_type" => HTTP_POST,
            "access_level" => ADMIN_ACCESS_LEVEL,
            "query" => $query,
            "query_array" => $array,
            "returned_id_name" => true
        ));

    }
}

class StudentDetails {
    function get($student_id) {
        get_members_main(STUDENT_ROLE, STUDENT_ACCESS_LEVEL, $student_id);
    }

    function put($student_id) {
        $clause_check = create_SQL_clause(array(
            login => $_REQUEST["login"],
            email => $_REQUEST["email"]
        ));

        if ($clause_check != "") {
            $checks = queryDB("SELECT COUNT(*) FROM %smembers WHERE ". $clause,
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
        ));

        $query_id_existence = "SELECT COUNT(*) FROM %smembers WHERE member_id = %d AND status = %d";
        $query_id_existence_array = array(TABLE_PREFIX, $student_id, STUDENT_ROLE);

        $query = "UPDATE %smembers SET " . $clause . "WHERE member_id = %d AND status = %d";
        $array = array(TABLE_PREFIX, $student_id, STUDENT_ROLE);

        api_backbone(array(
            "request_type" => HTTP_PUT,
            "access_level" => ADMIN_ACCESS_LEVEL,
            "query" => $query,
            "query_array" => $array,
            "query_id_existence" => $query_id_existence,
            "query_id_existence_array" => $query_id_existence_array
        ));
    }

    function delete($student_id) {
        $query = "DELETE FROM %smembers WHERE member_id = %d";
        $array = array(TABLE_PREFIX, $student_id);

        $query_id_existence = "SELECT COUNT(*) FROM %smembers WHERE member_id = %d AND status = %d";
        $query_id_existence_array = array(TABLE_PREFIX, $student_id, STUDENT_ROLE);

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

class StudentCoursesList {
    function get($student_id) {
        $clause = create_SQL_clause(array(
            "c.title" => $_GET["title"],
            "c.cat_id" => $_GET["category_id"],
            "c.primary_language" => $_GET["primary_language"]));

        get_courses_main(STUDENT_ACCESS_LEVEL, $clause, -1, $student_id);
    }
}

class StudentCoursesDetails {
    function get($student_id, $course_id) {
        get_courses_main(STUDENT_ACCESS_LEVEL, NULL, $course_id, $student_id);
    }
}

?>
