//ваш id партнера
var settings = {
    partner_id : '50bd8c21bfafa6e4e962f6a948b1ef92',
    count : 4,
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

document.getElementById("kad_tgb_3399").innerHTML = "<div id=\"container_3399\"></div><link  rel=\"stylesheet\" type=\"text/css\" href=\"/css/b_src/3399.css\" />";

scriptRequest(source_url + "/tgb/get/"
    , settings
    , function (response) {
        if (response.response == 'ok') {
            if (response.count && response.count > 0) {
                if (response.html) {
                    document.getElementById("container_3399").innerHTML = response.html;
                }
            }

        }
    }
    , function (response) {
        console.log('Good buy Error');
        console.log(response);
    }
);