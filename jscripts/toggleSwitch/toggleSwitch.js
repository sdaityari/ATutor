/*global jQuery*/

// @author: Shaumik Daityari

// This function would change all radio buttons under selected class into toggle switches
var inputs;
var createToggleSwitch = function (options) {
    var toggleSwitchClass = "switch",
        toggleTheme = options.theme || "ios",
        emptySpanClass = "slide-button",
        color = options.color || "yellow",
        toggleSwitches = $("." + options.className);

    $.each(toggleSwitches, function (index, toggleSwitch) {
        var toggleSwitch = $(toggleSwitch),
            inputs = toggleSwitch.find("input"),
            disabledSwitchClass = "disabled-switch",
            grayscaleSwitchClass = "grayscale-switch";

        $("<span />",{
            "class": emptySpanClass
        }).appendTo(toggleSwitch);

        toggleSwitch.click(function () {
            var anchor = inputs[0].checked ? inputs[1] : inputs[0];

            if (inputs[0].disabled || inputs[1].disabled) {
                return;
            }

            $(anchor).prop("checked", true);

            changeLookOfRadioButtons(inputs, toggleSwitch);
        });

        toggleSwitch.keyup( function(event) {
            var anchor;

            if (event.keyCode === 37) {
                anchor = inputs[0];
            } else if (event.keyCode === 39) {
                anchor = inputs[1];
            } else {
                return;
            }

            if (inputs[0].disabled || inputs[1].disabled) {
                return;
            }

            event.preventDefault();

            $(anchor).prop("checked", true);

            changeLookOfRadioButtons(inputs, toggleSwitch);
        });

        var changeLookOfRadioButtons = function (inputs, toggleSwitch) {

            toggleSwitch.removeClass(disabledSwitchClass);
            toggleSwitch.removeClass(grayscaleSwitchClass);

            if (inputs[0].disabled || inputs[1].disabled) {
                toggleSwitch.addClass(disabledSwitchClass);
            }

            if (inputs[1].checked) {
                toggleSwitch.addClass(grayscaleSwitchClass);
            }
        };

        changeLookOfRadioButtons(inputs, toggleSwitch);

    });
    toggleSwitches.addClass(toggleSwitchClass + " " + toggleTheme + " " + color);

};
