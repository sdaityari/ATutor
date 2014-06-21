<?php
    define ("DEBUG", true);

    define ("ERROR", 1);
    define ("SUCCESS", 2);

    define ("TOKEN_NAME", "x-AT-API-TOKEN");

    define("ADMIN_ACCESS_LEVEL", 1);
    define("INSTRUCTOR_ACCESS_LEVEL", 2);
    define("STUDENT_ACCESS_LEVEL", 3);
    define("TOKEN_ACCESS_LEVEL", 4);
    define("PUBLIC_ACCESS_LEVEL", 5);

    // Member roles
    define("STUDENT_ROLE", 2);
    define("INSTRUCTOR_ROLE", 3);

    // http methods
    define("HTTP_GET", 1);
    define("HTTP_POST", 2);
    define("HTTP_PUT", 3);
    define("HTTP_DELETE", 4);

    // custom messages
    define("TOKEN_DOES_NOT_EXIST", "The token you provided does not exist");
    define("YOU_ARE_NOT_AUTHORIZED_TO_ACCESS_THIS_RESOURCE", "You are not authorized to access this resource");
    define("WRONG_CREDENTIALS", "The username password combination is wrong");
    define("NOT_CONFIRMED", "Your account has not been confirmed");
    define("ACCOUNT_DISABLED", "Your accoutn has been disabled");
    define("RESOURCE_DOES_NOT_EXIST", "The resource your requested does not exist");
    define("ACTION_COMPLETED_SUCCESSFULLY", "The action was completed successfully.");
    define("INSUFFICIENT_INFORMATION_TO_CREATE_OBJECT", "The information you provided wasn't enough to create the object");
    define("MODULE_DISABLED", "Module is not enabled! Please contact the ATutor administrator.");

?>