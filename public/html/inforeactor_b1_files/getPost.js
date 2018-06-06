$.views.settings.delimiters("{%", "%}"); // Переопределяем скобки для jsRender
var loadPosts = function(data) {
    this.data = (typeof data == "undefined")?{}:data;
    var default_values = {
        catId: 31,
        paramId: 0,
        column: 4,
        limit: 12,
        apiUrl: '/posts/get',
        page: 1,
        category__not_in: 47
    };
    for (var i in default_values ) {
         if (default_values.hasOwnProperty(i)) {
            if(typeof data[i] == "undefined") {
                this[i] = default_values[i];
            } else {
                this[i] = data[i];
            }
         }
    }
}

loadPosts.prototype.get = function()  {
    var _this=this;
    $.ajax({
        url: this.apiUrl,
        method: 'GET',
        data: {
            page : this.page,
            category : this.catId,
            param: this.paramId,
            limit: this.limit,
            category__not_in: this.category__not_in
        },
        dataType: 'json',
        success:function(data) {
            if(typeof data.posts != "undefined" ) {
                _this.page=_this.page+1;
                $(data.posts.data).each(function(index, value) {
                    var i= index % _this.column;
                    $('.news_item_column.index'+i).append($("#loadPostTmpl").render(value));
                });
            }
        }
    });
}