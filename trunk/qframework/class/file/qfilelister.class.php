<?php

     include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * @brief Servicio de listado de archivos
     * 
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 13:59
     * @version 1.0
     * @ingroup file
     * @see qFileList
     */
     class qFileLister extends qObject
     {
        /**
        *    Add function info here
        */
        function qFileLister()
        {
            $this->qObject();
        }

        /**
        *  Add function info here
        */
        function ls($dir = null)
        {
            throw(new qException("qFileLister::ls: This method must be implemented by child classes."));
            die();
        }
     }
?>
