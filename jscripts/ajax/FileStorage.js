/**
 * @author Shaumik Daityari
 * @copyright Copyright © 2013, ATutor, All rights reserved.
 */

var ATutor = ATutor || {};

ATutor.fileStorage = ATutor.fileStorage || {};

(function(fileStorage) {

    "use strict";

    var deleteMessage = "Are you sure you want to delete this comment?",
        deleteTitle = "Delete Comment",
        deleteUrl = "mods/_standard/file_storage/ajax/delete_comment.php",
        deleteDialog = $("#comment-delete-dialog");

    //Function to be called on clicking Delete for a comment
    fileStorage.deleteComment = function (ot, oid, file_id, id) {
        
        //Sets POST variables to be sent
        var parameters = {
            "ot" : ot,
            "oid": oid,
            "file_id": file_id,
            "id": id,
            "submit_yes": true
        };

    
        var buttonOptions = {
            "Delete Comment":  function (){
                        $.ajax({
                            type: "POST",
                            url: deleteUrl,
                            data: parameters,
                            success: function(message) {
                                commentOnDelete(message, parameters);
                            }
                        });
                        deleteDialog.dialog("close");
                    },
            "Cancel" :  function () {
                        deleteDialog.dialog("close");
                    }
        };

        // Create dialog for the page if it doesn't exist
        if (deleteDialog.length === 0){
            $("body").append("<div title='" + deleteTitle + "' id='comment-delete-dialog'>" +
                    deleteMessage +"</div>");
            deleteDialog = $("#comment-delete-dialog");
        }

        deleteDialog.dialog({
            autoOpen: true,
            width: 400,
            modal: true,
            closeOnEscape: false,
            buttons: buttonOptions
        });
        
    };
    
    //Callback function for AJAX Request
    var commentOnDelete = function (responseMessage, parameters) {
        var ajaxResponse = "Action unsuccessful",
            accessDeniedMessage = "Access Denied",
            unknownErrorMessage = "Unknown Error Occurred",
            responseDialog = $("#ajax-response-dialog");

        // Create dialog for the page if it doesn't exist
        if (responseDialog.length === 0){
            $("body").append("<div title='" + ajaxResponse + "' id='ajax-response-dialog'></div>");
            responseDialog = $("#ajax-response-dialog");
        }

        if (responseMessage === "ACTION_COMPLETED_SUCCESSFULLY") {
            $("#comment" + parameters.id).fadeOut();
            return;
        } else if (responseMessage === "ACCESS_DENIED") {
            responseDialog.html(accessDeniedMessage);
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

})(ATutor.fileStorage);
