/*global jQuery*/

// @author: Shaumik Daityari

// This function would change all radio buttons under selected class into toggle switches

var createToggleSwitch = function (options) {
    var toggleSwitch = "switch",
        toggleTheme = options.theme || "candy",
        emptySpanClass = "slide-button",
        color = options.color || "yellow",
        toggleWidth = options.width || "4em",
        toggleHeight = options.height || "1.3em",
        toggleSwitches = $("." + options.className);

    $.each(toggleSwitches, function (index, toggleSwitch) {
        $("<span />",{
            "class": emptySpanClass
        }).appendTo(toggleSwitch);
        $(toggleSwitch).find("label").hide();
    });

    toggleSwitches.attr("style", "width: " + toggleWidth + "; height: " + toggleHeight + ";");
    toggleSwitches.addClass(toggleSwitch + " " + toggleTheme + " " + color);

    };
