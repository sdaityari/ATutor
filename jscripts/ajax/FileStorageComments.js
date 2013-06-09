/**
 * @author Shaumik Daityari
 * @copyright Copyright © 2013, ATutor, All rights reserved.
 */

var ATutor = ATutor || {};

(function() {
    "use strict";

    ATutor.fileStorage = ATutor.fileStorage || {};

    var deleteMessage = "Are you sure you want to delete this comment?",
        deleteTitle = "Delete Comment",
        deleteUrl = "mods/_standard/file_storage/ajax/delete_comment.php";

    ATutor.fileStorage.deleteComment = function (ot, oid, file_id, id) {
        if (!confirm (deleteMessage)) {
            return;
        }
        var parameters = {
            'ot' : ot,
            'oid': oid,
            'file_id': file_id,
            'id': id,
            'submit_yes': true
        };

        $.ajax({
            type: "POST",
            url: deleteUrl,
            data: parameters,
            success: function(message) {
                commentOnDelete(message, parameters);
            }
        });
    };

    var commentOnDelete = function (responseMessage, parameters) {
        if (responseMessage === 'ACTION_COMPLETED_SUCCESSFULLY') {
            $('#comment' + parameters.id).fadeOut();
        } else if (responseMessage === 'ACCESS_DENIED') {
            alert ('Access denied');
        } else {
            alert ('Action not completed. Unknown Error Occurred.');
        }
    };

})();
