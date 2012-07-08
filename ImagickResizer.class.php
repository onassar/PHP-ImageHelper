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
            /**
             * It's been known to happe that the <width> and <height> arguments
             * get passed in as a string. Cast as integer. This became a problem
             * in the <ImagickCropper> instance
             */

            // cast
            $width = (int) $width;
            $height = (int) $height;

            // get source dimensions; evaluate ratios
            $dimensions = $this->_resource->getImageGeometry();
            $source = $dimensions['width'] / $dimensions['height'];
            $fit = $width / $height;
            $dimension = max($width, $height);

            // if the image ought to be resized to a maximum dimension
            if (
                ($source > 1 && $fit > 1)
                || ($source < 1 && $fit < 1)
            ) {

                // resize it
                $blob = $this->maximum($dimension);
            }
            // otherwise, resize the image to a minimum dimension
            else {

                // resize it
                $blob = $this->minimum($dimension);
            }

            // boot cropper
            $class = get_class($this);
            $type = strstr($class, 'Resizer', true);
            $cropper = ($type) . 'Cropper';
            require_once ($cropper) . '.class.php';

            // return trimmed image
            $this->_cropper = (new $cropper($blob, true));
            return $this->_cropper->trim($width, $height);

            // landscape-oriented
            if ($width > $height) {

                // get minimum blob
                $dimension = max($width, $height);
prx($dimension);
                $blob = $this->minimum($dimension);

                // set x/y
                $y = round($height / 2);
                $x = 0;
            }
            // portrait-oriented
            else {

                // get minimum blob
                $dimension = max($width, $height);
                $blob = $this->maximum($dimension);

                // set x/y
                $y = round($height / 2);
                $x = 0;
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
