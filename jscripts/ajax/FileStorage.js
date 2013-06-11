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
        deleteUrl = "mods/_standard/file_storage/ajax/delete_comment.php";

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
            "Yes":  function (){
                        $.ajax({
                            type: "POST",
                            url: deleteUrl,
                            data: parameters,
                            success: function(message) {
                                commentOnDelete(message, parameters);
                            }
                        });
                        $(this).dialog("close");
                    },
            "No" :  function () {
                        $(this).dialog("close");
                    }
        };

        // Create dialog for the page if it doesn't exist
        if ($("#comment-delete-dialog").length === 0){
            $("body").append("<div title='" + deleteTitle + "' id='comment-delete-dialog'>" +
                    deleteMessage +"</div>");
        }

        $("#comment-delete-dialog").dialog({
            autoOpen: true,
            width: 400,
            modal: true,
            closeOnEscape: false,
            buttons: buttonOptions
        });
        
    };
    
    //Callback function for AJAX Request
    var commentOnDelete = function (responseMessage, parameters) {
        var ajaxResponse = "Ajax Response",
            accessDeniedMessage = "Access Denied",
            unknownErrorMessage = "Unknown Error Occurred";

        // Create dialog for the page if it doesn't exist
        if ($("#ajax-response-dialog").length === 0){
            $("body").append("<div title='" + ajaxResponse + "' id='ajax-response-dialog'></div>");
        }

        if (responseMessage === "ACTION_COMPLETED_SUCCESSFULLY") {
            $("#comment" + parameters.id).fadeOut();
            return;
        } else if (responseMessage === "ACCESS_DENIED") {
            $("#ajax-response-dialog").html(accessDeniedMessage);
        } else {
            $("#ajax-response-dialog").html(unknownErrorMessage);
        }

        //Set an Ok button for the dialog box to be shown in case the comment was not deleted
        var buttonOptions = {
            "Ok" : function () {
                $(this).dialog("close");
            }
        };

        $("#ajax-response-dialog").dialog({
            autoOpen: true,
            width: 400,
            modal: true,
            closeOnEscape: false,
            buttons: buttonOptions
        });
    };

})(ATutor.fileStorage);
