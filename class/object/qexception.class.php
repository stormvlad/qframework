<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    if (PHP_VERSION < 5) include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/exception.class.php");

    /**
     * @brief Clase de abstración para la excepciones en el qFramework
     *
     * @author  qDevel - info@qdevel.com
     * @date    06/03/2005 18:39
     * @version 1.0
     * @ingroup core
     */
    class qException extends Exception
    {
    }

?>
