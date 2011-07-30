<?php

    // load dependency
    require_once 'ImageResizer.class.php';

    /**
     * ImagickResizer class.
     * 
     * @extends ImageResizer
     */
    class ImagickResizer extends ImageResizer
    {
        /**
         * __construct function.
         * 
         * @access public
         * @param string $image path to file or image contents (aka. blob)
         * @param bool $blob
         * @return void
         */
        public function __construct($image, $blob)
        {
            parent::__construct($image, $blob);
            $this->_resource = (new Imagick());
            if ($blob) {
                $this->_resource->readImageBlob($image);
            } else {
                $this->_resource->readImage($image);
            }
        }

        /**
         * __destruct function.
         * 
         * @access public
         * @return void
         */
        public function __destruct()
        {
            $this->_resource->destroy();
            if (!is_null($this->_cropper)) {
                unset($this->_cropper);
            }
        }

        /**
         * getCropper function. Returns the cropper (if any) used to square an
         *     image
         * 
         * @access public
         * @return ImagickCropper
         */
        public function getCropper()
        {
            return $this->_cropper;
        }

        /**
         * getResource function.
         * 
         * @access public
         * @return Imagick
         */
        public function getResource()
        {
            return $this->_resource;
        }

        /**
         * resize function. Resizes an image to be a maximum number of pixels
         *     wide/high.
         * 
         * @access public
         * @param int $max
         * @return string
         */
        public function resize($max)
        {
            $dimensions = $this->_resource->getImageGeometry();
            $max = min($max, max($dimensions['width'], $dimensions['height']));
            $this->_resource->scaleImage($max, $max, true);
            return $this->_resource->getImageBlob();
        }

        /**
         * square function. Produces a square image that is resized to be a
         *     maximum width/height and is a certain number of pixels wide/high.
         * 
         * @note resizes local methods to resize image before cropping it
         * @access public
         * @param int $pixels
         * @return string
         */
        public function square($pixels)
        {

            // boot cropper
            $class = get_class($this);
            $type = strstr($class, 'Resizer', true);
            $cropper = ($type) . 'Cropper';
            require_once ($cropper) . '.class.php';

            // grab resource dimensions; determine smallest/largest dimensions
            $dimensions = $this->_resource->getImageGeometry();
            $max = max($dimensions['width'], $dimensions['height']);
            $min = min($dimensions['width'], $dimensions['height']);

            // ratio
            $ratio = $max / $min;
            $max = ceil($ratio * $pixels);

            // resize; crop
            $blob = $this->resize($max);

            $this->_cropper = (new $cropper($blob, true));
            return $this->_cropper->square($pixels);
        }
    }

?>
