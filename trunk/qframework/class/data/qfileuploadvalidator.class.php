<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qvalidator.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qregexprule.class.php");

    define("ERROR_VALIDATOR_FILE_UPLOAD_SIZE", "error_validator_file_upload_size");
    define("ERROR_VALIDATOR_FILE_UPLOAD_PARTIAL", "error_validator_file_upload_partial");
    define("ERROR_VALIDATOR_FILE_UPLOAD_UNKNOWN", "error_validator_file_upload_unknown");

    /**
     * Extends the validator class to determine wether an email address is valid or not.
     */
    class qFileUploadValidator extends qValidator
    {
        /**
        * Constructor
        */
        function qFileUploadValidator()
        {
            $this->qValidator();
        }

        /**
        * Add function info here
        */
        function validate($value)
        {
            if (is_array($value))
            {
                switch ($value["error"])
                {
                    case 0:
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

                return true;
            }

            return false;
        }
    }
?>