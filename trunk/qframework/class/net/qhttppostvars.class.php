<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttpvars.class.php");

    /**
     * @brief  Variables proporcionadas al script por medio de HTTP POST
     *
     * Mas información:
     * http://es2.php.net/manual/es/reserved.variables.php#reserved.variables.post
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 13:25
     * @version 1.0
     * @ingroup net http
     * @see qHttp
     */
    class qHttpPostVars extends qHttpVars
    {
        function qHttpPostVars()
        {
            $this->qHttpVars($_POST);
        }

        /**
        *    Add function info here
        */
        function save()
        {
            $this->_save($_POST, $this->getAsArray());
        }
    }
?>
