<?php

class InstructorCoursesList {
    function get($instructor_id) {
        $clause = create_SQL_clause(array(
            "c.title" => $_GET["title"],
            "c.cat_id" => $_GET["category_id"],
            "c.primary_language" => $_GET["primary_language"]));
        get_courses_main(INSTRUCTOR_ACCESS_LEVEL, $clause, -1, $instructor_id);
    }
}

class InstructorCoursesDetails {
    function get($instructor_id, $course_id) {
        get_courses_main(INSTRUCTOR_ACCESS_LEVEL, NULL, $course_id, $instructor_id);
    }
}