<?php

function get_courses_main($clause = NULL, $course_id = -1, $member_id = -1) {
    $one_row = $course_id == -1? false : true;

    $query = "SELECT c.course_id, c.cat_id, cc.cat_name, c.created_date, ".
    "c.title, c.description, c.notify, c.copyright, c.icon, c.release_date, c.primary_language, ".
    "c.end_date, c.banner FROM %scourses c, %scourse_cats cc, %scourse_enrollment ce WHERE c.cat_id = cc.cat_id";

    if ($member_id != -1) {
        $query = $query." AND ce.member_id = ".$member_id;
    }

    if ($course_id != -1) {
        $query = $query." AND c.course_id = ".$course_id;
    }

    if ($clause) {
        $query = $query." AND ".$clause;
    }

    $courses = queryDB($query, array(TABLE_PREFIX, TABLE_PREFIX, TABLE_PREFIX), $one_row);

    if ($one_row && count($courses) == 0){
        http_response_code(404);
    }

    return json_encode($courses);
}

?>