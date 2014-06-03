<?php

function get_courses_of_member($member_id){
    $courses = queryDB("SELECT c.course_id, c.cat_id, cc.cat_name, c.created_date, ".
        "c.title, c.description, c.notify, c.copyright, c.icon, c.release_date, c.primary_language, ".
        "c.end_date, c.banner FROM %scourses c, %scourse_cats cc, %scourse_enrollment ce WHERE c.cat_id = cc.cat_id ".
        "AND ce.member_id = %d AND ce.course_id = c.course_id", array(TABLE_PREFIX, TABLE_PREFIX, TABLE_PREFIX,
        $member_id));
    return json_encode($courses);
}

function get_course_details($member_id, $course_id) {
    $courses = queryDB("SELECT c.course_id, c.cat_id, cc.cat_name, c.created_date, ".
        "c.title, c.description, c.notify, c.copyright, c.icon, c.release_date, c.primary_language, ".
        "c.end_date, c.banner FROM %scourses c, %scourse_cats cc, %scourse_enrollment ce WHERE c.cat_id = cc.cat_id ".
        "AND ce.member_id = %d AND ce.course_id = %d AND ce.course_id = c.course_id",
        array(TABLE_PREFIX, TABLE_PREFIX, TABLE_PREFIX, $member_id, $course_id), true);
    return json_encode($courses);
}

?>