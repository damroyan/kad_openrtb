// politexpert - right-col x3
var jolly_settings_1724 = {
    partner_id : '50bd8c21bfafa6e4e962f6a948b1ef92',
    count : 3,
    url: window.location.href.toString().substr(0, 512),
    source: 'rp_videocapcinema'
};

if (typeof jolly_callback_1724 === "undefined") {
    var jolly_callback_1724 = {};
}

function jolly_request_1724(url, params, onSuccess, onError) {
    var scriptOk = false;
    var callbackName = 'cb' + String(Math.random()).slice(-6);
    url += ~url.indexOf('?') ? '&' : '?';
    url += 'callback=jolly_callback_1724.' + callbackName;
    jolly_callback_1724[callbackName] = function(data) {
        scriptOk = true;
        delete jolly_callback_1724[callbackName];
        onSuccess(data);
    };
    function checkCallback() {
        if (scriptOk) return;
        delete jolly_callback_1724[callbackName];
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
    document.getElementById("kad_tgb_1724").innerHTML = "<div id=\"container_1724\"></div><link  rel=\"stylesheet\" type=\"text/css\" href=\"/css/b_src/1724.css\">";
} else {
    document.getElementById("kad_tgb_1724").innerHTML = "<div id=\"container_1724\"></div><style>#container_1724 {position: relative;display: table;-webkit-box-sizing: content-box;-moz-box-sizing: content-box;box-sizing: content-box;margin-left: auto;margin-right: auto;margin-bottom: 0;width: auto;max-width: none;padding-top: 0px;padding-bottom: 0px;padding-left: 0px;padding-right: 0px;border-width: 0px;border-style: solid;border-color: #000000;background-color: #ffffff;background-image: none;background-repeat: repeat;background-position: 0 0;}#container_1724 .list-container {display: table-row;}#container_1724 .list-container-item {text-decoration: none;display: table-cell;position: relative;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;/* min-width: 86px; */width: 33%;padding-top: 0px;padding-bottom: 0px;padding-left: 9px;padding-right: 9px;border-width: 0px;border-style: solid;border-color: #000000;background-color: #ffffff;background-image: none;background-repeat: repeat;background-position: 0 0;font-size: 0;vertical-align: top;transition-duration: 100ms;}#container_1724 .list-container-item:first-child {padding-right: 9px !important;padding-left: 0px !important;}#container_1724 .list-container-item:last-child {padding-right: 0px !important;padding-left: 9px !important;}#container_1724 .list-container-item .innerWrap{position: static;display: block;vertical-align: top;text-align: center;}#container_1724 .list-container-item .imgFrame {box-sizing: border-box;max-width: 550px !important;width: 100%;height: auto;display: inline-block;vertical-align: inherit;}#container_1724 .list-container-item .imgFrame > div {background-position: center center;background-size: 170% auto !important;background-repeat: no-repeat;display: block;width: 100% !important;height: 100% !important;padding-top: 89%;cursor: pointer;}#container_1724 .list-container-item:first-child .imgFrame > div {padding-top: 85% !important;}#container_1724 .list-container-item:last-child .imgFrame > div {padding-top: 85% !important;}#container_1724 .list-container-item .title {width: 100%;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding-top: 3px;padding-right: 3px;padding-bottom: 3px;padding-left: 3px;color: #000000;font-family: inherit;color: #111111;font-size: 12px;line-height: 1.3;word-wrap: break-word;text-align: left;cursor: pointer;}#container_1724 .list-container-item .title img {height: 1px !important;}#container_1724 .list-container-item .title:hover {}#container_1724 .list-container-item .clear {clear: left;} </style>";;
}

jolly_request_1724(source_url + "/tgb/get/"
    , jolly_settings_1724
    , function (response) {
        if (response.response == 'ok') {
            //console.log(response);
            if (response.count && response.count == jolly_settings_1724.count
            ) {
                if (response.html) {
                    document.getElementById("container_1724").innerHTML = response.html;
                }
            } else {
                //document.getElementById("container_1724").style.cssText = "display:none;";

                document.getElementById("kad_tgb_1724").innerHTML = "<div id=\"MOIPqqs88Lr7JBpbawiP\"></div>";

                (function(d,s,b,id){var js=d.createElement(s);
                    window.AD_DOMAIN_URL_SYS_8349582369 = '//smipar.pro/';
                    js.src='//smipar.pro/js/v1.2/script.min.js';
                    d.getElementsByTagName('head')[0].appendChild(js);
                    if(typeof d[b]=='undefined') d[b]=[];
                    d[b].push(id);})(document,'script','obTBlocks','MOIPqqs88Lr7JBpbawiP')

            }

        }
    }
    , function (response) {
        console.log('Good buy Error');
        console.log(response);
    }
);