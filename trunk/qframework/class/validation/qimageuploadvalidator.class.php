<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qvalidator.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qregexprule.class.php");

    define("ERROR_VALIDATOR_IMAGE_UPLOAD_SIZES", "error_validator_image_upload_sizes");

    /**
     * @brief Determina cuando se ha subido una imagen sin errores.
     *
     * @author  qDevel - info@qdevel.com
     * @date    13/08/2009 11:25
     * @version 1.0
     * @ingroup validation validator
     */
    class qImageUploadValidator extends qValidator
    {
        var $_sizes;

        /**
        * Constructor
        */
        function qImageUploadValidator($sizes = null)
        {
            $this->qValidator();
            $this->setValidSizes($sizes);
        }
        
        /**
        * Add function info here
        */
        function getValidSizes()
        {
            return $this->_sizes;
        }

        /**
        * Add function info here
        */
        function setValidSizes($sizes)
        {
            if (empty($sizes))
            {
                $this->_sizes = array();
            }
            else if (is_array($sizes))
            {
                $this->_sizes = array();

                foreach ($sizes as $size)
                {
                    $this->_sizes[] = $size;
                }
            }
            else if (is_string($sizes) && preg_match("#([^|]+)(|,;:][^|]+)*#", $sizes))
            {
                include_once(APP_ROOT_PATH . "class/misc/utils.class.php");
                
                $this->_sizes = array();
                $sizes = split("[|,;:]", $sizes);

                foreach ($sizes as $size)
                {
                    $size = trim($size);
                    $this->_sizes[$size] = Utils::parseSizes($size);
                }
            }
            else
            {
                $this->_validExtensions = array();
            }
        }

        /**
        * Add function info here
        */
        function areValidSizes($width, $height)
        {
            foreach ($this->_sizes as $sizes)
            {
                if (empty($sizes["exact"]))
                {
                    if ($width < $sizes["width"] || $height < $sizes["height"])
                    {
                        return false;
                    }
                }
                else if ($width != $sizes["width"] || $height != $sizes["height"])
                {
                    return false;
                }
            }
            
            return true;
        }

        /**
        * Add function info here
        */
        function validate($value, $field = null)
        {
            if (is_array($value))
            {
                if ($value["error"] > 0 || $value["size"] == 0)
                {
                    trigger_error("You should apply qFileUploadValidator to field '" . $field . "'", E_USER_WARNING);
                    return;
                }
                else
                {
                    $sizes = getImageSize($value["tmp_name"]);

                    if (!$this->areValidSizes($sizes[0], $sizes[1]))
                    {
                        $this->setError(ERROR_VALIDATOR_IMAGE_UPLOAD_SIZES);
                        return false;
                    }
                }

                return true;
            }

            return false;
        }
    }
?>