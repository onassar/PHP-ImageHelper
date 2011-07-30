<?php

    // load dependency
    require_once 'ImagickCropper.class.php';

    /**
     * JPEGCropper class.
     * 
     * @extends ImagickCropper
     */
    class JPEGCropper extends ImagickCropper
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

?>
