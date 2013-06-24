/**
 * @author Shaumik Daityari
 * @copyright Copyright © 2013, ATutor, All rights reserved.
 */

var ATutor = ATutor || {};

ATutor.glossary = ATutor.glossary || {};

ATutor.ajaxFunctions = ATutor.ajaxFunctions || {};

(function(glossary, ajaxFunctions) {

    "use strict";

    glossary.editItemFlag = false;

    // Function to be called on clicking Delete for a thread or a reply
    glossary.deleteItem = function () {

        var options = {
            deleteMessage : "Are you sure you want to delete this glossary item?",
            deleteTitle : "Delete Glossary Item",
            deleteUrl : "mods/_core/glossary/tools/ajax/items.php",
            deleteId : "comment-delete-dialog",
            gid: $("input[name=word_id]:checked", "#words-form").val()
        };

        // Checking if none of the radio buttons are checked.
        if (!options.gid) {
            return;
        }

        var parameters = {
            gid : options.gid,
            deleteSubmit : true
        };

        // Setting button options
        var buttonOptions = {
            "Delete":  function (){
                        $.ajax({
                            type: "post",
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

        if (message !== ajaxFunctions.successfulCode) {
            ajaxFunctions.generateDialog(message);
            return;
        }

        $("#r_" + parameters.gid).remove();

    };

    glossary.showForm = function (options) {
        $("#glossary-form").show();
        $("#glossary-terms").hide();

        // Populate/Update the select option in the form
        updateSelect();

        options = options || {};

        if (options.related) {
            options.id = getWordId(options.related);
        }

        $("#glossary-form-name").val(options.name).focus();
        $("#glossary-form-definition").val(options.definition);
        $("#glossary-form-related").val(options.id);
    };

    glossary.confirmSubmit = function () {
        var inputs = $("#glossary-form :input"),
            addUrl = "mods/_core/glossary/tools/ajax/items.php";

        // Title, Definition, Related Term and two Submit Buttons
        if ( inputs.length !== 5) {
            return;
        }

        var parameters = {
            word : inputs[0].value,
            definition : inputs[1].value,
            relatedTerm : inputs[2].value,
        }

        if (!parameters.word || !parameters.definition) {
            return;
        }

        if (!glossary.editItemFlag) {
            parameters.addSubmit = true;
        } else {
            parameters.gid = glossary.editItemFlag;
            parameters.editSubmit = true;
        }

        $.ajax({
            type: "post",
            url: addUrl,
            dataType: "json",
            data: parameters,
            success: function(response) {
                itemOnAdd(response, parameters);
            }
        });
    };

    var itemOnAdd = function (parsedResponse, parameters) {

        if (parsedResponse.message !== ajaxFunctions.successfulCode) {
            var messages = {
                TERM_EXISTS : "The term that you added already exists"
            };
            ajaxFunctions.generateDialog(parsedResponse.message, messages);
            glossary.hideForm();
            return;
        }

        if (glossary.editItemFlag) {
            //Removing old item
            $('#r_'+glossary.editItemFlag).remove();
        }
        addItemToTable($.extend({}, parameters, parsedResponse));
        glossary.hideForm();
    };

    var addItemToTable = function (options) {

        var elements = $("tr[id^='r_']"),
            length = elements.length,
            onMouseDownString = "document.form['m" + options.id +
                "'].checked = true; rowselect(this);",
            anchorTr, element, elementLabel;

        for (var i = 0; i < length; i+=1) {
            element = elements[i];
            elementLabel = $(element).find("label");

            if (elementLabel.length === 0) {
                break;
            }
            if (options.word < elementLabel[0].innerHTML) {
                anchorTr = $(element);
                break;
            }
        }

        if (anchorTr) {
            var tr = $("<tr />",{
                id : "r_" + options.id,
                onmousedown : onMouseDownString
            }).insertBefore(anchorTr);
        } else {
            var tr = $("<tr />",{
                id : "r_" + options.id,
                onmousedown : onMouseDownString
            }).appendTo($("tbody"));
        }

        var td = $("<td />", {
            valign : "top",
            width : "10"
        }).appendTo(tr);

        $("<input />", {
            type : "radio",
            name : "word_id",
            value : options.id,
            id : "m" + options.id
        }).appendTo(td);

        var labelTd = $("<td />", {
            valign : "top"
        }).appendTo(tr);

        $("<label />", {
            "for" : "m" + options.id,
            text : options.word
        }).appendTo(labelTd);

        $("<td />", {
            style : "whitespace:nowrap;",
            text : options.definition
        }).appendTo(tr);

        $("<td />", {
            valign : "top",
            text : options.related
        }).appendTo(tr);
    };

    glossary.hideForm = function () {
        $("#glossary-form").hide();
        $("#glossary-terms").show();
        glossary.editItemFlag = false;
    };

    var updateSelect = function () {
        var tableRows = $("tr[id^='r_']"),
            selectElement = $("#glossary-form-related"),
            length = tableRows.length,
            row, rowInput, rowLabel;

        selectElement.html("");

        $("<option />", {
            value : 0,
        }).appendTo(selectElement);

        for (var i=0; i < length; i+=1) {
            row = tableRows[i];
            rowInput = $(row).find("input");
            rowLabel = $(row).find("label");

            if (rowInput.length !== 1 || rowLabel.length !== 1) {
                break;
            }

            $("<option />", {
                value : rowInput[0].value,
                text : rowLabel[0].innerHTML
            }).appendTo(selectElement);
        }
    };

    glossary.editItem = function () {
        var id = $("input[name=word_id]:checked", "#words-form").val();

        if (!id) {
            return;
        }

        glossary.editItemFlag = id;

        var row = $("#r_" + id).find("td");

        if (row.length !== 4) {
            return;
        }

        var options = {
            name : $(row[1]).find("label")[0].innerHTML,
            definition : row[2].innerHTML,
            related : row[3].innerHTML
        };

        glossary.showForm(options);

    };

    var getWordId = function (word) {
        var elements = $("tr[id^='r_']"),
            length = elements.length;

        for (var i=0; i<length; i++) {
            if (word === $(elements[i]).find("label")[0].innerHTML) {
                return $(elements[i]).find("input")[0].value;
            }
        }
        return 0;
    };

})(ATutor.glossary, ATutor.ajaxFunctions);
