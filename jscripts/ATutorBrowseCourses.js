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
        evenClass : "even",
        allWords : "all",
        anyWord : "one"
    };

    browseCourses.change = function() {
        showAll(); // show all elements
        updateAccess(); // hide based on accessibility
        updateText(); // hide based on search text
        changeStripes(); //change alternating classes of elements
    };

    var showAll = function () {
        $("tr[id^='row_']:hidden").each(function (index, value) {
            $(value).show();
        });
    };

    var updateAccess = function () {
        var access = $("input[name=access]:checked","#" + css.formId).val(),
            info;

        if (!access.length) {
            return;
        }

        $.each(ATutor.courseInfo, function (index, value) {
            if (access !== value.access) {
                $("#" + css.rowId + value.course_id).hide();
            }
        });
    };

    var updateText = function () {
        var match = $("input[name=include]:checked","#" + css.formId).val(),
            text = $("input[name=search]","#" + css.formId).val().toLowerCase(),
            substrings = $.trim(text).split(" "),
            callback;

        if (!text.length) {
            return;
        }

        if (match === css.anyWord) {
            callback = compareAny;
        } else if (match === css.allWords) {
            callback = compareAll;
        } else {
            return;
        }

        $.each(ATutor.courseInfo, function (index, value) {
            if (! (callback(value.title.toLowerCase(), substrings) ||
                        callback(value.description.toLowerCase(), substrings)) ) {

                $("#" + css.rowId + value.course_id).hide();
            }
        });
    };

    var compareAny = function (string, substrings) {
        var returnValue = false;

        $.each(substrings, function (index, value) {
            if (string.indexOf(value) !== -1) {
                returnValue = true;
                return false;
            }
        });
        return returnValue;
    };

    var compareAll = function (string, substrings) {
        var returnValue = true;

        $.each(substrings, function (index, value) {
            if (string.indexOf(value) === -1) {
                returnValue = false;
                return false;
            }
        });
        return returnValue;
    };

    var changeStripes = function () {
        $("tr[id^='row_']:visible").each( function (index, value) {
            value.className = (index % 2 === 0)? css.oddClass : css.evenClass;
        });
    };

})(ATutor.browseCourses);
