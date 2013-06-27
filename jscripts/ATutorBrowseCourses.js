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
        var elements = $("tr[id^='row_']:hidden"),
            length = elements.length;

        for (var i=0; i<length; i+=1) {
            $(elements[i]).show();
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
        var match = $("input[name=include]:checked","#" + css.formId).val(),
            text = $("input[name=search]","#" + css.formId).val().toLowerCase(),
            info = ATutor.courseInfo,
            length = info.length,
            substrings = text.split(" "),
            callback;

        if (text.length === 0) {
            return;
        }

        if (match === css.anyWord) {
            callback = compareAny;
        } else if (match === css.allWords) {
            callback = compareAll;
        } else {
            return;
        }

        for (var i=0; i<length; i+=1) {
            if (! (callback(info[i].title.toLowerCase(), substrings) ||
                        callback(info[i].description.toLowerCase(), substrings)) ) {

                $("#" + css.rowId + info[i].course_id).hide();
            }
        }
    };

    var compareAny = function (string, substrings) {
        var length = substrings.length;
        for (var i=0; i<length; i+=1) {
            if (string.indexOf(substrings[i]) !== -1) {
                return true;
            }
        }
        return false;
    };

    var compareAll = function (string, substrings) {
        var length = substrings.length;
        for (var i=0; i<length; i+=1) {
            if (string.indexOf(substrings[i]) === -1) {
                return false;
            }
        }
        return true;
    };

    var changeStripes = function () {
        var elements = $("tr[id^='row_']:visible"),
            length = elements.length;
        for (var i=0; i<length; i+=1) {
            elements[i].className = (i%2 === 0)? css.oddClass : css.evenClass;
        }
    };

})(ATutor.browseCourses);
