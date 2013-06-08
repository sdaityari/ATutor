/**
 * @author Shaumik Daityari
 * @copyright Copyright © 2013, ATutor, All rights reserved.
 */

//Function to make ajax calls and return the responses
var ajaxRequest = function (url, parameters, callback) {

    $.ajax({
        type: "POST",
        url: url,
        data: parameters,
        success: function(message) {
            callback(message, parameters);
        }
    });
}
