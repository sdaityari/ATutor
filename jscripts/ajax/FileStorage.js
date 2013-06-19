/**
 * @author Shaumik Daityari
 * @copyright Copyright © 2013, ATutor, All rights reserved.
 */

var ATutor = ATutor || {};

ATutor.fileStorage = ATutor.fileStorage || {};

ATutor.ajaxFunctions = ATutor.ajaxFunctions || {};

(function(fileStorage, generateDialog) {

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
        $("#textarea-" + id).focus();
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
         * comment: updated value of textarea
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

    //Function called on clicking post in when adding new comment
    fileStorage.addComment = function (options) {

        var options = options || {};

        var defaults = {
            addUrl : "mods/_standard/file_storage/ajax/comments.php",
            textarea : $('#comment')
        };

        options = $.extend({}, defaults, options);

        //In case the comment is empty
        if (options.textarea.val() === ''){
            return;
        }

        /**
         * Sets POST variables to be sent
         * @parameters
         * ot: owner type
         * oid: owner id
         * file_id: file id (primary key of files)
         * comment: comment id (primary key of filescomments)
         */
        var parameters = {
            ot : options.ot,
            oid: options.oid,
            fileId: options.fileId,
            id: options.id,
            comment: options.textarea.val(),
            addSubmit: true
        };

        //Sending AJAX request
        $.ajax({
            type: "POST",
            url: options.addUrl,
            data: parameters,
            success: function(response) {
                commentOnAdd(response, parameters);
            }
        });

        fileStorage.cancelAddComment(options.textarea);
    };

    //Function called on clicking cancel when adding new comment
    fileStorage.cancelAddComment = function (textarea){
        var textarea = textarea || $("#comment");

        //Reseting the text area
        textarea.val('');
    };

    var commentOnAdd = function (response, parameters) {
        var parsedResponse = $.parseJSON(response);

        //Checking if there were any errors
        if (parsedResponse.message !== 'ACTION_COMPLETED_SUCCESSFULLY') {
            generateDialog(parsedResponse.message);
            return;
        }

        var addCommentDom = $("<div />", {
                "class": "input-form",
                id: "comment" + parsedResponse.id
                }).insertBefore($('#comment-add-form'));

        //adding wrapper for comment
        var addCommentRow = $("<div />", {
                "class": "row"
                }).appendTo(addCommentDom);

        //adding comment heading
        $("<h4 />", {
                text: parsedResponse.name + " - " + parsedResponse.date
                }).appendTo(addCommentRow);

        //adding comment description
        $("<p />", {
                html: parsedResponse.comment,
                id: "comment-description-" + parsedResponse.id
                }).appendTo(addCommentRow);

        //Setting new parameters for using in functions to edit and delete comments
        var newParameters = {
            ot : parameters.ot,
            oid : parameters.oid,
            fileId : parameters.fileId,
            id : parsedResponse.id
        };

        //Setting variables to be used below
        var alignRightStyle = "text-align:right; font-size: smaller", //for edit/delete/cancel buttons
            separator = " | ",
            hrefText = "javascript:(null);";

        //Wrapper for Edit comment textarea and buttons
        var editCommentDiv = $("<div />", {
                style : "width:100%; display:none;",
                id : "edit-comment-" + parsedResponse.id
                }).appendTo(addCommentRow);

        //Edit comment textarea
        $("<textarea />", {
                text : parameters.comment,
                id : "textarea-" + parsedResponse.id
                }).appendTo(editCommentDiv);

        //wrapper for buttons below edit textarea
        var editButtons = $("<div />", {
                style : alignRightStyle,
                text : separator
                }).appendTo(editCommentDiv);

        //Submit Edits button prepended to wrapper div
        $("<a />", {
                text : "Submit",
                href : hrefText,
                onclick : "ATutor.fileStorage.editCommentSubmit(" +
                    JSON.stringify(newParameters) + ");"
            }).prependTo(editButtons);

        //Cancel button appended to wrapper div
        $("<a />", {
                text : "Cancel",
                href : hrefText,
                onclick : "ATutor.fileStorage.editCommentHide('" + parsedResponse.id + "');"
            }).appendTo(editButtons);

        //Wrapper for Edit and Delete buttons which are shown initially
        var editDeleteButtons = $("<div />", {
                style : alignRightStyle,
                text : separator,
                id : "comment-edit-delete-" + parsedResponse.id
                }).appendTo(addCommentRow);

        //Delete comment button appended to wrapper div
        $("<a />", {
                text : "Delete",
                href : hrefText,
                onclick : "ATutor.fileStorage.deleteComment(" +
                    JSON.stringify(newParameters) + ");"
            }).appendTo(editDeleteButtons);

        //Edit button prepended to wrapper div
        $("<a />", {
                text : "Edit",
                href : hrefText,
                onclick : "ATutor.fileStorage.editCommentShow('" + parsedResponse.id + "');"
            }).prependTo(editDeleteButtons);

    };

})(ATutor.fileStorage, ATutor.ajaxFunctions.generateDialog);
