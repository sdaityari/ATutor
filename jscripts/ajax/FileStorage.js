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
        //Asks for confirmation from user, returns if user cancels
        if (!confirm (deleteMessage)) {
            return;
        }

        //Sets POST variables to be sent
        var parameters = {
            "ot" : ot,
            "oid": oid,
            "file_id": file_id,
            "id": id,
            "submit_yes": true
        };

        //AJAX Request sent to required URL
        $.ajax({
            type: "POST",
            url: deleteUrl,
            data: parameters,
            success: function(message) {
                commentOnDelete(message, parameters);
            }
        });
    };

    //Callback function for AJAX Request
    var commentOnDelete = function (responseMessage, parameters) {
        if (responseMessage === "ACTION_COMPLETED_SUCCESSFULLY") {
            $("#comment" + parameters.id).fadeOut();
        } else if (responseMessage === "ACCESS_DENIED") {
            alert ("Access denied");
        } else {
            alert ("Action not completed. Unknown Error Occurred.");
        }
    };

})(ATutor.fileStorage);
