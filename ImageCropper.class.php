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
    }
