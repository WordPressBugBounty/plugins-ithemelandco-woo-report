"use strict";

jQuery(document).ready(function ($) {
    // Handle Allow & Continue button
    $(document).on("click", ".ithemeland-allow-continue", function (e) {
        var $btn = $(this);
        $btn.addClass("ithemeland-loading").prop("disabled", true);

        var optIn = $("#ithemeland_opt_in").prop("checked") ? 1 : 0;
        var usageTracking = document.getElementById("ithemeland_usage_track").checked ? 1 : 0;

        $.ajax({
            url: ithemeland_onboarding.ajaxurl,
            type: "POST",
            data: {
                action: "itwrl_ithemeland_onboarding_plugin",
                activation_type: "allow",
                ithemeland_opt_in: optIn,
                ithemeland_usage_track: usageTracking,
                _wpnonce: ithemeland_onboarding.nonce,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    window.location.href = response.data.redirect;
                } else {
                    swal("Error", response.data.message || "Error in activation", "error");
                }
            },
            error: function (xhr) {
                swal("Server Error", "error");
            },
            complete: function () {
                $btn.removeClass("ithemeland-loading").prop("disabled", false);
            },
        });
    });

    // Handle Skip button
    $(document).on("click", ".ithemeland-skip", function (e) {
        var $btn = $(this);
        $btn.addClass("ithemeland-loading").prop("disabled", true);

        $.ajax({
            url: ithemeland_onboarding.ajaxurl,
            type: "POST",
            data: {
                action: "itwrl_ithemeland_onboarding_plugin",
                activation_type: "skip",
                _wpnonce: ithemeland_onboarding.nonce,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    window.location.href = response.data.redirect;
                } else {
                    swal("Error", response.data.message || "Error in skipping", "error");
                }
            },
            error: function (xhr) {
                swal("Server Error", "error");
            },
            complete: function () {
                $btn.removeClass("ithemeland-loading").prop("disabled", false);
            },
        });
    });
});
