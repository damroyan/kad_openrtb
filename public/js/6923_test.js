//ваш id партнера
var settings = {
    partner_id : 'f08dfd477b90159ac5cef98cebe1ee90',
    count : 4,
    url: window.location.href.toString().substr(0, 512)
};

if (typeof CallbackRegistry === "undefined") {
    var CallbackRegistry = {};
}
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
    document.getElementById("kad_tgb_6923").innerHTML = "<div id=\"container_6923\"></div><link  rel=\"stylesheet\" type=\"text/css\" href=\"/css/b_src/6923.css\">";
} else {
    document.getElementById("kad_tgb_6923").innerHTML = "<div id=\"container_6923\"></div><style>#container_6923 {position: relative;display: table;-webkit-box-sizing: content-box;-moz-box-sizing: content-box;box-sizing: content-box;margin-left: auto;margin-right: auto;margin-bottom: 0;width: auto;max-width: none;padding-top: 0px;padding-bottom: 0px;padding-left: 0px;padding-right: 0px;border-width: 0px;border-style: solid;border-color: #000000;background-color: #ffffff;background-image: none;background-repeat: repeat;background-position: 0 0;}#container_6923 .list-container {display: table-row;}#container_6923 .list-container-item {text-decoration: none;display: table-cell;position: relative;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;/* min-width: 86px; */width: 25%;padding-top: 13px;padding-bottom: 13px;padding-left: 13px;padding-right: 13px;border-width: 0px;border-style: solid;border-color: #000000;background-color: #ffffff;background-image: none;background-repeat: repeat;background-position: 0 0;font-size: 0;vertical-align: top;transition-duration: 100ms;}#container_6923 .list-container-item .innerWrap{position: static;display: block;vertical-align: top;text-align: center;}#container_6923 .list-container-item .imgFrame {box-sizing: border-box;max-width: 550px !important;width: 100%;display: inline-block;vertical-align: inherit;}#container_6923 .list-container-item .imgFrame > div {background-position: center center;background-size: cover;background-repeat: no-repeat;display: block;}#container_6923 .list-container-item .title {width: 100%;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding-top: 3px;padding-right: 3px;padding-bottom: 3px;padding-left: 3px;color: #000000;font-family: inherit;font-size: 16px;line-height: 1.3;word-wrap: break-word;}#container_6923 .list-container-item .title:hover {}#container_6923 .list-container-item .clear {clear: left;}</style>";;
}

scriptRequest(source_url + "/tgb/get/"
    , settings
    , function (response) {
        if (response.response == 'ok') {
            if (response.count
                && response.count >= settings.count
             ) {
                if (response.html) {
                    document.getElementById("container_6923").innerHTML = response.html;
                }
            } else {
                //document.getElementById("container_6923").style.cssText = "display:none;";

                document.getElementById("kad_tgb_6923").innerHTML = " <div id=\"JefqDo9Vx24TgzDMmlWy\"></div>";

                (function(d,s,b,id){var js=d.createElement(s);
                    window.AD_DOMAIN_URL_SYS_8349582369 = '//smipar.pro/';
                    js.src='//smipar.pro/js/v1.2/script.min.js';
                    d.getElementsByTagName('head')[0].appendChild(js);
                    if(typeof d[b]=='undefined') d[b]=[];
                    d[b].push(id);})(document,'script','obTBlocks','JefqDo9Vx24TgzDMmlWy')

            }

        }
    }
    , function (response) {
        console.log('Good buy Error');
        console.log(response);
    }
);