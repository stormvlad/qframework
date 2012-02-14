<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * @brief Manejador de subida de ficheros
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 16:14
     * @version 1.0
     * @ingroup misc
     * @see qFileUploadValidator
     */
    class qFileUpload extends qObject
    {
        var $_name;
        var $_destinationFileName;
        var $_destinationDirectory;
        var $_mode;

        var $_useMoveUploadedFile;

        /**
        * Constructor.
        */
        function qFileUpload($name, $directory, $useMoveUploadedFile = true)
        {
            $this->qObject();

            $this->_name = $name;
            $this->_mode = 0755;
            $this->_destinationFileName  = null;
            $this->_destinationDirectory = $directory;
            $this->_useMoveUploadedFile  = $useMoveUploadedFile;

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
        function getUseMoveUploadedFile()
        {
            return $this->_useMoveUploadedFile;
        }

        /**
        * Add function here
        */
        function setUseMoveUploadedFile($value)
        {
            $this->_useMoveUploadedFile = $value;
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

            if ($this->getUseMoveUploadedFile())
            {
                if (!move_uploaded_file($file["tmp_name"], $this->_destinationDirectory . $dst))
                {
                    trigger_error("Error moving upload tmp file '" . $file["tmp_name"] . "' to '" . $this->_destinationDirectory . $dst . "'.", E_USER_ERROR);
                    return false;
                }
            }
            else
            {
                include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qfile.class.php");

                if (!qFile::rename($file["tmp_name"], $this->_destinationDirectory . $dst))
                {
                    trigger_error("Error renaming upload tmp file '" . $file["tmp_name"] . "' to '" . $this->_destinationDirectory . $dst . "'.", E_USER_ERROR);
                    return false;
                }
            }
            
            chmod($this->_destinationDirectory . $dst, $this->_mode);
            return true;
        }
    }

?>