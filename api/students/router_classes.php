<?php

class StudentList {
    function get() {
        $clause = create_SQL_clause(array(
            "email" => $_GET["email"],
            "first_name" => $_GET["first_name"],
            "last_name" => $_GET["last_name"],
            "login" => $_GET["login"]));
        get_members_main(STUDENT_ROLE, -1, $clause);
    }
}

class StudentDetails {
    function get($student_id) {
        get_members_main(STUDENT_ROLE, $student_id);
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