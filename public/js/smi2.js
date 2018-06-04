function _jsload(src) {
    var sc = document.createElement("script");
    sc.type = "text/javascript";
    sc.async = true;
    sc.src = src;
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(sc, s);
}
;
(function() {
    document.getElementById("kad_tgb_2489").innerHTML = "<div id=\"container_2489\"> test </div><style>#container_2489 { box-sizing: border-box; margin: 20px 0; } #container_2489 #header_2489 { color: #ffdd00; font-family: 'Open Sans', Helvetica, Arial, Verdana, sans-serif; font-size: 16px; font-weight: 600; line-height: 30px; text-transform: uppercase; background: #000; height: 30px; padding: 0 10px; margin-bottom: 10px; display: inline-block; } #container_2489 .list-container { font-size: 0; margin: 0 -5px; } #container_2489 .list-container-item { display: inline-block; vertical-align: top; width: 33.33333%; margin: 0 0px 10px; height: 88px; overflow: hidden; padding: 0 5px; -webkit-box-sizing: border-box; box-sizing: border-box; } #container_2489 .list-container-item .innerWrap{ background: #ededed; } #container_2489 .list-container-item .imgFrame { display: block; position: relative; width: 88px; height: 88px; float: left; } #container_2489 .list-container-item .imgFrame:after { content: \"\"; position: absolute; width: 0; height: 0; line-height: 0; font-size: 0; overflow: hidden; border: solid 10px; cursor: pointer; border-color: transparent #ededed transparent transparent !important; right: 0 !important; top: 10px !important; bottom: auto !important; left: auto !important; } #container_2489 .list-container-item .image { display: block; border: none; width: 100%; height: 100%; } #container_2489 .list-container-item .title { display: block; margin-left: 98px; margin-right: 6px; text-align: left; height: 88px; color: rgb(38, 38, 38) !important; font-family: 'Open Sans', Helvetica, Arial, Verdana, sans-serif; font-size: 14px; font-weight: 600; line-height: 17px; text-decoration: none; } #container_2489 .list-container-item .title:hover { text-decoration: underline; } #container_2489 .list-container-item .clear { clear: left; } @media (min-width: 900px) and (max-width: 1024px) { #container_2489 .list-container-item { width: 100%; } } @media (max-width: 450px) { #container_2489 .list-container-item { width: 100%; } }</style>";
    var cb = function() {
        /** * Размер страницы (количество) загружаемых элементов * * @type {number} */
        var page_size = 3;
        /** * Максимальное количество загружаемых страниц элементов * * @type {number} */
        var max_page_count = 1;
        /** * Родительский элемент контейнера * * @type {HTMLElement} */
        var parent_element = JsAPI.Dom.getElement("container_2489");
        /** * Настройки блока * * @type {*} */
        var properties = undefined;
        /** * Callback-функция рендера содержимого элемента * * @type {function(HTMLElement, *, number)} */
        var item_content_renderer = function(parent, model, index) {
            JsAPI.Dom.appendChild(parent, JsAPI.Dom.createDom('div', 'innerWrap', [JsAPI.Dom.createDom('a', {
                'href': model['url'],
                'target': '_blank',
                'class': 'imgFrame'
            }, JsAPI.Dom.createDom('img', {
                'class': 'image',
                'src': model['image']
            })), JsAPI.Dom.createDom('a', {
                'href': model['url'],
                'target': '_blank',
                'class': 'title'
            }, model['title']), JsAPI.Dom.createDom('div', 'clear')]));
        };
        /** * Идентификатор блока * * @type {number} */
        var block_id = 2489;
        /** * Маска требуемых параметров (полей) статей * * @type {number|undefined} */
        var opt_fields = JsAPI.Dao.NewsField.TITLE | JsAPI.Dao.NewsField.IMAGE;
        /** * Создание list-блока */
        JsAPI.Ui.ListBlock({
            'page_size': page_size,
            'max_page_count': max_page_count,
            'parent_element': parent_element,
            'properties': properties,
            'item_content_renderer': item_content_renderer,
            'block_id': block_id,
            'fields': opt_fields
        }, function(block) {}, function(reason) {});
    };
    if (!window.jsapi) {
        window.jsapi = [];
        _jsload("//static.smi2.net/static/jsapi/jsapi.v1.8.3.ru_RU.js");
    }
    window.jsapi.push(cb);
}());
window.ttsmi2_data = {
    blockid: 2489,
    siteid: 43622
};
if (!window.smi2TrackerSend) {
    window.smi2TrackerSend = {};
    var a = window.ttsmi2_data || {};
    a.bw = window.innerWidth;
    a.bh = window.innerHeight;
    var b = document.referrer;
    b && "" != b && (a.ref = b);
    a.rnd = Math.floor(1E13 * Math.random());
    var c = [], d;
    for (d in a)
        a.hasOwnProperty(d) && c.push(encodeURIComponent(d) + "=" + encodeURIComponent(a[d]));
    var e = new Image;
    e.width = 1;
    e.height = 1;
    e.src = "//target.smi2.net/init/?" + c.join("&")
}
;/* StatMedia */
(function(w, d, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.statmedia43622 = new StatMedia({
                    'id': 43622
                });
            } catch (e) {}
        });
        if (!window.__statmedia) {
            var p = d.createElement('script');
            p.type = 'text/javascript';
            p.async = true;
            p.src = 'https://stat.media/sm.js';
            var s = d.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(p, s);
        }
    }
)(window, document, '__statmedia_callbacks');
/* /StatMedia */
