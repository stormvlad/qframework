<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    define("QFRAMEWORK_VERSION", "0.9b");

    /** 
     * @defgroup misc Miscel�nea
     * @brief Grupo de clases con funciones de c�lculo y soporte.
     *
     * @author  qDevel - info@qdevel.com
     * @date    06/03/2005 18:19
     * @version 1.0
     */

    /**
     * @brief Clase est�tica con informaci�n del qFramework
     *
     * @author  qDevel - info@qdevel.com
     * @date    06/03/2005 18:19
     * @version 1.0
     * @ingroup misc
     */
    class qFramework extends qObject
    {
        /**
         * @brief Devuelve el n�mero de versi�n del qFramework
         *
         * @returns string N�mero de versi�n
         */
        function getVersion()
        {
            return QFRAMEWORK_VERSION;
        }
    }
?>
