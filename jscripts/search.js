/**
 * @author Shaumik Daityari
 * @copyright Copyright © 2013, ATutor, All rights reserved.
 */

var ATutor = ATutor || {};

ATutor.search = ATutor.search || {};

(function(search) {
    "use strict";

    var toggleStrings = {
            show : "[+] Advanced",
            hide : "[-] Advanced"
        }, css = {
            search : "advanced-search",
            searchText: "advanced-search-text"
        };


    search.toggleAdvanced = function () {
        var advancedSearchText = $("#" + css.searchText);

        advancedSearchText.text( (advancedSearchText.text() === toggleStrings.show) ? toggleStrings.hide : toggleStrings.show );

        $("#" + css.search).toggle();

    };

})(ATutor.search);
