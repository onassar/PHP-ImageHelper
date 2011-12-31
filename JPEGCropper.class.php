<?php

    // load dependency
    require_once 'ImagickCropper.class.php';

    /**
     * JPEGCropper
     * 
     * @author  Oliver Nassar <onassar@gmail.com>
     * @extends ImagickCropper
     */
    class JPEGCropper extends ImagickCropper
    {
        /**
         * __construct
         * 
         * @access public
         * @param  string $image path to file or image contents (aka. blob)
         * @param  boolean $blob. (default: false)
         * @return void
         */
        public function __construct($image, $blob = false)
        {
            parent::__construct($image, $blob);
        }
    }
