/**
 * @author Shaumik Daityari
 * @copyright Copyright © 2013, ATutor, All rights reserved.
 */

var deleteMessage = "Are you sure you want to delete this comment?";
var deleteTitle = "Delete Comment";
var deleteUrl = "mods/_standard/file_storage/ajax/delete_comment.php";

var deleteComment = function (ot, oid, file_id, id) {
    "use strict";
    if (confirm (deleteMessage)) {
        
        var parameters = {
            'ot' : ot,
            'oid': oid,
            'file_id': file_id,
            'id': id,
            'submit_yes': true
        }

        $.ajax({
            type: "POST",
            url: deleteUrl,
            data: parameters,
            success: function(message){
                if (message === 'ACTION_COMPLETED_SUCCESSFULLY') {
                    $('#comment'+id).fadeOut();
                } else if (message === 'ACCESS_DENIED') {
                    alert ('Access denied');
                } else {
                    alert ('Action not completed. Unknown Error Occurred.');
                }
            }
        });
    }
};
