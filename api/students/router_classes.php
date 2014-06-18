<?php

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