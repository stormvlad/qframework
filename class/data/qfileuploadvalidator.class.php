<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qvalidator.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qregexprule.class.php");

    define("ERROR_VALIDATOR_FILE_UPLOAD_SIZE", "error_validator_file_upload_size");
    define("ERROR_VALIDATOR_FILE_UPLOAD_PARTIAL", "error_validator_file_upload_partial");
    define("ERROR_VALIDATOR_FILE_UPLOAD_EXTENSION", "error_validator_file_upload_extension");
    define("ERROR_VALIDATOR_FILE_UPLOAD_UNKNOWN", "error_validator_file_upload_unknown");

    /**
     * Extends the validator class to determine wether an email address is valid or not.
     */
    class qFileUploadValidator extends qValidator
    {
        var $_validExtensions;

        /**
        * Constructor
        */
        function qFileUploadValidator($extensions = null)
        {
            $this->qValidator();
            $this->setValidExtensions($extensions);
        }

        /**
        * Add function info here
        */
        function getValidExtensions()
        {
            return $this->_validExtensions;
        }

        /**
        * Add function info here
        */
        function setValidExtensions($extensions)
        {
            if (empty($extensions))
            {
                $this->_validExtensions = array();
            }
            else if (is_array($extensions))
            {
                $this->_validExtensions = array();

                foreach ($extensions as $extension)
                {
                    $this->_validExtensions[] = strtolower(trim($extension));
                }
            }
            else if (is_string($extensions) && ereg("(\\*\\.[^*.|]+)([|,;:]\\*\\.[^*.|]+)*", $extensions))
            {
                $this->_validExtensions = array();
                $extensions = split("[|,;:]", $extensions);

                foreach ($extensions as $extension)
                {
                    $this->_validExtensions[] = strtolower(substr(trim($extension), 2));
                }
            }
            else if (is_string($extensions) && ereg("([^|]+)(|,;:][^|]+)*", $extensions))
            {
                $this->_validExtensions = array();
                $extensions = explode("[|,;:]", $extensions);

                foreach ($extensions as $extension)
                {
                    $this->_validExtensions[] = strtolower(trim($extension));
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
        function isValidExtension($extension)
        {
            return in_array(strtolower($extension), $this->_validExtensions);
        }

        /**
        * Add function info here
        */
        function validate($value)
        {
            if (is_array($value))
            {
                if ($value["error"] > 0)
                {
                    switch ($value["error"])
                    {
                        case 4:
                            return true;
                        case 1:
                        case 2:
                            $this->setError(ERROR_VALIDATOR_FILE_UPLOAD_SIZE);
                            return false;

                        case 3:
                            $this->setError(ERROR_VALIDATOR_FILE_UPLOAD_PARTIAL);
                            return false;

                        /*case 4:
                            $this->addError($locale->i18n("products_error_upload_empty"), "image");
                            return false;*/

                        default:
                            $this->setError(ERROR_VALIDATOR_FILE_UPLOAD_UNKNOWN);
                            return false;
                    }
                }

                if (!$this->isValidExtension(qFile::getExtension($value["name"])))
                {
                    $this->setError(ERROR_VALIDATOR_FILE_UPLOAD_EXTENSION);
                    return false;
                }

                return true;
            }

            return false;
        }
    }
?>