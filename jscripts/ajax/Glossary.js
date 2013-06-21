/**
 * @author Shaumik Daityari
 * @copyright Copyright © 2013, ATutor, All rights reserved.
 */

var ATutor = ATutor || {};

ATutor.glossary = ATutor.glossary || {};

ATutor.ajaxFunctions = ATutor.ajaxFunctions || {};

(function(glossary, ajaxFunctions) {

    "use strict";

    //Function to be called on clicking Delete for a thread or a reply
    glossary.deleteItem = function () {

        var options = {
            deleteMessage : "Are you sure you want to delete this glossary item?",
            deleteTitle : "Delete Glossary Item",
            deleteUrl : "mods/_core/glossary/tools/ajax/items.php",
            deleteId : "comment-delete-dialog",
            gid: $('input[name=word_id]:checked', '#words-form').val()
        };

        //Checking if none of the radio buttons are checked.
        if (!options.gid) {
            return;
        }

        var parameters = {
            gid : options.gid,
            deleteSubmit : true
        };

        //Setting button options
        var buttonOptions = {
            "Delete":  function (){
                        $.ajax({
                            type: "POST",
                            url: options.deleteUrl,
                            data: parameters,
                            success: function(message) {
                                itemOnDelete(message, parameters);
                            }
                        });
                        deleteDialog.dialog("close");
                    },
            "Cancel" :  function () {
                        deleteDialog.dialog("close");
                    }
        };

        // Create dialog for confirmation
        var deleteDialog = $("<div />", {
                                        title: options.deleteTitle,
                                        text: options.deleteMessage,
                                        id: options.deleteId
                                    }).appendTo($("body"));


        deleteDialog.dialog({
            autoOpen: true,
            width: 400,
            modal: true,
            closeOnEscape: false,
            buttons: buttonOptions
        });

    };

    var itemOnDelete = function (message, parameters) {

        if (message !== "ACTION_COMPLETED_SUCCESSFULLY") {
            ajaxFunctions.generateDialog(message);
            return;
        }

        $("#r_" + parameters.gid).fadeOut();

    };

})(ATutor.glossary, ATutor.ajaxFunctions);
