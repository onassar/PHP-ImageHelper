<?php

    /**
     * ImageCropper
     * 
     * Provides general methods for calculating how to square an image (eg.
     * coordinates-system).
     * 
     * @author Oliver Nassar <onassar@gmail.com>
     * @notes  Image library independent
     */
    class ImageCropper
    {
        /**
         * resource
         * 
         * @var    resource
         * @access protected
         */
        protected $_resource;

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
            if (!$blob) {
                if (!is_file($image)) {
                    throw new Exception(
                        'Image specified by path *' . ($image) . '* does not' .
                        'correspond to a real file.'
                    );
                }
            }
        }

        /**
         * getSquareCoordinates
         * 
         * Returns the x and y coordinates to be used to square an image.
         * 
         * @access protected
         * @param  integer|float $width
         * @param  integer|float $height
         * @param  integer|float $pixels
         * @return array
         */
        protected function getSquareCoordinates($width, $height, $pixels)
        {
            $x = floor(($width - $pixels) / 2);
            $y = floor(($height - $pixels) / 2);
            return array(
                'x' => $x,
                'y' => $y
            );
        }

        /**
         * getSquarePixels
         * 
         * Determines the pixels for a square crop by determining whether the
         * passed in pixels or the image are smaller.
         * 
         * @access protected
         * @param  integer $pixels
         * @param  array $dimensions
         * @return integer
         */
        protected function getSquarePixels($pixels, array $dimensions)
        {
            return min(
                $dimensions['width'],
                $dimensions['height'],
                $pixels
            );
        }

        /**
         * _getTrimCoordinates
         * 
         * Determines the crop positions for the image-resource that the
         * instance was instantiated under by seeing if the determining if the
         * resource needs to be cropped horizontally or vertically.
         * 
         * Presumes that the image has one of it's dimensions (width or height)
         * already set flush to the respective <$width> or <$height> value that
         * it is been trimmed to.
         * 
         * @access protected
         * @param  Integer $width
         * @param  Integer $height
         * @return Array
         */
        protected function _getTrimCoordinates($width, $height)
        {
            // grab dimensions of resource being cropped
            $dimensions = $this->_resource->getImageGeometry();

            // if the width requirement for the trim has been met
            if ($dimensions['width'] === $width) {

                // set the crop position to do-away with the vertical spacing
                $x = 0;
                $y = round(($dimensions['height'] - $height) / 2);
            }
            // otherwise the height requirement for the trim has been met
            else {

                // set the crop position to do-away with the horizontal spacing
                $x = round(($dimensions['width'] - $width) / 2);
                $y = 0;
            }
            return array(
                'x' => $x,
                'y' => $y
            );
        }
    }
