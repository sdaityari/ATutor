var ATutor = ATutor || {};

ATutor.ajaxFunctions = ATutor.ajaxFunctions || {};

(function(ajaxFunctions) {

    "use strict";

    //Function to generate a dialog box on getting an AJAX response
    ajaxFunctions.generateDialog = function (responseMessage) {

        var ajaxResponse = "Action unsuccessful",
            notFoundMessage = "Comment does not exist",
            accessDeniedMessage = "Access Denied",
            unknownErrorMessage = "Unknown Error Occurred",
            commentEmptyMessage = "Comment cannot be empty",
            responseDialog = $("#ajax-response-dialog");

        // Create dialog for the page if it doesn't exist
        if (responseDialog.length === 0){
            $("<div />", {
                title: ajaxResponse,
                id: "ajax-response-dialog"
            }).appendTo($("body"));
            responseDialog = $("#ajax-response-dialog");
        }

        if (responseMessage === "ACCESS_DENIED") {
            responseDialog.html(accessDeniedMessage);
        } else if (responseMessage === "COMMENT_EMPTY") {
            responseDialog.html(commentEmptyMessage);
        } else if (responseMessage === "PAGE_NOT_FOUND") {
            responseDialog.html(notFoundMessage);
        } else {
            responseDialog.html(unknownErrorMessage);
        }

        //Set an Ok button for the dialog box to be shown in case the comment was not deleted
        var buttonOptions = {
            "Ok" : function () {
                responseDialog.dialog("close");
            }
        };

        responseDialog.dialog({
            autoOpen: true,
            width: 400,
            modal: true,
            closeOnEscape: false,
            buttons: buttonOptions
        });


    };

})(ATutor.ajaxFunctions);
