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

function jolly_init_1537() {

    console.log("Jolly, Insert please");

    let host = fn_jolly_1537.getHost(jolly_settings_1537.url);
    if (typeof host === 'undefined') {
        return;
    }
    var img = document.createElement("img");
    img.src = 'https://firsttex.ru/tgb/track?partner_id='+jolly_settings_1537.partner_id+'&action=track&host='+host+'&id='+Math.random()+'&session=test&client=test&url='+jolly_settings_1537.url;

    switch (host) {

        case 'tizer.local':
            jolly_worker_1537('.js-mediator-article', 'P', 0, '');

            break;

        case 'rbc.ru':
            jolly_settings_1537.count = 2;
            jolly_worker_1537('.article__text', 'P', 0, '');

            break;

        case 'news.ru':
            jolly_settings_1537.count = 2;
            jolly_worker_1537('.article-text', 'P', 0, '');

            break;


        case 'gazeta.ru':
            jolly_settings_1537.count = 2;
            jolly_worker_1537('.article-text-body', 'P', 0, '');
            
            break;

        case 'vesti.ru':
        case 'championat.com':
        case '24smi.org':
            jolly_worker_1537('.js-mediator-article', 'P', 0, '');

            break;

        case 'ficbook.net':
            jolly_settings_1537.count = 4;
            jolly_worker_1537('.content-section', 'DIV', 0, '#container_1537 .list-container-item {width: 25% !important}');

            break;


        case 'drive2.ru':
            jolly_worker_1537('.js-translate-text', 'BR', 2, '');

            break;

        case 'mk.ru':
            jolly_settings_1537.count = 2;
            jolly_worker_1537('.content', 'P', 2, '#container_1537 .list-container-item .title img { width: 1px !important; height: 1px !important;}');

            break;

        default:
            return;
            break;
    }



    return false;
};

function jolly_worker_1537 (el_class, tag_name, after_tag_number, css='' ) {
    var el = Dom.query(el_class);
    if (!el || !el[0]) {
        console.log('Jolly: Element for init not found');
        return false;
    }

    var elements = el[0].getElementsByTagName(tag_name);

    if (elements.length >= after_tag_number) {

        if (css!='') {
                head = document.head || document.getElementsByTagName('head')[0],
                style = document.createElement('style');

            style.type = 'text/css';
            if (style.styleSheet){
                // This is required for IE8 and below.
                style.styleSheet.cssText = css;
            } else {
                style.appendChild(document.createTextNode(css));
            }

            head.appendChild(style);
        }

        let div = document.createElement('div');
        div.id = 'kad_tgb_1537';
        div.style = 'width: 100%;';

        Dom.insertAfter(div,elements[after_tag_number]);
        jolly_insert_1537('default');
    }
}



/***
 * DOM worker -- START
 */

var Dom = {
    query: function(query, elemParent) {
        let self = this,
            elem = [];

        elemParent = elemParent ? elemParent : document;

        if (!query) {
            return null;
        }

        switch (typeof query) {
            case 'object':
                if (fn_jolly_1537.isArray(query)) {
                    query.forEach(function(value) {
                        let result = self.query(value, elemParent);

                        if (result) {
                            fn_jolly_1537.merge(elem, result);
                        }
                    });
                }
                else {
                    if (query === null) {
                        return null;
                    }

                    elem.push(query);
                }

                break;

            case 'string':
                let a = query.split(',');
                if (a.length === 0) {
                    return null;
                }
                else if (a.length === 1) {
                    let val = fn_jolly_1537.trim(a[0]);
                    if (val.match(/^#/)) {
                        elem.push(
                            elemParent.getElementById(val.replace(/^#+/, ''))
                        );
                        break;
                    }
                    else {
                        if (typeof elemParent.querySelector === 'function') {
                            if ((function() {
                                let e = elemParent.querySelector(val);
                                if (e) {
                                    elem.push(e);
                                    return true;
                                }

                                return false;
                            })() === false) {
                                return null;
                            }
                        }
                        else if (typeof elemParent.querySelectorAll === 'function') {
                            if ((function() {
                                let e = elemParent.querySelectorAll(val);
                                if (e[0]) {
                                    elem.push(e[0]);
                                    return true;
                                }

                                return false;
                            })() === false) {
                                return null;
                            }
                        }
                        else {
                            console.log('Query selector not support:', val);
                            return null;
                        }
                    }
                }
                else {
                    a.forEach(function(value) {
                        let result = self.query(value, elemParent);

                        if (result) {
                            fn_jolly_1537.merge(elem, result);
                        }
                    });
                }

                break;

            default:
                console.log('Query selector undefined type:', typeof query);
                return null;

                break;
        }

        if (!elem.length) {
            return null
        }

        return elem;
    },

    documentHeight: function() {
        let D = document;

        return Math.max(
            D.body.scrollHeight, D.documentElement.scrollHeight,
            D.body.offsetHeight, D.documentElement.offsetHeight,
            D.body.clientHeight, D.documentElement.clientHeight
        )
    },

    insertBefore: function(elem, refElem) {
        let parent = refElem.parentNode;
        return parent.insertBefore(elem, refElem);
    },

    insertAfter: function (elem, refElem) {
        let parent = refElem.parentNode;
        let next = refElem.nextSibling;

        if (next) {
            return parent.insertBefore(elem, next);
        }
        else {
            return parent.appendChild(elem);
        }
    },

    // Sizzle.getText
    getText: function(elem) {
        let node,
            ret = "",
            i = 0,
            nodeType = elem.nodeType;

        if ( !nodeType ) {
            // If no nodeType, this is expected to be an array
            while ( (node = elem[i++]) ) {
                // Do not traverse comment nodes
                ret += this.getText( node );
            }
        } else if ( nodeType === 1 || nodeType === 9 || nodeType === 11 ) {
            // Use textContent for elements
            // innerText usage removed for consistency of new lines (jQuery #11153)
            if ( typeof elem.textContent === "string" ) {
                return elem.textContent;
            } else {
                // Traverse its children
                for ( elem = elem.firstChild; elem; elem = elem.nextSibling ) {
                    ret += this.getText( elem );
                }
            }
        } else if ( nodeType === 3 || nodeType === 4 ) {
            return elem.nodeValue;
        }
        // Do not include comment or processing instruction nodes

        return ret;
    },

    position: function(elem) {
        // (1)
        let box = elem.getBoundingClientRect();

        let body = document.body;
        let docEl = document.documentElement;

        // (2)
        let scrollTop = window.pageYOffset || docEl.scrollTop || body.scrollTop;
        let scrollLeft = window.pageXOffset || docEl.scrollLeft || body.scrollLeft;

        // (3)
        let clientTop = docEl.clientTop || body.clientTop || 0;
        let clientLeft = docEl.clientLeft || body.clientLeft || 0;

        // (4)
        let top = box.top + scrollTop - clientTop;
        let left = box.left + scrollLeft - clientLeft;

        return {
            top: top,
            left: left
        };
    },

    positionScroll: function() {
        let body = document.body;
        let docEl = document.documentElement;

        let scrollTop = window.pageYOffset || docEl.scrollTop || body.scrollTop;
        let scrollLeft = window.pageXOffset || docEl.scrollLeft || body.scrollLeft;

        return {
            top: scrollTop,
            left: scrollLeft
        };
    },

    windowWidthHeight: function() {
        let w = window.innerWidth
            || document.documentElement.clientWidth
            || document.body.clientWidth;

        let h = window.innerHeight
            || document.documentElement.clientHeight
            || document.body.clientHeight;

        return {
            width:  w,
            height: h
        };
    },

    create: function(obj, elem, attr, style) {
        let e = document.createElement(elem);

        this.attr(e, attr);
        this.css(e, style);

        try {
            obj.appendChild(e);
        }
        catch (e) {
        }

        return e;
    },

    createDiv: function(obj, attr, style) {
        return this.create(obj, 'div', attr, style);
    },

    remove: function (obj) {
        if (!obj) {
            return false;
        }

        try {
            obj.remove();
        }
        catch (e) {
            obj.parentNode.removeChild(obj);
        }
    },

    clear: function(obj) {
        while (obj.firstChild) {
            obj.removeChild(obj.firstChild);
        }
    },

    hasClass: function (obj, key) {
        try {
            let tmp = obj.className.split(' ');

            for (let i in tmp) {
                if (!tmp.hasOwnProperty(i)) {
                    continue;
                }

                if (tmp[i] === key) {
                    return true;
                }
            }
        }
        catch (e) {
            console.log('Exception: fn[hasClass]', e);
        }

        return false;
    },

    addClass: function (obj, key) {
        try {
            let tmp = obj.className.split(' '),
                s = false;

            for (let i in tmp) {
                if (!tmp.hasOwnProperty(i)) {
                    continue;
                }

                if (tmp[i] === key) {
                    s = true;
                    break;
                }
            }

            if (!s) {
                tmp.push(key);
            }

            obj.className = tmp.join(' ');
        }
        catch (e) {
            console.log('Exception: fn[addClass]', e);
        }

        return obj;
    },

    removeClass: function (obj, key) {
        try {
            let tmp1 = obj.className.split(' '),
                tmp2 = [];

            for (let i in tmp1) {
                if (!tmp1.hasOwnProperty(i)) {
                    continue;
                }

                if (tmp1[i] !== key) {
                    tmp2.push(tmp1[i]);
                }
            }

            obj.className = tmp2.join(' ');
        }
        catch (e) {
            console.log('Exception: fn[removeClass]', e);
        }

        return obj;
    },

    css: function (obj, key, value) {
        if (typeof (key) === 'object') {
            for (let i in key) {
                if (!key.hasOwnProperty(i)) {
                    continue;
                }

                this.css(obj, i, key[i])
            }
        }
        else if (typeof key === 'string' && typeof value === 'undefined') {
            return obj.style[key] ? obj.style[key] : null;
        }
        else if (typeof key === 'string' && typeof value !== 'undefined') {
            try {
                obj.style[key] = value;
            }
            catch (e) {
                console.log('Exception: fn[css]', e);
            }
        }

        return obj;
    },

    attr: function (obj, key, value) {
        if (typeof (key) === 'object') {
            for (let i in key) {
                if (!key.hasOwnProperty(i)) {
                    continue;
                }

                this.attr(obj, i, key[i])
            }
        }
        else if (typeof key === 'string' && typeof value === 'undefined') {
            return obj[key] ? obj[key] : null;
        }
        else if (typeof key === 'string' && typeof value !== 'undefined') {
            try {
                obj[key] = value;
            }
            catch (e) {
                console.log('Exception: fn[attr]', e);
            }
        }

        return obj;
    },

    width: function(obj) {
        let width = obj.offsetWidth;

        if (!width) {
            width = obj.getBoundingClientRect().width
        }

        return width;
    },

    height: function(obj) {
        let height = obj.offsetHeight;

        if (!height) {
            height = obj.getBoundingClientRect().height
        }

        return height;
    }
};

//helper functions
let fn_jolly_1537 = {
    trim: function(str) {
        return this.rtrim(this.ltrim(str));
    },

    ltrim: function(str) {
        return str.toString().replace(/^\s+/g, '');
    },

    rtrim: function(str) {
        return str.toString().replace(/\s+$/g, '');
    },
    
    openTab: function(url) {
        let win = window.open(url, '_blank');
        win.focus();

        return win;
    },

    getUrl: function(value) {
        if (typeof value !== 'string') {
            return undefined;
        }

        if (value.match(/^\/\//)) {
            return 'http:' + value;
        }
        else if (value.match(/^\//)) {
            return undefined;
        }
        else if (value.match(/^[a-z][a-z0-9]+:\/\//)) {
            return value;
        }
        else {
            let m = value.match(/^[^\/]+/);
            if (m && m[0] && m[0].match(/\./)) {
                return 'http://' + value;
            }
        }

        return undefined;
    },

    parseUrl: function(value) {
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
    },

    getHost: function(value) {
        let url = this.getUrl(value);

        if (typeof url !== 'string') {
            return undefined;
        }

        let parts = this.parseUrl(url);
        if (typeof parts === 'undefined') {
            return undefined;
        }

        return (parts.host).replace(/^www\./, '');
    },

    merge: function(arr1, arr2) {
        let self = this;

        if (!self.isArray(arr1)) {
            return null;
        }

        if (typeof arr2 !== 'object' || !arr2[0]) {
            return arr1;
        }

        arr2.forEach(function (value) {
            arr1.push(value);
        });

        return arr1;
    },

    trackingImage: function(src) {
        if (typeof src !== 'string') {
            return null;
        }

        let img = new Image();
        img.src = src;

        return img;
    },

    isArray: function( obj ) {
        return Object.prototype.toString.call(obj) === "[object Array]";
    }
};


(function() {
    console.log('MAGIC HERE!');
    jolly_init_1537();
})();