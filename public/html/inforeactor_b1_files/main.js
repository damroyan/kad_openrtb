
function close9May() {
    $('.may-iframe').slideUp(700);
    $('body').css('overflow', 'scroll');
    enableScroll();
}

function openLink(url) {
    location.href = url;
}


var keys = {37: 1, 38: 1, 39: 1, 40: 1};

function preventDefault(e) {
    e = e || window.event;
    if (e.preventDefault)
        e.preventDefault();
    e.returnValue = false;
}

function preventDefaultForScrollKeys(e) {
    if (keys[e.keyCode]) {
        preventDefault(e);
        return false;
    }
}

function disableScroll() {
    if (window.addEventListener) // older FF
        window.addEventListener('DOMMouseScroll', preventDefault, false);
    window.onwheel = preventDefault; // modern standard
    window.onmousewheel = document.onmousewheel = preventDefault; // older browsers, IE
    window.ontouchmove  = preventDefault; // mobile
    document.onkeydown  = preventDefaultForScrollKeys;
}

function enableScroll() {
    if (window.removeEventListener)
        window.removeEventListener('DOMMouseScroll', preventDefault, false);
    window.onmousewheel = document.onmousewheel = null;
    window.onwheel = null;
    window.ontouchmove = null;
    document.onkeydown = null;
}


var calScrollPos = 0;
function calScrollRight(el, id)
{
    elId = document.getElementById(id);
    if(calScrollPos>-1530) {
        calScrollPos -= 510;
        elId.style = "width: 2040px; position: relative; transition-duration: 0.5s; transform: translate3d(" + calScrollPos + "px, 0px, 0px);";

        if(calScrollPos==-1530) el.className = 'hideBtn';
    }

    calScrollCheckBtn();
}

function calScrollLeft(el, id)
{
    elId = document.getElementById(id);
    if(calScrollPos<0) {
        calScrollPos += 510;
        elId.style = "width: 2040px; position: relative; transition-duration: 0.5s; transform: translate3d(" + calScrollPos + "px, 0px, 0px);";

        if(calScrollPos==0) el.className = 'hideBtn';
    }
    calScrollCheckBtn();
}

function calScrollCheckBtn()
{
    if(calScrollPos>-1530) document.getElementById('right-btn').className = 'right-btn';
    if(calScrollPos<0) document.getElementById('left-btn').className = 'left-btn';
}

function calScrollImage(id, num)
{
    calScrollPos = num;
    document.getElementById(id).style = "width: 2040px; position: relative; transition-duration: 0.5s; transform: translate3d(" + calScrollPos + "px, 0px, 0px);";

    calScrollCheckBtn();
}

function CalendarList(id, year, month) {
    var Dlast = new Date(year,month+1,0).getDate(),
        D = new Date(year,month,Dlast),
        DNlast = new Date(D.getFullYear(),D.getMonth(),Dlast).getDay(),
        DNfirst = new Date(D.getFullYear(),D.getMonth(),1).getDay(),
        calendar = '<tr>',
        month=["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"];

    if (DNfirst != 0) {
        for(var  i = 1; i < DNfirst; i++) calendar += '<td>&nbsp;</td>';
    }else{
        for(var  i = 0; i < 6; i++) calendar += '<td>&nbsp;</td>';
    }
    for(var  i = 1; i <= Dlast; i++) {
        if (i == new Date().getDate() && D.getFullYear() == new Date().getFullYear() && D.getMonth() == new Date().getMonth()) {
            calendar += '<td class="today" onClick="CalendarDay(this)">' + i + '</td>';
        }else{
            calendar += '<td onClick="CalendarDay(this)">' + i + '</td>';
        }
        if (new Date(D.getFullYear(),D.getMonth(),i).getDay() == 0) {
            calendar += '<tr>';
        }
    };

    for(var  i = DNlast; i < 7; i++) calendar += '<td>&nbsp;</td>';
    calendar += '</tr>';

    document.querySelector('#'+id+' tbody').innerHTML = calendar;
    document.querySelector('#'+id+' thead td:nth-child(2)').innerHTML = month[D.getMonth()];
    document.querySelector('#'+id+' thead td:nth-child(2)').dataset.month = D.getMonth();
    document.querySelector('#'+id+' thead td:nth-child(2)').dataset.year = D.getFullYear();
    if (document.querySelectorAll('#'+id+' tbody tr').length < 6) {
        document.querySelector('#'+id+' tbody').innerHTML += '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
    }

    $('#calendar-day').html(new Date().getDate());
    $('#calendar-month').html(month[new Date().getMonth()]);
}

function formatDate(date)  {
    return [
        date.getFullYear(),
        date.getMonth() + 1,
        date.getDate()
    ].map(function(val){
        return val < 10 ? '0' + val : val;
    }).join('-');
}

function CalendarDay(el) {
    var dateString = formatDate(new Date(
        document.querySelector('#calendar-list thead td:nth-child(2)').dataset.year,
        document.querySelector('#calendar-list thead td:nth-child(2)').dataset.month,
        el.innerHTML
    ));
    var clickDate = Date.parse(dateString);
    var nowDate = Date.now();
    if(nowDate > clickDate) {
        location.href = '/special/calendar/' + dateString;
    }
}

$(document).ready(function(){

    if($('#calendar-main').length) {

        CalendarList("calendar-list", new Date().getFullYear(), new Date().getMonth());

        document.querySelector('#calendar-list thead tr:nth-child(1) td:nth-child(1)').onclick = function () {
            CalendarList("calendar-list", document.querySelector('#calendar-list thead td:nth-child(2)').dataset.year, parseFloat(document.querySelector('#calendar-list thead td:nth-child(2)').dataset.month) - 1);
        };

        document.querySelector('#calendar-list thead tr:nth-child(1) td:nth-child(3)').onclick = function () {
            CalendarList("calendar-list", document.querySelector('#calendar-list thead td:nth-child(2)').dataset.year, parseFloat(document.querySelector('#calendar-list thead td:nth-child(2)').dataset.month) + 1);
        }
    }

    $('#loadMoreButton').click(function() {
        PostsHistory.get();
    });

    // setInterval(checkNeedInstallPlugin, 3000);
});

function in_array(needle, haystack, strict) {	// Checks if a value exists in an array
    //
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)

    var found = false, key, strict = !!strict;

    for (key in haystack) {
        if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle)) {
            found = true;
            break;
        }
    }

    return found;
}

function get_cookie(cookie_name){
    var results = document.cookie.match ( '(^|;) ?' + cookie_name + '=([^;]*)(;|$)' );
    if(results)
        return ( unescape ( results[2] ) );
    else
        return null;
}
function set_cookie(name, value, exp_y, exp_m, exp_d){
    var cookie_string = name + "=" + escape ( value );
    if(exp_y){
        var expires = new Date(exp_y,exp_m,exp_d);
        cookie_string += "; expires=" + expires.toGMTString();
    }
    document.cookie = cookie_string;
    return false;
}

// onScrollActions = [];
// scrollAct = false;
//
// $(window).scroll(function(){
//     if(!scrollAct && ($(window).scrollTop()+$(window).height() == $(document).height()))
//     {
//         scrollAct = true;
//
//         for(key in onScrollActions)
//         {
//             onScrollActions[key]();
//         };
//
//         scrollAct = false;
//     }
// });

// onScrollActions.push( function() {
//     if($('#loadMoreButton')) $('#loadMoreButton').click();
// });

function metrikaHit(url, title) {
    if(yaCounter31323871 != undefined) {
        yaCounter31323871.hit(url, {title: title, referer: document.referrer});
    };
}


// Ctrl + Enter error reporting
function sendError(id){
    $(document).keydown(function(e){
        if(e.ctrlKey && e.which == 13){
            var text;
            if(text = getSelectionText()){
                errorPanel(true);
                $('#errorReport').off('submit').on('submit', function(e){
                    e.preventDefault();
                    var formData = $(this).serializeArray();
                    formData.push({name: 'id', value: id});
                    formData.push({name: 'text', value: text});
                    formData.push({name: 'url', value: window.location});
                    $.ajax({
                        url: "/mistake",
                        method: "POST",
                        data: formData,
                        success: function(){
                            errorPanel(false);
                            $('#errorReport').unbind('submit');
                        }
                    });

                    return false;
                });
            }
        }
    });
}

function getSelectionText() {
    var text = false;
    if (window.getSelection) {
        text = window.getSelection().toString();
    }
    return text;
}

function errorPanel(show){
    if(show){
        $(".report_popup").fadeIn();
        $("#cover").fadeIn();
    } else {
        // Show "thank you"
        $(".report_popup").fadeOut(300, function(){
            $(".report_popup_thanks").fadeIn(300, function(){
                $(".report_popup_thanks").fadeOut(1500);
                $("#cover").fadeOut(1500);
            });
        });
    }
}

$("#cover").click(function (e) {
    e.preventDefault();
    errorPanel(false);
    return;
});
// end: Ctrl + Enter error reporting

$('.subscribeButton').click(function(){

    $.ajax({
        url: "/ajax/action-save-email",
        method: "POST",
        data: {email: $(".emailContainer").val()},
        dataType: "JSON",
        success: function (data) {
            if (data.status) {
                //cookieVal = parseInt($.cookie('is_subscribed'), 10)
                //$.cookie('is_subscribed', cookieVal + 1, {expires: 362});
                $(".emailContainer").val("");
                $(".emailContainer").attr("placeholder", "Подписка оформлена!");
            }
        },
        error: function (data) {
            if(data.status == 422){
                $(".emailContainer" ).val("");
                $(".emailContainer").attr("placeholder", data.responseJSON.email[0]);
            } else {
                $(".emailContainer" ).val("");
                $(".emailContainer").attr("placeholder", "Ошибка сервера. Попробуйте позже.");
            }
        }
    });
    return false;
});

$("[data-format='views']").each(function () {
    var oldViews = $(this).text();
    var newViews = accounting.formatNumber(oldViews, 0, " ", " ");
    $(this).text(newViews);
});