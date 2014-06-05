<?php

function get_courses($variables, $member_id = -1){

    // random values
    $random_int = rand(100000, 500000);
    $random = md5($random_int);

    // GET Parameters
    $member_id = $member_id == -1? $random_int : $member_id;
    $title = $variables["title"] ? addslashes($variables["title"]) : $random;
    //print_r($variables);
    //$release_date = $variables["release_date"]?addslashes($variables["release_date"]):$random;
    //$end_date = $variables["end_date"]?addslashes($variables["end_date"]):$random;
    $category_id = $variables["category_id"] ? addslashes($variables["category_id"]) : $random_int;
    $primary_language = $variables["primary_language"] ? addslashes($variables["primary_language"]) : $random;

    $courses = queryDB("SELECT c.course_id, c.cat_id, cc.cat_name, c.created_date, ".
        "c.title, c.description, c.notify, c.copyright, c.icon, c.release_date, c.primary_language, ".
        "c.end_date, c.banner FROM %scourses c, %scourse_cats cc, %scourse_enrollment ce WHERE c.cat_id = cc.cat_id ".
        "AND ((%d = %d OR ce.member_id = %d) AND ce.course_id = c.course_id) ".
        "AND ('%s' = '%s' OR c.title like '%%%s%%') ".
        //"AND ('%s' = '%s' OR c.release_date = %s) ".
        //"AND ('%s' = '%s' OR c.end_date = %s) ".
        "AND (%d = %d OR c.cat_id = %d) ".
        "AND ('%s' = '%s' OR c.primary_language = '%s')",
        array(TABLE_PREFIX, TABLE_PREFIX, TABLE_PREFIX,
            $member_id, $random_int, $member_id,
            $title, $random, $title,
            //$release_date, $random, $release_date,
            //$end_date, $random, $end_date,
            $category_id, $random_int, $category_id,
            $primary_language, $random, $primary_language));

    return json_encode($courses);
}

function get_course_details($course_id, $member_id = -1) {
    // random values
    $random_int = rand(100000, 500000);

    $member_id = $member_id == -1? $random_int : $member_id;

    $courses = queryDB("SELECT c.course_id, c.cat_id, cc.cat_name, c.created_date, ".
        "c.title, c.description, c.notify, c.copyright, c.icon, c.release_date, c.primary_language, ".
        "c.end_date, c.banner FROM %scourses c, %scourse_cats cc, %scourse_enrollment ce WHERE c.cat_id = cc.cat_id ".
        "AND ((%d = %d OR ce.member_id = %d) AND ce.course_id = c.course_id) AND c.course_id = %d",
        array(TABLE_PREFIX, TABLE_PREFIX, TABLE_PREFIX,
            $member_id, $random_int, $member_id, $course_id), true);
    if (count($courses) == 0){
        http_response_code(404);
    }
    return json_encode($courses);
}

?>