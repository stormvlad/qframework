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
        function qEmailValidator($checkEmailAddress = DEFAULT_CHECK_EMAIL_ADDRESS)
        {
            $this->qValidator();

            $this->addRule(new qEmailFormatRule());

            if ($checkEmailAddress)
            {
                $this->addRule(new qEmailDnsRule());
            }
        }
    }
?>
