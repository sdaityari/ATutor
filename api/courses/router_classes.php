<?php

class CourseHome {
    function get() {
        $token = get_access_token(getallheaders(), STUDENT_ACCESS_LEVEL);

        $courses = queryDB("SELECT x.course_id, x.cat_id, y.cat_name, x.created_date, ".
            "x.title, x.description, x.notify, x.copyright, x.icon, x.release_date, ".
            "x.end_date, x.banner FROM %scourses x, %scourse_cats y WHERE x.cat_id = y.cat_id",
            array(TABLE_PREFIX, TABLE_PREFIX));

        echo json_encode($courses);
    }
}

class CourseDetails {
    function get($course_id) {
        $token = get_access_token(getallheaders(), STUDENT_ACCESS_LEVEL);
        $course = queryDB("SELECT x.course_id, x.cat_id, y.cat_name, x.created_date, ".
            "x.title, x.description, x.notify, x.copyright, x.icon, x.release_date, x.end_date, ".
            "x.banner FROM %scourses x, %scourse_cats y WHERE x.cat_id = y.cat_id AND x.course_id = %d",
            array(TABLE_PREFIX, TABLE_PREFIX, $course_id), true);

        echo json_encode($course);
    }
}

?>