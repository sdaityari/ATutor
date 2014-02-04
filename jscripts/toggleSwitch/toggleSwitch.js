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
            labels = toggleSwitch.find("label"),
            disabledSwitchClass = "disabled-switch",
            grayscaleSwitchClass = "grayscale-switch",
            onText = "ON",
            offText = "OFF",
            rightLabelClass = "right-label",
            leftLabelClass = "left-label",
            enableInput, disableInput;

        if (inputs.length !== 2 || labels.length !== 2) {
            return false;
        } else {
            enableInput = inputs[0];
            disableInput = inputs[1];
            labels[0].innerHTML = offText;
            labels[0].className = leftLabelClass;
            labels[1].innerHTML = onText;
            labels[1].className = rightLabelClass;
        }

        $("<span />",{
            "class": emptySpanClass
        }).appendTo(toggleSwitch);

        toggleSwitch.click(function (event) {
            var anchor = enableInput.checked ? disableInput : enableInput;

            changeRadioButtonSelection(event, anchor, enableInput, disableInput);
            changeLookOfRadioButtons(inputs, toggleSwitch);
        });

        toggleSwitch.keyup( function(event) {
            var anchor;

            if (event.keyCode === 37) {
                anchor = enableInput;
            } else if (event.keyCode === 39) {
                anchor = disableInput;
            } else {
                return;
            }

            event.preventDefault();
            changeRadioButtonSelection(event, anchor, enableInput, disableInput);
            changeLookOfRadioButtons(inputs, toggleSwitch);
        });

        //changes the selected radio button
        var changeRadioButtonSelection = function (event, anchor, enable, disable) {
            if (enable.disabled || disable.disabled) {
                return;
            }
            $(anchor).prop("checked", true);
        };

        // Adds grayscale class if switch is off, makes it translucent if disabled
        var changeLookOfRadioButtons = function (inputs, toggleSwitch) {

            toggleSwitch.removeClass(disabledSwitchClass + " " + grayscaleSwitchClass);

            // inputs[0] - EnableInput, inputs[1] - disableInput
            // the check for inputs.length !== 2 is implemented above

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
