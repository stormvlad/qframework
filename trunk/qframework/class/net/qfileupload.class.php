<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
    * File upload class
    */
    class qFileUpload extends qObject
    {
        var $_name;
        var $_destinationFileName;
        var $_destinationDirectory;
        var $_mode;

        /**
        * Constructor.
        */
        function qFileUpload($name, $directory)
        {
            $this->qObject();

            $this->_name = $name;
            $this->_mode = 0644;
            $this->_destinationFileName  = null;
            $this->_destinationDirectory = $directory;

            if (substr($directory, -1) != "/")
            {
                $this->_destinationDirectory .= "/";
            }
        }

        /**
        * Add function here
        */
        function getName()
        {
            return $this->_name;
        }

        /**
        * Add function here
        */
        function setName($name)
        {
            $this->_name = $name;
        }

        /**
        * Add function here
        */
        function getMode()
        {
            return $this->_mode;
        }

        /**
        * Add function here
        */
        function setMode($mode)
        {
            $this->_mode = $mode;
        }

        /**
        * Add function here
        */
        function getDestinationDirectory()
        {
            return $this->_destinationDirectory;
        }

        /**
        * Add function here
        */
        function setDestinationDirectory($directory)
        {
            $this->_destinationDirectory = $directory;
        }

        /**
        * Add function here
        */
        function getDestinationFileName()
        {
            if (empty($this->_destinationFileName))
            {
                $files = &qHttp::getFilesVars();
                $file  = $files->getValue($this->_name);

                return $file["name"];
            }

            return $this->_destinationFileName;
        }

        /**
        * Add function here
        */
        function setDestinationFileName($fileName)
        {
            $this->_destinationFileName = $fileName;
        }

        /**
        * Add function here
        */
        function save()
        {
            $files = &qHttp::getFilesVars();

            if (!$files->keyExists($this->_name))
            {
                return false;
            }

            $file = $files->getValue($this->_name);
            $dst  = $this->getDestinationFileName();

            if (!is_dir($this->_destinationDirectory))
            {
                mkdir($this->_destinationDirectory, $this->getMode());
            }

            if (!move_uploaded_file($file["tmp_name"], $this->_destinationDirectory . $dst))
            {
                throw(new qException("qFileUpload::save: Error moving upload tmp file '" . $file["tmp_name"] . "' to '" . $this->_destinationDirectory . $dst . "'."));
                die();
            }

            chmod($this->_destinationDirectory . $dst, $this->_mode);
            return true;
        }
    }

?>