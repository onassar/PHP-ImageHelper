<?php

    // load dependency
    require_once 'ImagickCropper.class.php';

    /**
     * PNGCropper
     * 
     * @author  Oliver Nassar <onassar@gmail.com>
     * @extends ImagickCropper
     */
    class PNGCropper extends ImagickCropper
    {
        /**
         * __construct function.
         * 
         * @access public
         * @param string $image path to file or image contents (aka. blob)
         * @param bool $blob. (default: false)
         * @return void
         */
        public function __construct($image, $blob = false)
        {
            parent::__construct($image, $blob);
        }
    }
