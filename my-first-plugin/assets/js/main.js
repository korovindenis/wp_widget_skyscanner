var hideLoader = false;

function setDefaultSettings(settingsJs){
    if(settingsJs.default != true){
        $('.skyscanner-widget-container').html(settingsJs.wdgt);
    }
    showContent();
}
function showContent(){
    $("#success_msg").hide();
    $("#failed_msg").hide();
    $("[class^=FormRow_FormRow]").first().empty();
    $("[class^=BpkText_bpk]").empty();
    $("[class*=codeContainer]").hide();
    $("[class^=ExtraActionsBlock_ExtraActionsBlock]").empty();
    $(".loader").hide();
    $("#main_container").removeClass("off_data");
}
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

$("button").on( "click", function() {
    var id_box_msg = "#success_msg";
    var show_time = 3000;
    var btnId = $(this).attr("id");
    var sendData = (btnId == "save_button") ? $(".bpk-code").text() : "NULL";

    $(this).addClass("disabled");
    $.ajax({
            type: "POST",
            url: pluginParams.restApiUrl + "WidgetFormSettings/",
            data: JSON.stringify({wdgt : sendData }),
            dataType: "json",
            beforeSend: function (e) {
                e.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce)
            },
            complete: function(){
                $(id_box_msg).show();
                setTimeout(() => {  $(id_box_msg).hide(); $(".save_button").removeClass("disabled"); }, show_time);
                if (btnId == "reset_button"){
                    location.reload(true);
                }
            }
        }).fail(function (data, textStatus, xhr) {
            id_box_msg = "#failed_msg";
            show_time = 10000;
        });
});
