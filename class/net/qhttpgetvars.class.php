<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttpvars.class.php");

    /**
     * @brief Variables proporcionadas al script por medio de HTTP GET
     *
     * Mas información:
     * http://es.php.net/manual/es/reserved.variables.php#reserved.variables.get
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 13:25
     * @version 1.0
     * @ingroup net http
     * @see qHttp
     */
    class qHttpGetVars extends qHttpVars
    {
        function qHttpGetVars()
        {
            $this->qHttpVars($_GET);
        }

        /**
        *    Add function info here
        */
        function save()
        {
            $this->_save($_GET, $this->getAsArray());
        }
    }
?>
