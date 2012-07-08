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
    }
