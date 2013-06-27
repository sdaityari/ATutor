/**
 * @author Shaumik Daityari
 * @copyright Copyright © 2013, ATutor, All rights reserved.
 */

var ATutor = ATutor || {};
ATutor.browseCourses = ATutor.browseCourses || {};

(function(browseCourses) {
    var css = {
        formId : "browse-courses-form",
        rowId : "row_",
        oddClass : "odd",
        evenClass : "even"
    };

    $("#" + css.formId).change(function() {
        showAll(); // show all elements
        updateAccess(); // hide based on accessibility
        updateText(); // hide based on search text
        changeStripes(); //change alternating classes of elements
    });

    var showAll = function () {
        var length = ATutor.courseInfo.length;
        for (var i=0; i<length; i+=1) {
            $("#" + css.rowId + ATutor.courseInfo[i].course_id).show();
        }
    };

    var updateAccess = function () {
        var access = $("input[name=access]:checked","#" + css.formId).val(),
            length = ATutor.courseInfo.length,
            info;

        if (access === "") {
            return;
        }

        for (var i=0; i<length; i+=1) {
            info = ATutor.courseInfo[i];
            if (access !== info.access) {
                $("#" + css.rowId + info.course_id).hide();
            }
        }
    };

    var updateText = function () {
        return;
    };

    var changeStripes = function () {
        var elements = $("tr[id^='row_']:visible"),
            length = elements.length;
        for (var i=0; i<length; i+=1) {
            elements[i].className = (i%2 === 0)? "odd" : "even";
        }
    };

})(ATutor.browseCourses);
