define([ "jquery", "plugins/validate", "plugins/maskedinput", "plugins/pickadate", "plugins/jquery.scrollTo" ], function($, validate, maskedinput) {
    function formHelpers() {
        toggleInput();
        toggleElement();
        $(document).ready(function() {
            enableAutosubmit();
            enablePolyfills($(document.body));
            validateForm($(document.body));
            maskPhoneInput();
            maskTipInput();
            errorHelpers();
            disableSelect("pizza-size", "pizza-crust-thin");
            $("[id*=show-password]").on("click", showPassword);
        });
        $(document).on("freight.afterReplacement", function(e) {
            validateForm($(e.target));
            enablePolyfills($(e.target));
            $(e.target).filter("[data-freight-scrollto]").each(function() {
                if ($(this).offset().top - $(window).scrollTop() < 0) {
                    $.scrollTo($(this), 0, {
                        offset: -16
                    });
                }
            });
        });
        function disableSelect(groupName, targetVal) {
            var group = $('[name="' + groupName + '"]'), targetName = group.attr("data-form-disable"), disableSrc = "data-form-disable", disableTrgt = "data-form-disable-target";
            $(group).click(function() {
                if ($(this).attr(disableSrc) && $(this).is(":checked")) {
                    $("[" + disableTrgt + '="' + targetName + '"]').find('[value="' + targetVal + '"]').attr("disabled", "disabled");
                    $("[" + disableTrgt + '="' + targetName + '"]').val("pizza-crust-original");
                } else {
                    $("[" + disableTrgt + '="' + targetName + '"]').find('[value="' + targetVal + '"]').removeAttr("disabled");
                }
            });
            $("[" + disableTrgt + '="' + targetName + '"]').change(function() {
                if ($(this).val() === "pizza-crust-thin") {
                    $("input#size-large").prop("checked", true);
                }
            });
        }
        function enableAutosubmit() {
            $(document).off("change.autosubmit");
            $(document).on("change.autosubmit", "select.autosubmit", function(e) {
                $(e.currentTarget).closest("form").submit();
                e.preventDefault();
            });
        }
        function enablePolyfills(root) {
            if (!Modernizr.inputtypes.date) {
                $(root).find('input[type="date"]').each(function() {
                    var options = {
                        format: "mm/dd/yyyy"
                    };
                    if ($(this).attr("min") !== undefined && $(this).attr("min") !== "") {
                        var minSplit = $(this).attr("min").split("-");
                        options.min = new Date(parseInt(minSplit[0]), parseInt(minSplit[1]) - 1, parseInt(minSplit[2]));
                    }
                    if ($(this).attr("max") !== undefined && $(this).attr("max") !== "") {
                        var maxSplit = $(this).attr("max").split("-");
                        options.max = new Date(parseInt(maxSplit[0]), parseInt(maxSplit[1]) - 1, parseInt(maxSplit[2]));
                    }
                    $(this).pickadate(options);
                });
            }
            if (!Modernizr.inputtypes.time) {
                $(root).find('input[type="time"]').each(function() {
                    var options = {};
                    if ($(this).attr("min") !== undefined && $(this).attr("min") !== "") {
                        var minSplit = $(this).attr("min").split(":");
                        options.min = [ parseInt(minSplit[0]), parseInt(minSplit[1]) ];
                    }
                    if ($(this).attr("max") !== undefined && $(this).attr("max") !== "") {
                        var maxSplit = $(this).attr("max").split(":");
                        options.max = [ parseInt(maxSplit[0]), parseInt(maxSplit[1]) ];
                    }
                    if ($(this).attr("step") !== undefined && $(this).attr("step") !== "") {
                        var step = parseInt($(this).attr("step"));
                        options.interval = step / 60;
                    }
                    $(this).pickatime(options);
                });
            }
        }
        function validateForm(scope) {
            var $forms = scope.find("form");
            $forms.each(function() {
                $(this).validate({
                    ignore: '[data-input-hidden], input[type="date"], input[type="time"]',
                    errorPlacement: function(error, element) {
                        if ($(element).is('input[id*="terms"]')) {
                            error.appendTo(element.parent(".input-wrap"));
                        } else {
                            error.insertAfter(element);
                        }
                    },
                    invalidHandler: function(form, validator) {
                        for (var i = 0; i < validator.errorList.length; i++) {
                            var $invalidElement = $(validator.errorList[i].element);
                            $invalidElement.siblings("label").not(".error").addClass("error-strong");
                        }
                    },
                    success: function(label) {
                        label.siblings(".error-strong").removeClass("error-strong");
                    }
                });
            });
            $('input[id*="phone-number"]').each(function() {
                $(this).rules("add", {
                    required: true,
                    phoneUS: true
                });
            });
            $('input[id*="streetaddress"]').each(function() {
                $(this).rules("add", {
                    required: true,
                    street: true
                });
            });
            $('input[id*="aptstefloornumber"]').each(function() {
                $(this).rules("add", {
                    apt: true
                });
            });
            $('input[id*="city"]').each(function() {
                $(this).rules("add", {
                    required: true,
                    city: true
                });
            });
            $('input[id*="zipcode"]').each(function() {
                $(this).rules("add", {
                    required: true,
                    zipcodeUS: true
                });
            });
            $('input[id="create-account-password"]').each(function() {
                $(this).rules("add", {
                    required: true,
                    minlength: 8,
                    maxlength: 25,
                    specialCharacter: true,
                    lowercaseCharacter: true,
                    uppercaseCharacter: true,
                    hasNumber: true,
                    threeConsecutiveCharacters: true
                });
            });
            $('input[id*="credit-card-number"]').each(function() {
                $(this).rules("add", {
                    required: true,
                    creditcardtypes: {
                        all: true
                    }
                });
            });
            $.validator.addMethod("phoneUS", function(phone_number, element) {
                phone_number = phone_number.replace(/\s+/g, "");
                return this.optional(element) || phone_number.length > 9 && phone_number.match(/^(\+?1-?)?(\([2-9]([02-9]\d|1[02-9])\)|[2-9]([02-9]\d|1[02-9]))-?[2-9]([02-9]\d|1[02-9])-?\d{4}$/);
            }, "Please enter a valid phone number.");
            $.validator.addMethod("street", function(street, element) {
                return this.optional(element) || street.match(/^(\[\d+\s*-\s*\d+\]\s+)?[-\w\s\.\'\,\\\/\#]+$/);
            }, "Please use letters, numbers, periods, -, \\, #, and / only.");
            $.validator.addMethod("apt", function(apt, element) {
                return this.optional(element) || apt.match(/^[A-Za-z0-9\-\ \#\,]+$/);
            }, "Please use letters, numbers, commas, # only.");
            $.validator.addMethod("city", function(city, element) {
                return this.optional(element) || city.match(/^[a-zA-Z\-\ \.\']+$/);
            }, "Please use letters, spaces,-, ', . only.");
            $.validator.addMethod("threeConsecutiveCharacters", function(value, element) {
                return this.optional(element) || !value.match(/(.)\1\1/);
            }, "Password can not have 3 of the same characters in a row.");
            $.validator.addMethod("zipcodeUS", function(value, element) {
                return this.optional(element) || /^(\d{9}$|(^\d{5}[-]?(\d{4})?$))|(^[ABCEGHJKLMNPRSTVXYabceghjklmnprstvxy]{1}\d{1}[A-Za-z]{1} *\d{1}[A-Za-z]{1}\d{1})$/.test(value);
            }, "Please enter a valid US zip code.");
            $.validator.addMethod("specialCharacter", function(value, element) {
                return this.optional(element) || specialChar.test(value);
            }, "Password must have at least 1 special character (!@#$&*).");
            $.validator.addMethod("lowercaseCharacter", function(value, element) {
                return this.optional(element) || lowercaseChar.test(value);
            }, "Password must have at least 1 lowercase character.");
            $.validator.addMethod("uppercaseCharacter", function(value, element) {
                return this.optional(element) || uppercaseChar.test(value);
            }, "Password must have at least 1 uppercase character.");
            $.validator.addMethod("hasNumber", function(value, element) {
                return this.optional(element) || numberChar.test(value);
            }, "Password must have at least 1 number.");
            var specialChar = /[!@#$%^&*]/;
            var lowercaseChar = /[a-z]/;
            var uppercaseChar = /[A-Z]/;
            var numberChar = /[0-9]/;
            $.validator.addMethod("creditcardtypes", function(value, element, param) {
                if (/[^0-9\-]+/.test(value)) {
                    return false;
                }
                value = value.replace(/\D/g, "");
                var validTypes = 0;
                if (param.mastercard) {
                    validTypes |= 1;
                }
                if (param.visa) {
                    validTypes |= 2;
                }
                if (param.amex) {
                    validTypes |= 4;
                }
                if (param.dinersclub) {
                    validTypes |= 8;
                }
                if (param.enroute) {
                    validTypes |= 16;
                }
                if (param.discover) {
                    validTypes |= 32;
                }
                if (param.jcb) {
                    validTypes |= 64;
                }
                if (param.unknown) {
                    validTypes |= 128;
                }
                if (param.all) {
                    validTypes = 1 | 2 | 4 | 8 | 16 | 32 | 64 | 128;
                }
                if (validTypes & 1 && /^(5[12345])/.test(value)) {
                    $(element).attr("data-card-type", "mastercard");
                    $(element).closest("fieldset").attr("data-card-type", "mastercard");
                    return value.length === 16;
                }
                if (validTypes & 2 && /^(4)/.test(value)) {
                    $(element).attr("data-card-type", "visa");
                    $(element).closest("fieldset").attr("data-card-type", "visa");
                    return value.length === 16;
                }
                if (validTypes & 4 && /^(3[47])/.test(value)) {
                    $(element).attr("data-card-type", "amex");
                    $(element).closest("fieldset").attr("data-card-type", "amex");
                    return value.length === 15;
                }
                if (validTypes & 8 && /^(3(0[012345]|[68]))/.test(value)) {
                    $(element).attr("data-card-type", "dinersclub");
                    $(element).closest("fieldset").attr("data-card-type", "dinersclub");
                    return value.length === 14;
                }
                if (validTypes & 16 && /^(2(014|149))/.test(value)) {
                    $(element).attr("data-card-type", "enroute");
                    $(element).closest("fieldset").attr("data-card-type", "enroute");
                    return value.length === 15;
                }
                if (validTypes & 32 && /^(6011)/.test(value)) {
                    $(element).attr("data-card-type", "discover");
                    $(element).closest("fieldset").attr("data-card-type", "discover");
                    return value.length === 16;
                }
                if (validTypes & 64 && /^(3)/.test(value)) {
                    $(element).attr("data-card-type", "jcb");
                    $(element).closest("fieldset").attr("data-card-type", "jcb");
                    return value.length === 16;
                }
                if (validTypes & 64 && /^(2131|1800)/.test(value)) {
                    $(element).attr("data-card-type", "jcb");
                    $(element).closest("fieldset").attr("data-card-type", "jcb");
                    return value.length === 15;
                }
                if (validTypes & 128) {
                    $(element).attr("data-card-type", "unknown");
                    $(element).closest("fieldset").attr("data-card-type", "unknown");
                    return true;
                }
                $(element).removeAttr("data-card-type");
                $(element).closest("fieldset").removeAttr("data-card-type");
                return false;
            }, "Please enter a valid credit card number.");
        }
        function maskPhoneInput() {
            $("input[id*=phone-number]").mask("(999) 999-9999");
        }
        function maskTipInput() {
            $("input[id*=tipamount]").mask("$9.99");
        }
        function showPassword(e) {
            e.preventDefault();
            var $this = $(this), $passwordField = $this.closest("form").find('input[id*="password"]');
            if ($passwordField.attr("type") === "password") {
                $passwordField.attr("type", "text");
                $this.text("Hide password");
            } else {
                $passwordField.attr("type", "password");
                $this.text("Show password");
            }
        }
        function toggleElement() {
            $(document).on("click", "[data-toggle-trigger]", toggleVisibility);
            $(document).on("change", 'select[id*="country"]', toggleCountryAndAddressFields);
            $(document).on("change", 'select[id*="addresstype"]', toggleCountryAndAddressFields);
            function toggleVisibility(e) {
                var $this = $(this), $triggerValue = $this.data("toggle-trigger");
                if (!$this.is('input[type="checkbox"]')) {
                    e.preventDefault();
                }
                if (typeof $triggerValue == "string") {
                    var $target = $('[data-toggle-target="' + $triggerValue + '"]');
                    if ($target.is(":input")) {
                        $target.toggleClass("nodisplay");
                        if ($target.is(":visible")) {
                            $target.prop("disabled", false);
                        } else {
                            $target.prop("disabled", true);
                        }
                    } else {
                        $target.toggleClass("nodisplay");
                        if ($target.is(":visible")) {
                            $target.children(":input").prop("disabled", false);
                        } else {
                            $target.children(":input").prop("disabled", true);
                        }
                    }
                } else if (typeof $triggerValue == "object") {
                    for (i in $triggerValue) {
                        var $target = $('[data-toggle-target="' + $triggerValue[i] + '"]');
                        i++;
                        if ($target.is(":input")) {
                            $target.toggleClass("nodisplay");
                            if ($target.is(":visible")) {
                                $target.prop("disabled", false);
                            } else {
                                $target.prop("disabled", true);
                            }
                        } else {
                            var $input = $target.find(":input");
                            $target.toggleClass("nodisplay");
                            if ($target.is(":visible")) {
                                $input.prop("disabled", false);
                            } else {
                                $input.prop("disabled", true);
                            }
                        }
                    }
                }
            }
            function showElement(element, ignoredElement) {
                var input = element.find(":input");
                ignoredElement = ignoredElement || false;
                if (ignoredElement) {
                    element.removeClass("nodisplay");
                } else if (element.is(":input")) {
                    element.removeClass("nodisplay").prop("disabled", false);
                    input = element;
                } else {
                    element.removeClass("nodisplay");
                }
                input.filter(":visible").removeAttr("data-input-hidden");
            }
            function hideElement(element) {
                var input = element.find(":input");
                if (element.is(":input")) {
                    element.addClass("nodisplay").prop("disabled", true);
                    input = element;
                } else {
                    element.addClass("nodisplay");
                }
                input.attr("data-input-hidden", "");
            }
            function toggleCountryAndAddressFields() {
                var $this = $(this), $countryValue = $this.closest("form").find('select[id*="country"]').val(), $formID = $this.closest("form").find('select[id*="country"]').attr("id"), $canadaFields = $("[data-" + $formID + '="canada"]'), $usaFields = $("[data-" + $formID + '="usa"]'), $canadaPostalFields = $this.closest("form").find('[data-dsc-target*="-zipcode-city-state-canada"]'), $usPostalFields = $this.closest("form").find('[data-dsc-target*="-zipcode-city-state-us"]'), $universityFields = $this.closest("form").find('[data-field-type*="addresstype-university-fields"]'), $militaryFields = $this.closest("form").find('[data-field-type*="addresstype-military-fields"]'), $streetAddressFields = $this.closest("form").find('[data-field-type*="-street-address-fields"]'), $addresstype = $this.closest("form").find('select[id*="addresstype"]').val(), $usaMilitaryFields = $militaryFields.find($usaFields), $usaUniversityFields = $universityFields.find($usaFields), $canadaMilitaryFields = $militaryFields.find($canadaFields), $canadaUniversityFields = $universityFields.find($canadaFields);
                $(this).closest("form").find("[data-clearable]").each(function() {
                    $(this).val("");
                    if ($(this).is("select")) {
                        $(this).val($(this).find("option").eq(0).attr("value"));
                    }
                    $(this).removeAttr("checked");
                    $(this).removeAttr("selected");
                    $(this).removeClass("valid error");
                });
                if ($countryValue == "canada" && $addresstype !== "university" && $addresstype !== "military") {
                    hideElement($usaFields);
                    hideElement($militaryFields);
                    hideElement($universityFields);
                    showElement($canadaFields);
                    showElement($canadaPostalFields);
                    showElement($streetAddressFields);
                } else if ($countryValue == "usa" && $addresstype !== "university" && $addresstype !== "military") {
                    hideElement($canadaFields);
                    hideElement($militaryFields);
                    hideElement($universityFields);
                    showElement($usaFields);
                    showElement($usPostalFields);
                    showElement($streetAddressFields);
                } else if ($countryValue == "canada" && $addresstype == "university") {
                    hideElement($usaFields);
                    hideElement($usaUniversityFields);
                    hideElement($militaryFields);
                    hideElement($usPostalFields);
                    hideElement($canadaPostalFields);
                    hideElement($streetAddressFields);
                    showElement($universityFields);
                    showElement($canadaUniversityFields);
                } else if ($countryValue == "canada" && $addresstype == "military") {
                    hideElement($usaFields);
                    hideElement($universityFields);
                    hideElement($usaUniversityFields);
                    hideElement($usPostalFields);
                    hideElement($canadaPostalFields);
                    hideElement($streetAddressFields);
                    showElement($militaryFields);
                    showElement($canadaMilitaryFields);
                } else if ($countryValue == "usa" && $addresstype == "university") {
                    hideElement($canadaFields);
                    hideElement($canadaUniversityFields);
                    hideElement($militaryFields);
                    hideElement($canadaPostalFields);
                    hideElement($usPostalFields);
                    hideElement($streetAddressFields);
                    showElement($universityFields);
                    showElement($usaUniversityFields);
                } else if ($countryValue == "usa" && $addresstype == "military") {
                    hideElement($canadaFields);
                    hideElement($universityFields);
                    hideElement($canadaMilitaryFields);
                    hideElement($canadaPostalFields);
                    hideElement($usPostalFields);
                    hideElement($streetAddressFields);
                    showElement($militaryFields);
                    showElement($usaMilitaryFields);
                }
            }
        }
        function toggleInput() {
            $(document).on("change", '[data-toggle-input="trigger"]', enableInput);
            $('[data-toggle-input="trigger"]').each(function() {
                enableInput({
                    target: $(this)
                });
            });
            $(document).on("freight.afterReplacement", function(e) {
                $('[data-toggle-input="trigger"]').each(function() {
                    enableInput({
                        target: $(this)
                    });
                });
            });
            function enableInput(e) {
                var $this = $(e.target);
                var $disabler = $this.children('[data-toggle-input="disabled"]'), $target = $this.nextAll('[data-toggle-input="target"]');
                if ($this.closest("[data-toggle-input-root]").length > 0) {
                    $target = $this.closest("[data-toggle-input-root]").find('[data-toggle-input="target"]');
                }
                function disablerSelected() {
                    if (!$disabler.length) {
                        return false;
                    } else if ($disabler.is("option") && $disabler.is(":selected")) {
                        return true;
                    } else if ($disabler.is("checkbox") && $disabler.is(":checkbox")) {
                        return true;
                    } else {
                        return false;
                    }
                }
                if (disablerSelected()) {
                    $target.addClass("disabled").attr("disabled", "disabled");
                    $target.val("");
                    $target.removeAttr("checked");
                    $target.removeAttr("selected");
                    $target.removeClass("valid error");
                    $target.filter("[data-toggle-input-disable-parent]").parent().addClass("disabled").attr("disabled", "disabled");
                } else {
                    $target.removeClass("disabled").removeAttr("disabled");
                    $target.filter("[data-toggle-input-disable-parent]").parent().removeClass("disabled").removeAttr("disabled");
                }
            }
            return {
                enableInput: enableInput
            };
        }
        function errorHelpers() {
            function clearError(container) {
                var submitButton = container.find('input[type="submit"]');
                submitButton.val(submitButton.attr("data-original-value"));
                container.removeClass("error").next(".tooltip.error").remove();
                container.find('input[type="text"]').eq(0).focus();
            }
            $("form.error, form .error").each(function() {
                var errorContainer = $(this);
                var submitButton = errorContainer.find('input[type="submit"]');
                submitButton.attr("data-original-value", submitButton.val()).val("X");
            });
            $(document).on("click", 'form.error input[type="submit"], form .error input[type="submit"]', function(e) {
                clearError($(e.target).closest(".error"));
                e.preventDefault();
            });
            $(document).on("focus", 'form.error input[type="text"], form .error input[type="text"]', function(e) {
                clearError($(e.target).closest(".error"));
                e.preventDefault();
            });
        }
    }
    return {
        init: function() {
            formHelpers();
        }
    };
});