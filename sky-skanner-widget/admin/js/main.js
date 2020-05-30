/*-----------------------------------------------------------------------------------

 Plugin Name: Sky Scanner widget
 Plugin URI: 
 Description: Simple Flight Search Widget
 Author: 
 Author URI: 
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
    $("[class^=ExtraActionsBlock_ExtraActionsBlock]").empty();
    $(".loader").hide();
    $("#main_container").removeClass("off_data");
}

/**
 * After load page:
 *   Get widget-html from database
 *   Show content on page
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
 *    save_button
 *    reload_button
 */
$("button").on("click", function () {
    var idBoxMsg = "#success_msg";
    var showTime = 3000;
    var btnId = $(this).attr("id");
    var sendData = (btnId == "save_button") ? $(".bpk-code").text() : "NULL";

    $(this).addClass("disabled");
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
                $(".save_button").removeClass("disabled");
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