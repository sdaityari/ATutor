/*global jQuery*/

// @author: Shaumik Daityari

// This function would change all radio buttons under selected class into toggle switches

var createToggleSwitch = function (options) {
    var toggleSwitchClass = "switch",
        toggleTheme = options.theme || "ios",
        emptySpanClass = "slide-button",
        color = options.color || "yellow",
        toggleSwitches = $("." + options.className);

    $.each(toggleSwitches, function (index, toggleSwitch) {
        var toggleSwitch = $(toggleSwitch);

        $("<span />",{
            "class": emptySpanClass
        }).appendTo(toggleSwitch);

        toggleSwitch.click(function () {
            var inputs = toggleSwitch.find("input"),
                anchor = inputs[0].checked ? inputs[1] : inputs[0];

            $(anchor).prop("checked", true);
        });

        toggleSwitch.keyup( function(event) {
            var inputs = toggleSwitch.find("input"),
                anchor;

            if (event.keyCode === 37) {
                anchor = inputs[0];
            } else if (event.keyCode === 39) {
                anchor = inputs[1];
            } else {
                return;
            }

            event.preventDefault();

            $(anchor).prop("checked", true);
        });
    });

    toggleSwitches.addClass(toggleSwitchClass + " " + toggleTheme + " " + color);

};
