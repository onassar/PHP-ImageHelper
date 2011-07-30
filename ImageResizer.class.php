<?php

    /**
     * ImageResizer class.
     * 
     */
    class ImageResizer
    {
        /**
         * cropper
         * 
         * @var ImagickCropper
         * @access protected
         */
        protected $_cropper;

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
         * @param string $image
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
    }

?>
