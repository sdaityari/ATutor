<?php

class StudentCoursesList {
    function get($student_id) {
        $clause = create_SQL_clause(array(
            "title" => "c.title",
            "category_id" => "c.cat_id",
            "primary_language" => "c.primary_language"
        ), $_GET);

        get_courses_main(STUDENT_ACCESS_LEVEL, $clause, -1, $student_id);
    }
}

class StudentCoursesDetails {
    function get($student_id, $course_id) {
        get_courses_main(STUDENT_ACCESS_LEVEL, NULL, $course_id, $student_id);
    }
}

?>