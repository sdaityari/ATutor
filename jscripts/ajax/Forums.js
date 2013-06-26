/**
 * @author Shaumik Daityari
 * @copyright Copyright © 2013, ATutor, All rights reserved.
 */

var ATutor = ATutor || {};

ATutor.forums = ATutor.forums || {};

ATutor.ajaxFunctions = ATutor.ajaxFunctions || {};

(function(forums, ajaxFunctions) {

    "use strict";

    var css = {
        postId : "post-"
    };

    //Function to be called on clicking Delete for a thread or a reply
    forums.deleteThread = function (options) {

        var defaults = {
            deleteMessageReply : "Are you sure you want to delete this reply?",
            deleteMessageThread : "Are you sure you want to delete this thread?",
            deleteTitleReply : "Delete Reply",
            deleteTitleThread : "Delete Thread",
            deleteUrl : "mods/_standard/forums/ajax/threads.php",
            deleteId : "comment-delete-dialog"
        };

        options = options || {};

        options = $.extend({}, defaults, options);

        /**
         * Sets POST variables to be sent
         * @parameters
         * pid: post id
         * ppid: parent post id, 0 if thread
         * fid: forum id
         */
        var parameters = {
            pid : options.pid,
            ppid : options.ppid,
            fid : options.fid,
            deleteSubmit : true
        };

        //Setting title and message for dialog box according to thread/reply
        if (options.ppid === "0") {
            options.deleteTitle = options.deleteTitleThread;
            options.deleteMessage = options.deleteMessageThread;
        } else {
            options.deleteTitle = options.deleteTitleReply;
            options.deleteMessage = options.deleteMessageReply;
        }

        // Create dialog for confirmation
        var deleteDialog = $("<div />", {
                                        title: options.deleteTitle,
                                        text: options.deleteMessage,
                                        id: options.deleteId
                                    }).appendTo($("body"));

        //Setting button options
        var buttonOptions = {
            "Delete":  function (){
                        $.ajax({
                            type: "POST",
                            url: options.deleteUrl,
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

        deleteDialog.dialog({
            autoOpen: true,
            width: 400,
            modal: true,
            closeOnEscape: false,
            buttons: buttonOptions
        });

    };

    var commentOnDelete = function (message, parameters) {
        var redirectUrl = "mods/_standard/forums/forum/index.php?fid=" + parameters.fid;

        if (message !== ajaxFunctions.successfulCode) {
            ajaxFunctions.generateDialog(message);
            return;
        }

        if (parameters.ppid === "0") {
            window.location.href = redirectUrl;
            return;
        }
        //removing comment
        $("#" + css.postId + parameters.pid).remove();

        rearrangeElements();
    };

    //function to change css class of existing thread and replies
    var rearrangeElements = function () {
        //changing css class of existing posts
        var elements = $("li[id^='" + css.postId + "']");
        for (var i = 0; i < elements.length; i+=1) {
            elements[i].className = (i % 2 === 0) ? "odd" : "even";
        }
    };

})(ATutor.forums, ATutor.ajaxFunctions);
