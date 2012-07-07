<?php

    // dependecy check
    if (!in_array('imagick', get_loaded_extensions())) {
        throw new Exception('Imagick extension needs to be installed.');
    }

    // load dependency
    require_once 'ImageResizer.class.php';

    /**
     * ImagickResizer
     * 
     * @author  Oliver Nassar <onassar@gmail.com>
     * @extends ImageResizer
     */
    class ImagickResizer extends ImageResizer
    {
        /**
         * __construct
         * 
         * @access public
         * @param  string $image path to file or image contents (aka. blob)
         * @param  boolean $blob
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
         * __destruct
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
         * getCropper
         * 
         * Returns the cropper (if any) used to square an image.
         * 
         * @access public
         * @return ImagickCropper
         */
        public function getCropper()
        {
            return $this->_cropper;
        }

        /**
         * getResource
         * 
         * @access public
         * @return Imagick
         */
        public function getResource()
        {
            return $this->_resource;
        }

        /**
         * fit
         * 
         * 
         * 
         * @access public
         * @param  Integer $width
         * @param  Integer $height
         * @return String
         */
        public function fit($width, $height)
        {
            // get largest dimension
            $max = max($width, $height);

            // get maximum blob
            $blob = $this->maximum($max);
            $dimensions = $this->_resource->getImageGeometry();

            // boot cropper
            $class = get_class($this);
            $type = strstr($class, 'Resizer', true);
            $cropper = ($type) . 'Cropper';
            require_once ($cropper) . '.class.php';

            // if the width should be cropped
            if ($dimensions['height'] > $height) {

                // set x/y
                $x = 0;
                $half = round($height / 2);
                $y = $half; 
            } else {

                // set x/y
                $y = 0;
                $half = round($width / 2);
                $x = $half;
            }

            // return cropped image
            $this->_cropper = (new $cropper($blob, true));
            return $this->_cropper->crop($width, $height, $x, $y);
        }

        /**
         * maximum
         * 
         * Resizes an image to be a maximum number of pixels wide/high.
         * 
         * @access public
         * @param  integer $max
         * @return string
         */
        public function maximum($max)
        {
            $dimensions = $this->_resource->getImageGeometry();
            $max = min($max, max($dimensions['width'], $dimensions['height']));
            $this->_resource->scaleImage($max, $max, true);
            return $this->_resource->getImageBlob();
        }

        /**
         * minimum
         * 
         * Resizes an image to be a minimum number of pixels wide/high.
         * 
         * @access public
         * @param  Integer $min
         * @return String
         */
        public function minimum($min)
        {
            $dimensions = $this->_resource->getImageGeometry();
            $min = $min * (
                (
                    max($dimensions['width'], $dimensions['height']) /
                    min($dimensions['width'], $dimensions['height'])
                )
            );
            $min = round($min);
            $this->_resource->scaleImage($min, $min, true);
            return $this->_resource->getImageBlob();
        }

        /**
         * square
         * 
         * Produces a square image that is resized to be a maximum width/height
         * and is a certain number of pixels wide/high.
         * 
         * @notes  resizes local methods to resize image before cropping it
         * @access public
         * @param  integer $pixels
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
            $blob = $this->maximum($max);

            $this->_cropper = (new $cropper($blob, true));
            return $this->_cropper->square($pixels);
        }
    }
