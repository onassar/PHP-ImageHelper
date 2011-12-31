<?php

    // load dependency
    require_once 'ImagickResizer.class.php';

    /**
     * JPEGResizer
     * 
     * @author  Oliver Nassar <onassar@gmail.com>
     * @extends ImagickResizer
     */
    class JPEGResizer extends ImagickResizer
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
