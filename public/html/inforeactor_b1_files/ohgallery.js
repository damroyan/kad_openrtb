class imageSlider {
    constructor(data) {
        this.id = data.id;
        this.counter = 0;
        this.allImageCount = $('#' + this.id).find('.galleryItem').length;
        this.el = $('#' + this.id);
        this.offset = $('#' + this.id).outerWidth();
        this.wrap = $('#' + this.id).parent().parent();
        this.wrap.offset = this.wrap.outerWidth();
        this.galleryItemWidth = $('#' + this.id).find('.galleryItem').eq(0).outerWidth();
        this.viewEl = Math.floor((this.wrap.offset - 100) / this.galleryItemWidth);
        this.viewBox = (this.viewEl * this.galleryItemWidth);


        this.widthEl = this.allImageCount * this.galleryItemWidth;

        // right arrow
        if (this.viewBox < this.widthEl) {
            this.wrap.find('.slideArrows').css('display', 'inline-block');
            this.wrap.find('.corner-right').css('left', (this.wrap.offset - 50) + 'px');
        }

        this.el.css('width', (this.widthEl) + 'px');
        if (this.widthEl % this.viewBox != 0)
            this.lastBox = Math.floor(this.widthEl / this.viewBox);
        else
            this.lastBox = (this.widthEl / this.viewBox);

        this.lastOffset = this.allImageCount % this.viewEl * this.galleryItemWidth - 20;
        this.lastLeft = ((this.lastBox - 1) * this.viewBox) + this.lastOffset;

        this.currentLeft = 0;

        var _this = this;

        this.wrap.fadeTo(500, 1);

        $('.galleryItem').each(function () {
            Photo.resize($(this).find('img'), $(this).width(), $(this).height());
        });

        $(document).on("click", ".slideArrows", function () {
            var option = $(this).data('option');
            _this.slide(option);
        });
        $(window).resize(function () {
            if (_this.wrap.offset != $('#' + _this.id).parent().parent().outerWidth()) {

                _this.wrap.offset = _this.wrap.outerWidth();
                _this.wrap.find('.corner-right').css('left', (_this.wrap.offset - 50) + 'px');

                _this.viewEl = Math.floor((_this.wrap.offset - 100) / _this.galleryItemWidth);
                _this.viewBox = (_this.viewEl * _this.galleryItemWidth);
                if (_this.widthEl % _this.viewBox != 0)
                    _this.lastBox = Math.floor(_this.widthEl / _this.viewBox);
                else
                    _this.lastBox = (_this.widthEl / _this.viewBox) - 1;

                _this.lastOffset = _this.allImageCount % _this.viewEl * _this.galleryItemWidth - 20;
                _this.lastLeft = ((_this.lastBox - 1) * _this.viewBox) + _this.lastOffset;

            }
        });
    }

    slide(option) {
        if (option == "next") {
            this.next();
        }
        else if (option == "prev") {
            this.prev();
        }
    }

    prev() {
        if (this.currentLeft >= 0) {
            $('#' + this.id).animate({
                left: -this.lastLeft
            }, 500);
            this.currentLeft = -this.lastLeft;
        } else {
            if (this.currentLeft != -this.lastOffset) {
                $('#' + this.id).animate({
                    left: '+=' + (this.viewBox)
                }, 500);
                this.currentLeft += this.viewBox;
            }
            else {
                $('#' + this.id).animate({
                    left: 0
                }, 500);
                this.currentLeft = 0;
            }
        }
    }

    next() {
        if (this.currentLeft <= -this.lastLeft) {
            $('#' + this.id).animate({
                left: 0
            }, 500);
            this.currentLeft = 0;
        } else {
            if (this.currentLeft != -(this.lastBox - 1) * this.viewBox) {
                $('#' + this.id).animate({
                    left: '-=' + (this.viewBox)
                }, 500);
                this.currentLeft -= this.viewBox;
            }
            else {
                $('#' + this.id).animate({
                    left: '-=' + (this.lastOffset)
                }, 500);
                this.currentLeft -= this.lastOffset;
            }
        }
    }
}

class Photo {
    /**
     * Подстройка фото под размеры блока
     * @param  object photo  Фото
     * @param  int width  Ширина контейнера
     * @param  int height Высота контейнера
     */
    static resize(photo, width, height) {
        if (photo.attr('src') == '' || photo.is(':hidden') || typeof photo.attr('src') == 'undefined') return;
        var newImg = new Image();
        newImg.src = photo.attr('src');

        newImg.onload = function () {
            var heightPhoto = newImg.height;
            var widthPhoto = newImg.width;

            delete this;

            var res_Width = widthPhoto / (heightPhoto / height);

            if (res_Width < width) {
                height = heightPhoto / (widthPhoto / width);
                res_Width = widthPhoto / (heightPhoto / height);
            }
            photo.css({
                "height": height,
                'width': res_Width,
                "position": "relative",
                'left': -(res_Width - width) / 2
            });
        }
    }
}