<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttpvars.class.php");

    /**
     * @brief  Variables proporcionadas al script por medio de la subida de ficheros via HTTP
     *
     * Análoga a la matriz superglobal $_FILES
     *
     * Mas información:
     * http://es.php.net/manual/es/reserved.variables.php#reserved.variables.files
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 13:25
     * @version 1.0
     * @ingroup net http
     * @see qHttp
     */
    class qHttpFilesVars extends qHttpVars
    {
        function qHttpFilesVars()
        {
            $this->qHttpVars($_FILES);
        }

        /**
        *    Add function info here
        */
        function save()
        {
            $this->_save($_FILES, $this->getAsArray());
        }
    }
?>
