<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qvalidator.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qipformatrule.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qiprangerule.class.php");

    /**
     * @brief Comprueba que el dato tiene formato de IP o IP/máscara.
     *
     * @author  qDevel - info@qdevel.com
     * @date    05/03/2005 19:22
     * @version 1.0
     * @ingroup validation validator
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
