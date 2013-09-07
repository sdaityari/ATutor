/**
 * @author Shaumik Daityari
 * @copyright Copyright © 2013, ATutor, All rights reserved.
 */

var ATutor = ATutor || {};
ATutor.browseCourses = ATutor.browseCourses || {};

(function(browseCourses) {
    var css = {
        formId : "browse-courses-form",
        rowId : "accordeon_",
        oddClass : "odd",
        evenClass : "even",
        allWords : "all",
        anyWord : "one",
        resultsId : "results_found",
        matchId : "match-buttons-row",
        accessId : "access-row",
        advancedSearchId : "advanced-search",
        noResultsId : "no-results-found"
    }, noResults = $("#" + css.noResultsId);

    $(document).ready(function () {
        a11yAccordeon({
            container: ".a11yAccordeon",
            hiddenLinkDescription: "Course Description"
        });
    });

    $("#" + css.formId).bind("change keyup", function () {
        browseCourses.change();
    });

    browseCourses.change = function() {
        showAll(); // show all elements
        updateAccess(); // hide based on accessibility
        updateText(); // hide based on search text
        if (!$("li[id^='" + css.rowId + "']:visible").length) {
            $(noResults).show();
        } else {
            $(noResults).hide();
        }
    };

    var showAll = function () {
        $("li[id^='" + css.rowId + "']:hidden").each(function (index, value) {
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
            isAll;

        if (!text.length) {
            return;
        }

        /*
         * Any Word: In case it matches any word, we return true
         * All Words: In case it does not match any word, we return false
         */
        if (match === css.anyWord) {
            isAll = false;
        } else if (match === css.allWords) {
            isAll = true;
        } else {
            return;
        }

        $.each(ATutor.courseInfo, function (index, value) {
            if (! (compareStrings(value.title.toLowerCase(), substrings, isAll) ||
                        compareStrings(value.description.toLowerCase(), substrings, isAll)) ) {

                $("#" + css.rowId + value.course_id).hide();
            }
        });
    };

    var compareStrings = function (string, substrings, logicalAnd) {
        for (var i=0, len=substrings.length; i<len; i+=1) {
            if ((string.indexOf(substrings[i]) >= 0) !== logicalAnd) {
                return !logicalAnd;
            }
        }
        return logicalAnd;
    };

    browseCourses.toggleAdvanced = function () {
        var advancedSearch = $("#" + css.advancedSearchId),
            toggleStrings = {
                show : "[+] Advanced",
                hide : "[-] Advanced"
            };

        $(advancedSearch).html( ($(advancedSearch).html() === toggleStrings.show) ? toggleStrings.hide : toggleStrings.show );
        $("#" + css.matchId).toggle();
        $("#" + css.accessId).toggle();
    };

})(ATutor.browseCourses);
