<?php
namespace Tizer;
class Image {
    /**
     * @var Imagick
     */
    public $im = null;
    public $isImage = false;

    public $quality = 95;
    public $width = null;
    public $height = null;
    public $ratio = null;

    protected $maxWidth = 4096;
    protected $maxHeight = 4096;

    public function __construct($file = null) {
        if(!is_null($file)) {
            $this->open($file);
        }
    }

    public function blank() {
        $this->im = new Imagick();

        return $this;
    }

    public function open($file) {
        if(!is_file($file)) {
            return null;
        }

        try {
            $this->im = new Imagick($file);
            $this->setWidthHeightRatio();

            return $this;
        }
        catch(Exception $e) {
            $this->setWidthHeightRatio();

            return false;
        }
    }

    public function setBackground($color = 'white') {
        $this->im->setImageBackgroundColor(new ImagickPixel($color));
        try {
            $this->im = $this->im->flattenImages();
        }
        catch(\Exception $e) {
            $this->im->setImageAlphaChannel(11);
            $this->im = $this->im->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
        }

        return $this;
    }

    public function setWidthHeightRatio() {
        if($this->im) {
            $imageprops = $this->im->getImageGeometry();

            $this->width    = (int)$imageprops['width'];
            $this->height   = (int)$imageprops['height'];

            if($this->width && $this->height) {
                $this->ratio    = $this->width / $this->height;
                $this->isImage  = true;

                return $this;
            }
        }

        $this->isImage  = false;
        $this->width    = null;
        $this->height   = null;
        $this->ratio    = null;

        return $this;
    }

    public function rotate($angle = 90, $color = null) {
        if(is_null($color)) {
            $color = new ImagickPixel('#00000000'); // Transparent
        }

        $this->im->rotateImage($color, $angle);
        $this->setWidthHeightRatio();

        return $this;
    }

    public function setMaximumWidhtHeight() {
        if($this->width > $this->maxWidth || $this->height > $this->maxHeight) {
            if($this->ratio > 1) {
                $this->width    = $this->maxWidth;
                $this->height   = floor($this->width / $this->ratio);
            }
            else {
                $this->height   = $this->maxHeight;
                $this->width    = floor($this->height * $this->ratio);
            }

            $this->im->resizeImage(
                $this->width,
                $this->height,
                Imagick::FILTER_LANCZOS,
                1
            );
        }

        return $this;
    }

    public function setWatermark($file, $x = null, $y = null) {
        if(!is_file($file)) {
            return $this;
        }

        $watermark = new Imagick();
        $watermark->readImage($file);

        $width = $watermark->getImageWidth();
        $height = $watermark->getImageHeight();

        $offsetX = 0;
        $offsetY = 0;

        $paddingX = 10;
        $paddingY = 10;

        if($width * 3 > $this->width || $height * 3 > $this->height) {
            return $this;
        }

        $x = is_null($x) ? (rand(0, 1) ? -1 : 1) : $x;
        $y = is_null($y) ? (rand(0, 1) ? -1 : 1) : $y;

        switch($x) {
            case -1:
                $offsetX = $paddingX;
                break;

            case 0:
                $offsetX = ($this->width - $width) / 2;
                break;

            case 1:
                $offsetX = $this->width - ($paddingX + $width);
                break;
        }

        switch($y) {
            case -1:
                $offsetY = $paddingY;
                break;

            case 0:
                $offsetY = ($this->height - $height) / 2;
                break;

            case 1:
                $offsetY = $this->height - ($paddingY + $height);
                break;
        }

        $this->im->compositeImage(
            $watermark,
            imagick::COMPOSITE_OVER,
            $offsetX,
            $offsetY
        );
    }

    public function getBlob() {
        return $this->im->getImageBlob();
    }

    public function save($file) {
        preg_match('@\.(png|jpg)$@', $file, $match);

        switch($match[1]) {
            case 'png':
                $this->im->setImageFormat('png');
                break;

            case 'jpg':
            default:
                $this->im->setImageFormat('jpg');
                $this->im->setImageCompression(Imagick::COMPRESSION_JPEG);
                $this->im->setImageCompressionQuality($this->quality);

                break;
        }

        $this->im->writeimage($file);

        return $this;
    }

    public function setImagick($imagick) {
        $this->im = $imagick;
    }

    public function round($radius) {
        $this->im->roundCorners($radius, $radius, 10, 5, -6);
    }

    public function crop($width, $height, $x = null, $y = null) {

        if(is_null($x) && is_null($y)) {
            $this->im->cropThumbnailImage($width, $height);
        }
        else {
            if(is_null($x)) {
                $x = ($this->width - $width) / 2;
            }

            if(is_null($y)) {
                $y = ($this->height - $height) / 2;
            }

            $this->im->cropImage($width, $height, (int)$x, (int)$y);
        }

        $this->setWidthHeightRatio();
    }

    public function resize($width, $height, $force = false) {

        if ($this->ratio > 1) {
           $imgWidth = $width;
           $imgHeight = floor($this->height * $imgWidth / $this->width);
        }
        else {
           $imgHeight = $height;
           $imgWidth = floor($this->width * $imgHeight / $this->height);
        }

        if ($imgWidth >= $this->width && $imgHeight >=$this->height && !$force) {
            return $this;
        }

        $this->im->adaptiveResizeImage($imgWidth, $imgHeight);

        $this->setWidthHeightRatio();

        return $this;
    }

    public function resizeMin($min, $bestfit = true) {
        if($this->width > $this->height) {
            if($min > $this->height) {
                $min = $this->height;
            }

            $this->im->scaleImage($this->ratio * $min, $min, $bestfit);
        }
        else {
            if($min > $this->width) {
                $min = $this->width;
            }

            $this->im->scaleImage($min, $min / $this->ratio, $bestfit);
        }

        $this->setWidthHeightRatio();
    }

    public function resizeMaxWidthHeight($width, $height, $bestfit = true) {
        $w = 0;
        $h = 0;

        if($width > $this->width && $height > $this->height) {
            return $this;
        }

        if(!$width || !$height) {
            if(!$width) {
                if($height > $this->height) {
                    return $this;
                }
                else {
                    $h = $height;
                    $w = $this->ratio * $height;
                }
            }
            elseif(!$height) {
                if($width > $this->width) {
                    return $this;
                }
                else {
                    $w = $width;
                    $h = $width / $this->ratio;
                }
            }
        }
        else {
            if($this->width > $this->height) {
                $w = $width;
                $h = $width / $this->ratio;
            }
            else {
                $h = $height;
                $w = $this->ratio * $height;
            }
        }

        $this->im->scaleImage($w, $h, $bestfit);
        $this->setWidthHeightRatio();
    }

    /**
     * Ресайз пропорция
     *
     * @param $ratio
     * @param null $width
     * @param null $height
     * @return $this
     */
    public function resizeRatio($ratio, $width = null, $height = null) {
        if($width) {
            $w = $width;
            $h = round($w * $ratio);
        }
        elseif($height) {
            $h = $height;
            $w = round($h / $ratio);
        }
        else {
            $w = $this->width;
            $h = round($w * $ratio);
        }

        if($this->ratio <= $ratio) {
            $width = $this->width > $w ? $w : $this->width;
            $height = round($width / $ratio);
        }
        else {
            $height = $this->height > $h ? $h : $this->height;
            $width = round($height * $ratio);
        }

        $this->resize($width, $height, true);
        if($width != $this->width || $height != $this->height) {
            $this->crop($width, $height);
        }

        return $this;
    }

    public function resizeMax($max, $bestfit = true) {
        if($this->width > $this->height) {
            if($max > $this->width) {
                $max = $this->width;
            }

            $this->im->scaleImage($max, $max / $this->ratio, $bestfit);
        }
        else {
            if($max > $this->height) {
                $max = $this->height;
            }

            $this->im->scaleImage($this->ratio * $max, $max, $bestfit);
        }

        $this->setWidthHeightRatio();
    }

    public function getExt() {
        $type = $this->im->getImageFormat();

        switch($type) {
            case 'JPEG':
            case 'JPG':
                return 'jpg';
                break;

            case 'PNG':
            case 'PNG00':
            case 'PNG24':
            case 'PNG32':
            case 'PNG48':
            case 'PNG64':
            case 'PNG8':
                return 'png';
                break;

            case 'GIF':
            case 'GIF87':
                return 'gif';
                break;

            default:
                return false;
                break;
        }
    }

    static public function Validate($file, $size = null, $aspect = null) {
        /**
         * @var $image \Image
         */
        $image = new self($file);

        if (!is_null($size) && filesize($file) > $size) {
            return [false, __('Image is too big. The limit is %d. Please try to upload less size image',$size)];
        }

        if(!$image->isImage) {
            return [false, __("We can't define image type. Please try do upload other file")];
        }

        $ext = $image->getExt();
        if($ext === false) {
            return [false, __("We can't define image type. Please try do upload other file")];
        }

        if($image->width > 0 && $image->height > 0) {
            if(!is_null($aspect) && $aspect > 0) {
                if($image->ratio >= $aspect || $image->ratio <= 1 / $aspect) {
                    return [false, __('Bad image proportion detected. Use Image Editor or another photo and than try do download image again.')];
                }
            }
        }
        else {
            return [false, __('We receive image with 0 height or 0 width. It can mean that file was broken. You should try to download another one.')];
        }

        return [true, null, $ext];
    }
}
