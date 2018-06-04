//ваш id партнера
var settings = {
    partner_id : '50bd8c21bfafa6e4e962f6a948b1ef92',
    count : 3,
    url: window.location.href.toString().substr(0, 512)
};


var CallbackRegistry = {};
function scriptRequest(url, params, onSuccess, onError) {
    var scriptOk = false;
    var callbackName = 'cb' + String(Math.random()).slice(-6);
    url += ~url.indexOf('?') ? '&' : '?';
    url += 'callback=CallbackRegistry.' + callbackName;
    CallbackRegistry[callbackName] = function(data) {
        scriptOk = true;
        delete CallbackRegistry[callbackName];
        onSuccess(data);
    };
    function checkCallback() {
        if (scriptOk) return;
        delete CallbackRegistry[callbackName];
        onError(url);
    }
    var script = document.createElement('script');

    script.onreadystatechange = function() {
        if (this.readyState == 'complete' || this.readyState == 'loaded') {
            this.onreadystatechange = null;
            setTimeout(checkCallback, 0);
        }
    }
    script.onload = script.onerror = checkCallback;

    var str = "";
    for (var key in params) {
       // if (str != "") {
            str += "&";
       // }
        str += key + "=" + encodeURIComponent(params[key]);
    }

    script.src = url+str;
    document.body.appendChild(script);
}

var source_url = "https://firsttex.ru";
var url = new URL(window.location.href);
if (url.searchParams.get("ft_local_test")) {
    source_url = "";
};

document.getElementById("kad_tgb_2489").innerHTML = "<div id=\"container_2489\"></div><style>#container_2489 { box-sizing: border-box; margin: 20px 0; } #container_2489 .list-container { font-size: 0; margin: 0 -5px; } #container_2489 .list-container-item { display: inline-block; vertical-align: top; width: 33.33333%; margin: 0 0px 10px; height: 88px; overflow: hidden; padding: 0 5px; -webkit-box-sizing: border-box; box-sizing: border-box; } #container_2489 .list-container-item .innerWrap{ background: #ededed; } #container_2489 .list-container-item .imgFrame { display: block; position: relative; width: 88px; height: 88px; float: left; } #container_2489 .list-container-item .imgFrame:after { content: \"\"; position: absolute; width: 0; height: 0; line-height: 0; font-size: 0; overflow: hidden; border: solid 10px; cursor: pointer; border-color: transparent #ededed transparent transparent !important; right: 0 !important; top: 10px !important; bottom: auto !important; left: auto !important; } #container_2489 .list-container-item .image { display: block; border: none; width: 100%; } #container_2489 .list-container-item .title { display: block; margin-left: 98px; margin-right: 6px; text-align: left; height: 88px; color: rgb(38, 38, 38) !important; font-family: 'Open Sans', Helvetica, Arial, Verdana, sans-serif; font-size: 14px; font-weight: 600; line-height: 17px; text-decoration: none; } #container_2489 .list-container-item .title:hover { text-decoration: underline; } #container_2489 .list-container-item .clear { clear: left; } @media (min-width: 900px) and (max-width: 1024px) { #container_2489 .list-container-item { width: 100%; } } @media (max-width: 450px) { #container_2489 .list-container-item { width: 100%; } }</style>";

scriptRequest(source_url + "/tgb/get/"
    , settings
    , function (response) {
        if (response.response == 'ok') {
            if (response.count && response.count > 0) {
                if (response.html) {
                    document.getElementById("container_2489").innerHTML = response.html;
                }
            }

        }
    }
    , function (response) {
        console.log('Good buy Error');
        console.log(response);
    }
);