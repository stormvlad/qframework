<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qvalidator.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qemailformatrule.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qemaildnsrule.class.php");

    define(DEFAULT_CHECK_EMAIL_ADDRESS, true);

    /**
     * Extends the validator class to determine wether an email address is valid or not.
     */
    class qEmailValidator extends qValidator
    {
        var $_checkEmailAddress;

        function qEmailValidator($checkEmailAddress = DEFAULT_CHECK_EMAIL_ADDRESS)
        {
            $this->qValidator();
            $this->_checkEmailAddress = $checkEmailAddress;
        }

        /**
         * Add function here
        */
        function getCheckEmailAddress()
        {
            return $this->_checkEmailAddress;
        }

        /**
         * Add function here
        */
        function setCheckEmailAddress($enable = true)
        {
            $this->_checkEmailAddress = $enable;
        }

        /**
         * Returns true if the email address is a valid one, or false otherwise.
         *
         * @return Returns true if it's a valid address or false otherwise.
         */
        function validate($value)
        {
            $this->addRule(new qEmailFormatRule());

            if ($this->_checkEmailAddress)
            {
                $this->addRule(new qEmailDnsRule());
            }

            return parent::validate($value);
        }
    }
?>
