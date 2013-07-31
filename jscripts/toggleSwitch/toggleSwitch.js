/*global jQuery*/

// @author: Shaumik Daityari

// This function would change all radio buttons under selected class into toggle switches

var createToggleSwitch = function (options) {
    var toggleClass = "switch",
        toggleTheme = options.theme || "candy",
        emptySpanClass = "slide-button",
        toggleColor = options.color || "",
        toggleWidth = options.width || "200px",
        toggleSwitches = $("." + options.className);
 
    toggleSwitches.attr("style", "width: " + toggleWidth + ";");
    toggleSwitches.addClass(toggleClass + " " + toggleTheme + " " + toggleColor);

    $.each(toggleSwitches, function (index, toggleSwitch) {
        $("<span />",{
            "class": emptySpanClass
        }).appendTo(toggleSwitch);
    });
};
