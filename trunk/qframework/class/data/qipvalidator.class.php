<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qvalidator.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qipformatrule.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qiprangerule.class.php");

    /**
     * Extends the validator class to determine wether an email address is valid or not.
     */
    class qIpValidator extends qValidator
    {
        function qIpValidator($cidr = null)
        {
            $this->qValidator();

            $this->addRule(new qIpFormatRule());

            if (!empty($cidr))
            {
                $this->addRule(new qIpRangeRule($cidr));
            }
        }
    }
?>
