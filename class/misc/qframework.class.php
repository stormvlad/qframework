<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    define("QFRAMEWORK_VERSION", "0.9b");

    /** 
     * @defgroup misc Miscelánea
     * @brief Grupo de clases con funciones de cálculo y soporte.
     *
     * @author  qDevel - info@qdevel.com
     * @date    06/03/2005 18:19
     * @version 1.0
     */

    /**
     * @brief Clase estática con información del qFramework
     *
     * @author  qDevel - info@qdevel.com
     * @date    06/03/2005 18:19
     * @version 1.0
     * @ingroup misc
     */
    class qFramework extends qObject
    {
        /**
         * @brief Devuelve el número de versión del qFramework
         *
         * @returns string Número de versión
         */
        function getVersion()
        {
            return QFRAMEWORK_VERSION;
        }
    }
?>
