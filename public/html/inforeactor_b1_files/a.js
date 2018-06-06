function rememberReferer(callback) {
    console.log("Remember referer");
    if(!isSelfReferer() && !isClearRef()){
        console.log("Store: " + document.referrer);
        setAdCookie("referer", document.referrer);
    }
    if(callback) {
        callback();
    }
}
function isSelfReferer() {
    return document.referrer.match(/inforeactor.ru/i);
}

function setAdCookie(name, value){
    var cookie_string = name + "=" + escape ( value );
    var date = new Date();
    date.setDate(date.getDate() + 1);
    cookie_string += "; expires=" + date.toUTCString();
    document.cookie = cookie_string;
    return false;
}

function getCookie(cookie_name){
    var results = document.cookie.match ( '(^|;) ?' + cookie_name + '=([^;]*)(;|$)' );
    if(results)
        return ( unescape ( results[2] ) );
    else 
        return null;
}

function isYandexNews() {
    return getCookie('referer') && getCookie('referer').match(/yandex/i);
}

function isObjzor() {
    return getCookie('referer') && getCookie('referer').match(/(warfiles|politobzor|vsluh|finobzor)/i) || isIframe();
}

function isIframe() {
    return ( getCookie('referer') == '' || window.self !== window.top );
}

function isDA() {
    return getCookie('referer') && getCookie('referer').match(/(nnn.ru|directadvert)/i);
}

function isClearRef() {
    return document.referrer == '';
}


function isSmi2() {
    return getCookie('referer') && getCookie('referer').match(/smi2.ru/i);
}

function is24smi() {
    return getCookie('referer') && getCookie('referer').match(/24smi/i);
}
function isMarketgid() {
    return getCookie('referer') && getCookie('referer').match(/marketgid/i);
}
function isLentainform() {
    return getCookie('referer') && getCookie('referer').match(/lentainform/i);
}

function isSocial() {
    return getCookie('referer') && getCookie('referer').match(/(vk.com|ok.com|facebook)/i);
}

function isMM() {
    return getCookie('referer') && getCookie('referer').match(/mediametrics/i);
}

function isInfox() {
    return getCookie('referer') && getCookie('referer').match(/infox/i);
}


function daScript(id) {
    var fileref=document.createElement('script');
    fileref.setAttribute("type","text/javascript");
    fileref.setAttribute("charset","windows-1251");
    fileref.setAttribute("src", '//inner.inforeactor.ru/data/'+id+'.js');
    document.getElementsByTagName("head")[0].appendChild(fileref);
}

function goLanding() {
    console.log("Go Landing init");
        rememberReferer(function () {
        console.log("Remember Referer callback");
        if (isLentainform() || isMarketgid())  {
            if(!document.location.pathname.match(/\/landing\//i) && document.location.pathname.match(/^\/\d*-\S*/i) && !getCookie('visitLanding_'+document.location.pathname)) {
                setAdCookie('visitLanding_'+document.location.pathname, true);
                document.location.href = document.location.protocol + '//' + document.location.hostname + '/landing' + document.location.pathname;
                console.log("Landing GO");
            }
        }
    });
}
