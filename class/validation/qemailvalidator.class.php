<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qvalidator.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qemailformatrule.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qemaildnsrule.class.php");

    define("DEFAULT_CHECK_EMAIL_ADDRESS", false);

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
