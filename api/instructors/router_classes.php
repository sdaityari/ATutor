<?php

class InstructorList {
    function get() {
        $clause = create_SQL_clause(array(
            "email" => $_GET["email"],
            "first_name" => $_GET["first_name"],
            "last_name" => $_GET["last_name"],
            "login" => $_GET["login"]));
        get_members_main(INSTRUCTOR_ROLE, -1, $clause);
    }
}

class InstructorDetails {
    function get($instructor_id) {
        get_members_main(INSTRUCTOR_ROLE, $instructor_id);
    }
}

class InstructorCoursesList {
    function get($instructor_id) {
        $clause = create_SQL_clause(array(
            "c.title" => $_GET["title"],
            "c.cat_id" => $_GET["category_id"],
            "c.primary_language" => $_GET["primary_language"]));
        get_courses_main(INSTRUCTOR_ACCESS_LEVEL, $clause, -1, $instructor_id);
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

        $query = "INSERT INTO %scourses(member_id, cat_id, access, title, ".
            "description, primary_language, icon, release_date, end_date, banner) ".
            "VALUES(%d, %d, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')";
        $array = array(TABLE_PREFIX, $instructor_id, $category_id, $access, $title,
            $description, $primary_language, $icon, $release_date, $end_date, $banner);

        $course_id = api_backbone(array(
            "request_type" => HTTP_POST, 
            "access_level" => $access_level,
            "query" => $query,
            "query_array" => $array,
            "returned_id_name" => true
        ));

        queryDB("INSERT INTO %scourse_enrollment(member_id, course_id, approved, privileges, role, last_cid) ".
            "VALUES(%d, %d, 'n', 0, 'Instructor', 0)", array(TABLE_PREFIX, $instructor_id, $course_id));

    }

}

class InstructorCoursesDetails {
    function get($instructor_id, $course_id) {
        get_courses_main(INSTRUCTOR_ACCESS_LEVEL, NULL, $course_id, $instructor_id);
    }

    function put($instructor_id, $course_id) {
        $access_level = INSTRUCTOR_ACCESS_LEVEL;

        $query_id_existence = "SELECT COUNT(*) FROM %scourses c".
        "INNER JOIN course_enrollment ce ON c.course_id = cc.course_id ".
        "WHERE c.course_id = %d AND ce.member_id = %d";
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
        ));

        $query = "UPDATE %scourses SET ".$clause. "WHERE course_id = %d";
        $array = array(TABLE_PREFIX, $course_id);

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