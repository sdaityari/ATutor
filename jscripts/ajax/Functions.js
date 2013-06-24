var ATutor = ATutor || {};

ATutor.ajaxFunctions = ATutor.ajaxFunctions || {};

(function(ajaxFunctions) {

    "use strict";

    ajaxFunctions.successfulCode = "ACTION_COMPLETED_SUCCESSFULLY";

    //Function to generate a dialog box on getting an AJAX response
    ajaxFunctions.generateDialog = function (responseMessage, messages) {

        var ajaxResponse = "Action unsuccessful",
            unknownErrorMessage = "Unknown Error Occurred",
            responseId = "ajax-response-dialog",
            responseDialog = $("#" + responseId),
            defaults = {
                "ACCESS_DENIED" : "Access Denied",
                "COMMENT_EMPTY" : "Comment cannot be empty",
                "PAGE_NOT_FOUND" : "Content does not exist"
            };

        var messages = messages || {};

        messages = $.extend({}, defaults, messages);

        // Create dialog for the page if it doesn't exist
        if (!responseDialog.length) {
            responseDialog = $("<div />", {
                title: ajaxResponse,
                id: responseId
            }).appendTo($("body"));
        }

        responseDialog.html(messages[responseMessage] || unknownErrorMessage);

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
