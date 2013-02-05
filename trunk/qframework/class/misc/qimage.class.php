<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qfile.class.php");

    /**
     * @brief Creación de previsualizaciones (thumbnails)
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 17:53
     * @version 1.0
     * @ingroup misc
     */
    class qImage extends qFile
    {
        var $_type;
        var $_width;
        var $_height;

        var $_outputTemplateFileName;
        var $_outputFileName;
        var $_outputDirectory;
        var $_outputQuality;
        var $_outputType;

        /**
        * Constructor.
        */
        function qImage($fileName)
        {
            $this->qFile($fileName);

            $this->_width    = 0;
            $this->_height   = 0;
            $this->_type     = null;

            $this->_getImageDetails($fileName);

            $this->_outputTemplateFileName  = "{%n}.sized.{%w}x{%h}.{%e}";
            $this->_outputFileName          = false;
            $this->_outputDirectory         = "./tmp/";
            $this->_outputQuality           = 75;
            $this->_outputType              = null;
        }

        /**
        * Add function here
        */
        function getWidth()
        {
            return $this->_width;
        }

        /**
        * Add function here
        */
        function getHeight()
        {
            return $this->_height;
        }

        /**
        * Add function here
        */
        function getWidthFromHeight($height)
        {
            $ratio = $height / $this->getHeight();
            return number_format($this->getWidth() * $ratio, 2);
        }

        /**
        * Add function here
        */
        function getHeightFromWidth($width)
        {
            $ratio = $width / $this->getWidth();
            return number_format($this->getHeight() * $ratio, 2);
        }
        
        /**
        * Add function here
        */
        function getType()
        {
            return $this->_type;
        }

        /**
        * Add function here
        */
        function getOutputTemplateFileName()
        {
            return $this->_outputTemplateFileName;
        }

        /**
        * Add function here
        */
        function setOutputTemplateFileName($template)
        {
            $this->_outputTemplateFileName = $template;
        }

        /**
        * Add function here
        */
        function getOutputFileName()
        {
            return $this->_outputFileName;
        }

        /**
        * Add function here
        */
        function setOutputFileName($fileName)
        {
            $this->_outputFileName = $fileName;
        }

        /**
        * Add function here
        */
        function getOutputDirectory()
        {
            return $this->_outputDirectory;
        }

        /**
        * Add function here
        */
        function setOutputDirectory($dir)
        {
            if (substr($dir, -1) != "/")
            {
                $dir .= "/";
            }

            $this->_outputDirectory = $dir;
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
        function getOutputType()
        {
            if (empty($this->_outputType))
            {
                return $this->getType();
            }
            else
            {
                return $this->_outputType;
            }
        }

        /**
        * Add function here
        */
        function setOutputType($type)
        {
            $this->_outputType = $type;
        }

        /**
        * Add function info here
        */
        function _calcSizedSizes($width, $height, $maxWidth, $maxHeight, $exact)
        {
            if ($width <= $maxWidth && $height <= $maxHeight)
            {
                return array($maxWidth, $maxHeight);
            }
            
            $ratioWidth  = $maxWidth / $width;
            $ratioHeight = $maxHeight / $height;

            if ($exact)
            {
                if ($ratioWidth <= $ratioHeight)
                {
                    $aspectRatio = $ratioHeight;
                }
                else
                {
                    $aspectRatio = $ratioWidth;
                }
            }
            else
            {
                if ($ratioWidth <= $ratioHeight)
                {
                    $aspectRatio = $ratioWidth;
                }
                else
                {
                    $aspectRatio = $ratioHeight;
                }
            }
            
            $newWidth    = round($width * $aspectRatio);
            $newHeight   = round($height * $aspectRatio);
            $size        = $newHeight;
            $aspectRatio = $height / $width;

            if ($height <= $size)
            {
                $newWidth  = $width;
                $newHeight = $height;
            }
            else
            {
                $newHeight = $size;
                $newWidth  = round($newHeight / $aspectRatio);
            }

            return array($newWidth, $newHeight);
        }

        /**
        *
        */
        function _applyReplaces($width, $height)
        {
            $fileName  = $this->getFileName();
            $extension = $this->getExtension();
            $template  = $this->getOutputTemplateFileName();

            $template  = str_replace("{%n}", basename($fileName, "." . $extension), $template);
            $template  = str_replace("{%s}", strtolower(preg_replace("/[^[:alnum:]+]/","", basename($fileName, "." . $extension))), $template);
            $template  = str_replace("{%e}", $extension, $template);
            $template  = str_replace("{%t}", $this->getOutputType(), $template);
            $template  = str_replace("{%w}", $width, $template);
            $template  = str_replace("{%h}", $height, $template);

            return $template;
        }

        /**
        * Add function info here
        */
        function generateSizedImage($width, $height, $exact = true, $overwrite = true, $color = "ffffff")
        {
            $oWidth    = $this->getWidth();
            $oHeight   = $this->getHeight();
            $newSizes  = $this->_calcSizedSizes($oWidth, $oHeight, $width, $height, $exact);
            $newWidth  = $newSizes[0];
            $newHeight = $newSizes[1];
            $file      = $this->getFileName();
            $extension = $this->getExtension();
            $hexColor  = hexdec($color); 
            
            
            switch ($this->getType())
            {
                case "gif":
                    $src = imageCreateFromGif($file);
                    break;

                case "jpeg":
                    $src = imageCreateFromJpeg($file);
                    break;

                case "png":
                    $src = imageCreateFromPng($file);
                    break;
            }

            $img = imageCreateTrueColor($newWidth, $newHeight);            
            imageFill($img, 0, 0, imageColorAllocate($img, 0xFF & ($hexColor >> 0x10), 0xFF & ($hexColor >> 0x8), 0xFF & $hexColor));
            
            if ($newWidth >= $oWidth && $newHeight >= $oHeight)
            {
                if (empty($exact))
                {
                    $img = imageCreateTrueColor($oWidth, $oHeight);            
                    imageFill($img, 0, 0, imageColorAllocate($img, 0xFF & ($hexColor >> 0x10), 0xFF & ($hexColor >> 0x8), 0xFF & $hexColor));
                    imagecopy($img, $src, 0, 0, 0, 0, $oWidth, $oHeight);
                }
                else
                {
                    $x = round(($newWidth - $oWidth) / 2);
                    $y = round(($newHeight - $oHeight) / 2);
                    
                    imageCopyResampled($img, $src, $x, $y, 0, 0, $oWidth, $oHeight, $oWidth, $oHeight);
                }
            }
            else
            {
                imageCopyResampled($img, $src, 0, 0, 0, 0, $newWidth, $newHeight, $oWidth, $oHeight);
            }

            if (!empty($exact))
            {
                $x = 0;
                $y = 0;
                
                if ($newWidth > $width)
                {
                    $x = round(($newWidth - $width) / 2);
                }

                if ($newHeight > $height)
                {
                    $y = round(($newHeight - $height) / 2);
                }

                $img2 = imageCreateTrueColor($width, $height);
                imageFill($img2, 0, 0, imageColorAllocate($img2, 0xFF & ($hexColor >> 0x10), 0xFF & ($hexColor >> 0x8), 0xFF & $hexColor));
                imageCopyResampled($img2, $img, 0, 0, $x, $y, $width, $height, $width, $height);
                
                $img       = $img2;
                $newWidth  = $width;
                $newHeight = $height;
            }

            if (empty($this->_outputFileName))
            {
                $outputFileName = $this->_applyReplaces($newWidth, $newHeight);
            }
            else
            {
                $outputFileName = $this->_outputFileName;
            }
            
            $fileName = $this->getOutputDirectory() . $outputFileName;
            
            if (is_file($fileName) && empty($overwrite))
            {
                // Do nothing. We don't want overwrite ;-)
            }
            else
            {
                if (is_file($fileName) && !empty($overwrite))
                {
                    unlink($fileName);
                }
                
                switch ($this->getOutputType())
                {
                    case "gif":
                        imageGif($img, $fileName);
                        break;
    
                    case "jpeg":
                        imageJpeg($img, $fileName, $this->getOutputQuality());
                        break;
    
                    case "png":
                        imagePng($img, $fileName);
                        break;
                }
            }
            
            imagedestroy($img);
            return $outputFileName;
        }

        /**
        * Add function info here
        */
        function _getImageDetails($fileName)
        {
            if (is_file($fileName) && is_readable($fileName))
            {
                $sizes = getImageSize($fileName);

                $this->_width    = $sizes[0];
                $this->_height   = $sizes[1];

                switch ($sizes[2])
                {
                    case 1:
                        $this->_type = "gif";
                        break;
                    case 2:
                        $this->_type = "jpeg";
                        break;
                    case 3:
                        $this->_type = "png";
                        break;
                    case 4:
                        $this->_type = "swf";
                        break;
                    case 5:
                        $this->_type = "psd";
                        break;
                    case 6:
                        $this->_type = "bmp";
                        break;
                    case 7:
                    case 8:
                        $this->_type = "tiff";
                        break;
                }
            }
        }
    }

?>