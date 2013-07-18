/**
 * @author Shaumik Daityari
 * @copyright Copyright © 2013, ATutor, All rights reserved.
 */

var ATutor = ATutor || {};

ATutor.search = ATutor.search || {};

(function(search) {
    "use strict";

    search.toggleAdvanced = function () {
        var advancedSearch = $("#advanced-search"),
            advancedSearchText = $("#advanced-search-text"),
            toggleStrings = {
                show : "[+] Advanced",
                hide : "[-] Advanced"
            };

        $(advancedSearchText).html( ($(advancedSearchText).html() === toggleStrings.show) ? toggleStrings.hide : toggleStrings.show );
        $(advancedSearch).toggle();

    };

})(ATutor.search);
