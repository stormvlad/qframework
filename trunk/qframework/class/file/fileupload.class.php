<?php

    /**
     * Object representation of a file upload.
     * Wraps around the values in the $_FILES or $HTTP_POST_FILES array.
     */
    class FileUpload extends Object {

        var $_name;
        var $_mimeType;
        var $_tmpName;
        var $_error;
        var $_size;
        var $_folder;

        /**
         * Constructor. Takes as a parameter a position of the $_FILES array.
         *
         * @param uploadInfo An associative array with information about the file uploaded.
         */
        function FileUpload( $uploadInfo )
        {
            $this->_name     = $uploadInfo["name"];
            $this->_mimeType = $uploadInfo["type"];
            $this->_tmpName  = $uploadInfo["tmp_name"];
            $this->_size     = $uploadInfo["size"];
            $this->_error    = $uploadInfo["error"];
            $this->_folder   = null;
        }

        function getFileName()
        {
            return $this->_name;
        }

        function getMimeType()
        {
            return $this->_mimeType;
        }

        function getTmpName()
        {
            return $this->_tmpName;
        }

        function getError()
        {
            return $this->_error;
        }

        function setError( $error )
        {
            $this->_error = $error;
        }

        function getSize()
        {
            return $this->_size;
        }

        function setFolder( $folder )
        {
            $this->_folder = $folder;
        }

        function getFolder()
        {
            return $this->_folder;
        }
    }
?>
