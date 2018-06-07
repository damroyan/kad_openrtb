// politexpert - right-col x3
var jolly_settings_3579 = {
    partner_id : '50bd8c21bfafa6e4e962f6a948b1ef92',
    count : 3,
    url: window.location.href.toString().substr(0, 512),
    source: 'rp_videocapcinema'
};

if (typeof jolly_callback_3579 === "undefined") {
    var jolly_callback_3579 = {};
}

function jolly_request_3579(url, params, onSuccess, onError) {
    var scriptOk = false;
    var callbackName = 'cb' + String(Math.random()).slice(-6);
    url += ~url.indexOf('?') ? '&' : '?';
    url += 'callback=jolly_callback_3579.' + callbackName;
    jolly_callback_3579[callbackName] = function(data) {
        scriptOk = true;
        delete jolly_callback_3579[callbackName];
        onSuccess(data);
    };
    function checkCallback() {
        if (scriptOk) return;
        delete jolly_callback_3579[callbackName];
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
    document.getElementById("kad_tgb_3579").innerHTML = "<div id=\"container_3579\"></div><link  rel=\"stylesheet\" type=\"text/css\" href=\"/css/b_src/3579.css\">";
} else {
    document.getElementById("kad_tgb_3579").innerHTML = "<div id=\"container_3579\"></div><style>#container_3579 {position: relative;display: block;height: auto;overflow: visible;}#container_3579 .list-container {padding: 0 !important;position: relative !important;text-align: center;vertical-align: top !important;margin: 0 auto;border-style: solid;border-width: 0px;display: -ms-flexbox;display: -webkit-flex;display: flex;-webkit-flex-direction: row;-ms-flex-direction: row;flex-direction: row;-webkit-flex-wrap: wrap;-ms-flex-wrap: wrap;flex-wrap: wrap;line-height: 100% !important;transition: none !important;box-sizing: border-box;}#container_3579 .list-container-item {position: relative;padding: 3px;border-color: transparent;border: none !important;min-width: 230px;vertical-align: top;background: none repeat scroll 0 0;cursor: pointer;display: inline-block;_overflow: hidden;padding: 0 !important;border-style: solid;border-color: #b71c1c;border-width: 0px;max-width: 99%;box-sizing: border-box;margin: 6px 0.6%;display: -ms-flexbox;display: -webkit-flex;display: flex;-webkit-flex-direction: column;-ms-flex-direction: column;flex-direction: column;}#container_3579 .list-container-item .innerWrap{}#container_3579 .list-container-item .imgFrame {box-sizing: border-box;width: 102px;display: inline-block;vertical-align: inherit;float: left;}#container_3579 .list-container-item .imgFrame > div {background-position: center center;background-size: 150px auto !important;background-repeat: no-repeat;display: block;width: 102px;height: 102px;}#container_3579 .list-container-item .title {float:left;word-wrap: break-word;width: 135px;margin-left: 3px;font-weight: normal;font-size: 15px;line-height: 15px;font-style: normal;text-decoration: none;color: #000000;font-family: Arial,Helvetica,sans-serif;cursor: pointer;text-align: left;}#container_3579 .list-container-item .title:hover {}#container_3579 .list-container-item .clear {}</style>";;
}

jolly_request_3579(source_url + "/tgb/get/"
    , jolly_settings_3579
    , function (response) {
        if (response.response == 'ok') {
            //console.log(response);
            if (response.count && response.count == jolly_settings_3579.count
            ) {
                if (response.html) {
                    document.getElementById("container_3579").innerHTML = response.html;
                }
            } else {
                //document.getElementById("container_3579").style.cssText = "display:none;";

                document.getElementById("kad_tgb_3579").innerHTML = "<div id=\"MOIPqqs88Lr7JBpbawiP\"></div>";

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