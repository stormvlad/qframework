<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qvalidator.class.php");
    
    define("DEFAULT_CHECK_EMAIL_ADDRESS", false);
    
    define("EMAIL_SIMPLE_FORMAT", 2);
    define("EMAIL_COMPLETE_FORMAT", 4);
    define("EMAIL_ANY_FORMAT", EMAIL_SIMPLE_FORMAT | EMAIL_COMPLETE_FORMAT);

    /**
     * @brief Comprueba que se sigue un formato de email y se comprueba posteriormente online
     *
     * @author  qDevel - info@qdevel.com
     * @date    05/03/2005 19:22
     * @version 1.0
     * @ingroup validation validator
     */
    class qEmailValidator extends qValidator
    {
        var $_format;
        
        function qEmailValidator($checkEmailAddress = DEFAULT_CHECK_EMAIL_ADDRESS, $format = EMAIL_SIMPLE_FORMAT)
        {
            $this->qValidator();
            $this->_format = $format;

            if ($checkEmailAddress)
            {
                include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qemaildnsrule.class.php");
                $this->addRule(new qEmailDnsRule());
            }
        }

        /**
         * Add function info here
         */
        function _validateEmailFormat($value)
        {
            include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qemailformatrule.class.php");
            $rule = new qEmailFormatRule();

            if (!$rule->validate($value))
            {
                $this->setError($rule->getError());
                return false; 
            }

            return true;
        }

        /**
         * Add function info here
         */
        function _validateEmailCompleteFormat($value)
        {
            include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qemailcompleteformatrule.class.php");
            $rule = new qEmailCompleteFormatRule();

            if (!$rule->validate($value))
            {
                $this->setError($rule->getError());
                return false; 
            }

            return true;
        }
        
        /**
         * Add function info here
         */
        function validate($value, $field = null)
        {
            if ($this->_format == EMAIL_ANY_FORMAT)
            {
                if ($this->_validateEmailFormat($value))
                {
                    return parent::validate($value);
                }

                if ($this->_validateEmailCompleteFormat($value))
                {
                    return parent::validate($value);
                }

                return false;
            }
            elseif (($this->_format & EMAIL_SIMPLE_FORMAT) == EMAIL_SIMPLE_FORMAT)
            {
                if ($this->_validateEmailFormat($value))
                {
                    return parent::validate($value);
                }

                return false;
            }
            elseif (($this->_format & EMAIL_COMPLETE_FORMAT) == EMAIL_COMPLETE_FORMAT)
            {
                if ($this->_validateEmailCompleteFormat($value))
                {
                    return parent::validate($value);
                }

                return false;
            }

            return true;
        }
    }
?>
