/**
 * @author Shaumik Daityari
 * @copyright Copyright © 2013, ATutor, All rights reserved.
 */

var ATutor = ATutor || {};

ATutor.fileStorage = ATutor.fileStorage || {};

(function(fileStorage) {

    "use strict";

    //Function to be called on clicking Delete for a comment
    fileStorage.deleteComment = function (options) {
       
        var defaults = { 
            deleteMessage : "Are you sure you want to delete this comment?",
            deleteTitle : "Delete Comment",
            deleteUrl : "mods/_standard/file_storage/ajax/comments.php"
        };
        
        var deleteDialog = $("#comment-delete-dialog");

        options = options || {};

        options = $.extend({}, defaults, options);

        /** 
         * Sets POST variables to be sent
         * @parameters
         * ot: owner type
         * oid: owner id
         * file_id: file id (primary key of files)
         * id: comment id (primary key of filescomments)
         */
        var parameters = {
            ot : options.ot,
            oid: options.oid,
            fileId: options.fileId,
            id: options.id,
            deleteSubmit: true
        };

    
        var buttonOptions = {
            "Delete Comment":  function (){
                        $.ajax({
                            type: "POST",
                            url: options.deleteUrl,
                            data: parameters,
                            success: function(message) {
                                commentOnDelete(message, parameters.id);
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
            $("<div />", {
                title: options.deleteTitle,
                text: options.deleteMessage,
                id: "comment-delete-dialog"
            }).appendTo($("body"));
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
    var commentOnDelete = function (responseMessage, id) {
        if (responseMessage === "ACTION_COMPLETED_SUCCESSFULLY") {
            $("#comment" + id).fadeOut();
            return;
        } else {
            generateDialog(responseMessage);
        }
    };

    //Function to be called on clicking Edit under a comment
    fileStorage.editCommentShow = function (id) {
        $("#edit-comment-" + id).show();
        $("#comment-edit-delete-" + id).hide();
        $("#comment-description-" + id).hide();
    };

    //Function to be called on clicking submit after editing a comment
    fileStorage.editCommentSubmit = function (options) {
       
        var defaults = { updateUrl : "mods/_standard/file_storage/ajax/comments.php" };
        
        options = options || {};

        options = $.extend({}, defaults, options);

        /** 
         * Sets POST variables to be sent
         * @parameters
         * ot: owner type
         * oid: owner id
         * file_id: file id (primary key of files)
         * id: comment id (primary key of filescomments)
         */
        var parameters = {
            ot : options.ot,
            oid: options.oid,
            fileId: options.file_id,
            id: options.id,
            comment: $("#textarea-" + options.id).val(),
            editSubmit: true
        };
      
        //Checking if the comment has been changed at all
        if (parameters.comment === $("#comment-description-" + options.id).text()) {
            fileStorage.editCommentHide(options.id);
            return;
        }

        $.ajax({
            type: "POST",
            url: options.updateUrl,
            data: parameters,
            success: function(message) {
                commentOnEdit(message, parameters.id, parameters.comment);
            }
        });
    };

    //Function to be called on clicking Cancel under a textarea
    fileStorage.editCommentHide = function (id) {
        $("#comment-edit-delete-" + id).show();
        $("#comment-description-" + id).show();
        $("#edit-comment-" + id).hide();
    };
    
    //Function to be called on successful AJAX request for comment edit
    var commentOnEdit = function (message, id, comment) {
        if (message === "ACTION_COMPLETED_SUCCESSFULLY") {
            $("#comment-description-" + id).html($("<div/>").text(comment).html());
            fileStorage.editCommentHide(id);
        } else {
            generateDialog(message);
        }
    };

    var generateDialog = function (responseMessage) {
        
        var ajaxResponse = "Action unsuccessful",
            notFoundMessage = "Comment does not exist",
            accessDeniedMessage = "Access Denied",
            unknownErrorMessage = "Unknown Error Occurred",
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

})(ATutor.fileStorage);
