<?php
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qproperties.class.php");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qHttpVars extends qProperties
    {
        function qHttpVars($params = null)
        {
            $this->qProperties($params);
        }
    }
?>
