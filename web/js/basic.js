jQuery.fn.load = function(callback){ $(window).on("load", callback) };

var csrfParam = 0;
var csrfToken = 0;
var this_host = '';
var output = new Object();

$(function(){
    this_host = window.location.protocol + "//" + window.location.hostname;
    csrfParam = $('meta[name="csrf-param"]').attr("content");
    csrfToken = $('meta[name="csrf-token"]').attr("content");
});

function nl2br(str) {
    return str.replace(/([^>])\n/g, '$1<br/>');
}

function message(text,timer) {
    $(".message_form_text").text('');
    $(".message_text").html(text);
    $("#message_form").modal().show();
    if (timer >= 0 || !timer ) {
        if (!timer) timer = 1000;
        setTimeout(function(){
            $('#message_form').modal('hide');
        },timer);
    }
}