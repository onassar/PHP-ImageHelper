<?php

    /**
     * ImageCropper class. Provides general methods for calculating how to
     *     square an image (eg. coordinates-system).
     * 
     * @note Image library indepdent
     */
    class ImageCropper
    {
        /**
         * resource
         * 
         * @var resource
         * @access protected
         */
        protected $_resource;

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
         * getSquareCoordinates function. Returns the x and y coordinates to be
         *     used to square an image
         * 
         * @access protected
         * @param int|float $width
         * @param int|float $height
         * @param int $pixels
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
         * getSquarePixels function. Determines the pixels for a square crop
         *     by determining whether the passed in pixels or the image are
         *     smaller
         * 
         * @access protected
         * @param int $pixels
         * @param array $dimensions
         * @return int
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

?>
