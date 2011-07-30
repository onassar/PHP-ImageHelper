<?php

    // load dependency
    require_once 'ImageCropper.class.php';

    /**
     * ImagickCropper class. Provides Imagick-specific code to crop/square an
     *     image.
     * 
     * @extends ImageCropper
     */
    class ImagickCropper extends ImageCropper
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
        }

        /**
         * crop function.
         * 
         * @access public
         * @param int $width
         * @param int $height
         * @param int $x. (default: 0)
         * @param int $y. (default: 0)
         * @return string
         */
        public function crop($width, $height, $x = 0, $y = 0)
        {
            $this->_resource->cropImage($width, $height, $x, $y);
            return $this->_resource->getImageBlob();
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
         * square function.
         * 
         * @access public
         * @param int $pixels
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

?>
