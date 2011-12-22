<?php

    // dependecy checks
    if (!in_array('fileinfo', get_loaded_extensions())) {
        throw new Exception('Fileinfo extension needs to be installed.');
    }

    /**
     * Image
     * 
     * Acts as a wrapper for file-type-independent image resizing and squaring.
     * 
     * @author  Oliver Nassar <onassar@gmail.com>
     * @example
     * <code>
     *     require_once APP . '/vendors/PHP-ImageHelper/Image.class.php';
     *     $image = (new Image(APP . '/webroot/kittens.jpg'));
     *     header('Content-Type: image/jpeg');
     *     echo $image->square(100);
     *     exit(0);
     * </code>
     */
    class Image
    {
        /**
         * _map
         * 
         * @var array
         * @access protected
         */
        protected $_map = array(
            'image/jpeg' => 'JPEGResizer',
            'image/jpg' => 'JPEGResizer',
            'image/png' => 'PNGResizer'
        );

        /**
         * _path
         * 
         * @var string
         * @access protected
         */
        protected $_path;

        /**
         * _resource
         * 
         * @var resource
         * @access protected
         */
        protected $_resource;

        /**
         * _type
         * 
         * @var string
         * @access protected
         */
        protected $_type;

        /**
         * __construct function.
         * 
         * @access public
         * @param string $path path to a local file
         * @return void
         */
        public function __construct($path)
        {
            if (!is_file($path)) {
                throw new Exception(
                    'Image *' . ($path) . '* is not a valid file.'
                );
            }
            $this->_path = $path;
            $this->_loadResource();
        }

        /**
         * __destruct function.
         * 
         * @access public
         * @return void
         */
        public function __destruct()
        {
            unset($this->_resource);
        }

        /**
         * _getFileType function.
         * 
         * @access protected
         * @return string
         */
        protected function _getFileType()
        {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $this->_type = finfo_file($finfo, $this->_path);
            return $this->_type;
        }

        /**
         * _loadResource function.
         * 
         * @access protected
         * @return void
         */
        protected function _loadResource()
        {
            // type enforcement
            $type = $this->_getFileType();
            $keys = array_keys($this->_map);
            if (!in_array($type, $keys)) {
                throw new Exception(
                    'Invalid image type being processed. Image *' .
                    $this->_path . '* is of type *' . ($type) . '* and must ' .
                    'be one of the following: ' . implode(', ', $keys)
                );
            }

            // store resource
            require_once ($this->_map[$type]) . '.class.php';
            $this->_resource = (new $this->_map[$type]($this->_path));

        }

        /**
         * getResizer function.
         * 
         * @access public
         * @return JPEGResizer
         */
        public function getResizer()
        {
            return $this->_resource;
        }

        /**
         * getType function.
         * 
         * @access public
         * @return string
         */
        public function getType()
        {
            return $this->_type;
        }

        /**
         * resize function. Resizes the instantiated image to have a maximum
         *     width/height as passed.
         * 
         * @access public
         * @param int $max
         * @return string
         */
        public function resize($max)
        {
            return $this->_resource->resize($max);
        }

        /**
         * square function. Crops the instantiated image to have either a width
         *     or height (whichever is smaller) to have a value of <$pixels>.
         *     The opposite dimension is scaled and cropped appropriately.
         * 
         * @access public
         * @param int $pixels
         * @return string
         */
        public function square($pixels)
        {
            return $this->_resource->square($pixels);
        }
    }
