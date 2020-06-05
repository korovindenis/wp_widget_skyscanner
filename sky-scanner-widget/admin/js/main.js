/*-----------------------------------------------------------------------------------

 Plugin Name: Sky Scanner widget
 Plugin URI: https://codecanyon.net/user/skyengineers/
 Description: Simple Flight Search Widget
 Author: SkyEngineers
 Author URI: https://codecanyon.net/user/skyengineers/
 Version: 1.0

-----------------------------------------------------------------------------------*/
"use strict";
var hideLoader = false;

/**
 * Show content
 */
function setDefaultSettings(settingsJs) {
    if (settingsJs.default != true) {
        $('.skyscanner-widget-container').html(settingsJs.wdgt);
    }
    $("#success_msg").hide();
    $("#failed_msg").hide();
    $("[class^=FormRow_FormRow]").first().empty();
    $("[class^=BpkText_bpk]").empty();
    $("[class*=codeContainer]").hide();
    $("div[class^=BpkRadio_bpk-radio").hide();
    $("[class^=ExtraActionsBlock_ExtraActionsBlock]").empty();
    $(".loader").hide();
    $("#main_container").removeClass("off_data");
}

/**
 * After load page:
 *   1. Get widget-html from database
 *   2. Show content on page
 */
document.body.addEventListener("DOMSubtreeModified", function () {
    if ($("[class^=PoweredBySkyscanner]").is(":visible") && hideLoader != true) {
        hideLoader = true;

        $.ajax({
            type: "GET",
            url: pluginParams.restApiUrl + "WidgetFormSettings/",
            beforeSend: function (e) {
                e.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
            }
        }).done(function (e) {
            setDefaultSettings(e);
        }).fail(function (data, textStatus, xhr) {
            console.log("Error WP-RestApi: ", data.status, " ", xhr);
        });
    }
}, false);

/**
 * Event-click for button:
 *    1. save_button
 *    2. reload_button
 */
$("button").on("click", function () {
    var idBoxMsg = "#success_msg";
    var showTime = 3000;
    var currentBtn = this;
    var btnId = $(currentBtn).attr("id");
    var sendData = (btnId == "save_button") ? $(".bpk-code").text() : "NULL";

    $(currentBtn).addClass("disabled");
    $.ajax({
        type: "POST",
        url: pluginParams.restApiUrl + "WidgetFormSettings/",
        data: JSON.stringify({
            wdgt: sendData
        }),
        dataType: "json",
        beforeSend: function (e) {
            e.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
        },
        complete: function () {
            $(idBoxMsg).show();
            setTimeout(() => {
                $(idBoxMsg).hide();
                $(currentBtn).removeClass("disabled");
            }, showTime);
            if (btnId == "reset_button") {
                location.reload(true);
            }
        }
    }).fail(function (data, textStatus, xhr) {
        idBoxMsg = "#failed_msg";
        showTime = 10000;
    });
});