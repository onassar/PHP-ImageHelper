<?php

    // load dependency
    require_once 'ImagickResizer.class.php';

    /**
     * PNGResizer
     * 
     * @author  Oliver Nassar <onassar@gmail.com>
     * @extends ImagickResizer
     */
    class PNGResizer extends ImagickResizer
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
