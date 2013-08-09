ATutor = ATutor || {};

ATutor.helpPage = ATutor.helpPage || {};

(function (helpPage) {
    "use strict";

    var css = {
        navId: "nav-",
        subNavListId: "subnavlist"
    };

    helpPage.changeActive = function (name) {
        unfocus();
        $("#" + css.navId + name).addClass("active");
    };

    var unfocus = function () {
        $("#"+css.subNavListId+">li").each( function (index, li) {
            li.className = "";
        });
    };

})(ATutor.helpPage);
