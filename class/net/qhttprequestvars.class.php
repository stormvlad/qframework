<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttpvars.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttp.class.php");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qHttpRequestVars extends qHttpVars
    {
        function qHttpRequestVars($params = null)
        {
            $this->qHttpVars($params);
        }

        function save()
        {
            qHttp::setRequest($this->getAsArray());
        }
    }
?>
