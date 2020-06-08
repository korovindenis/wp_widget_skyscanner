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
        jQuery('.skyscanner-widget-container').html(settingsJs.wdgt);
    }
    jQuery("#success_msg").hide();
    jQuery("#failed_msg").hide();
    jQuery("[class^=BpkText_bpk]").empty();
    jQuery("[class*=codeContainer]").hide();
    jQuery("div[class^=BpkRadio_bpk-radio").hide();
    jQuery("[class^=ExtraActionsBlock_ExtraActionsBlock]").empty();
    jQuery(".loader").hide();
    jQuery("#main_container").removeClass("off_data");
}

/**
 * After load page:
 *   1. Get widget-html from database
 *   2. Show content on page
 */
document.body.addEventListener("DOMSubtreeModified", function () {
    if (jQuery("[class^=PoweredBySkyscanner]").is(":visible") && hideLoader != true) {
        hideLoader = true;

        jQuery.ajax({
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
jQuery("button").on("click", function () {
    var idBoxMsg = "#success_msg";
    var showTime = 3000;
    var currentBtn = this;
    var btnId = jQuery(currentBtn).attr("id");
    var sendData = (btnId == "save_button") ? jQuery(".bpk-code").text() : "NULL";

    jQuery(currentBtn).addClass("disabled");
    jQuery.ajax({
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
            jQuery(idBoxMsg).show();
            setTimeout(() => {
                jQuery(idBoxMsg).hide();
                jQuery(currentBtn).removeClass("disabled");
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