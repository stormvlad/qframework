<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qfileupload.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/misc/qimage.class.php");

    define("IMAGE_DEFAULT_OUTPUT_QUALITY", 75);

    /**
    * File upload class
    */
    class qImageUpload extends qFileUpload
    {
        var $_maxWidth;
        var $_maxHeight;
        var $_outputQuality;
        var $_destinationSizedFileName;

        /**
        * Constructor.
        */
        function qImageUpload($name, $directory)
        {
            $this->qFileUpload($name, $directory);

            $this->_maxWidth      = 0;
            $this->_maxHeight     = 0;
            $this->_outputQuality = IMAGE_DEFAULT_OUTPUT_QUALITY;

            $this->_destinationSizedFileName = null;

            $this->setMode(0777);
        }

        /**
        * Add function here
        */
        function getMaxWidth()
        {
            return $this->_maxWidth;
        }

        /**
        * Add function here
        */
        function setMaxWidth($width)
        {
            $this->_maxWidth = $width;
        }

        /**
        * Add function here
        */
        function getMaxHeight()
        {
            return $this->_maxHeight;
        }

        /**
        * Add function here
        */
        function setMaxHeight($height)
        {
            $this->_maxHeight = $height;
        }

        /**
        * Add function here
        */
        function setMaxSizes($width, $height)
        {
            $this->_maxWidth = $width;
            $this->_maxHeight = $height;
        }

        /**
        * Add function here
        */
        function getOutputQuality()
        {
            return $this->_outputQuality;
        }

        /**
        * Add function here
        */
        function setOutputQuality($quality)
        {
            $this->_outputQuality = $quality;
        }

        /**
        * Add function here
        */
        function getDestinationSizedFileName()
        {
            return $this->_destinationSizedFileName;
        }

        /**
        * Add function here
        */
        function setDestinationSizedFileName($fileName)
        {
            $this->_destinationSizedFileName = $fileName;
        }

        /**
        * Add function here
        */
        function save()
        {
            if (!parent::save())
            {
                return false;
            }

            if (empty($this->_maxWidth) || empty($this->_maxHeight))
            {
                return true;
            }

            $dir  = $this->getDestinationDirectory();
            $file = $dir . $this->getDestinationFileName();
            $img  = new qImage($file);

            $img->setOutputTemplateFileName("{%n}.thumbnail.{%w}x{%h}.{%e}");
            $img->setOutputDirectory($dir);

            if (!($outputFileName = $img->generateSizedImage($this->_maxWidth, $this->_maxHeight, false)))
            {
                return false;
            }

            chmod($dir . $outputFileName, $this->getMode());
            $this->setDestinationSizedFileName($outputFileName);
            return true;
        }
    }

?>