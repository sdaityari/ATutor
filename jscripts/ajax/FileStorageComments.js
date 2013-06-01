/**
 * @author Shaumik Daityari
 * @copyright Copyright © 2013, ATutor, All rights reserved.
 */

var deleteMessage = "Are you sure you want to delete this comment?";
var deleteTitle = "Delete Comment";
var deleteUrl = "mods/_standard/file_storage/ajax/delete_comment.php";

var confirmDelete = function (ot, oid, file_id, id) {
    if (confirm (deleteMessage)) {
        //AJAX Request to ajax/delete_comment.php
        alert('AJAX REQUEST');
    }
};
