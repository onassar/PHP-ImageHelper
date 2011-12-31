<?php

    // dependecy check
    if (!in_array('imagick', get_loaded_extensions())) {
        throw new Exception('Imagick extension needs to be installed.');
    }

    // load dependency
    require_once 'ImageCropper.class.php';

    /**
     * ImagickCropper
     * 
     * Provides Imagick-specific code to crop/square an image.
     * 
     * @author  Oliver Nassar <onassar@gmail.com>
     * @extends ImageCropper
     */
    class ImagickCropper extends ImageCropper
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
        }

        /**
         * crop
         * 
         * @access public
         * @param  integer $width
         * @param  integer $height
         * @param  integer $x. (default: 0)
         * @param  integer $y. (default: 0)
         * @return string
         */
        public function crop($width, $height, $x = 0, $y = 0)
        {
            $this->_resource->cropImage($width, $height, $x, $y);
            return $this->_resource->getImageBlob();
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
         * square
         * 
         * @access public
         * @param  integer $pixels
         * @return string
         */
        public function square($pixels)
        {
            // minimum pixels not specified
            if ((int) $pixels < 1) {
                throw new Exception(
                    'Cropping an image requires dimensions of at least 1x1'
                );
            }

            // grab dimensions
            $dimensions = $this->_resource->getImageGeometry();
            $pixels = $this->getSquarePixels($pixels, $dimensions);

            // grab centering coordinates
            $coordinates = $this->getSquareCoordinates(
                $dimensions['width'],
                $dimensions['height'],
                $pixels
            );

            // make square crop
            return $this->crop(
                $pixels,
                $pixels,
                $coordinates['x'],
                $coordinates['y']
            );
        }
    }
