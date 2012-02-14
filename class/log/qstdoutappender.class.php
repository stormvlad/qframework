<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/log/qappender.class.php");

    /**
     * @brief Añade el mensaje directamente a la respuesta para el cliente
     *
     * @author  qDevel - info@qdevel.com
     * @date    13/03/2005 04:32
     * @version 1.0
     * @ingroup log
     */
    class qStdoutAppender extends qAppender
    {
        /**
         * Constructor
         *
         * @param layout Una instancia de qLayout (la plantilla)
         */
        function qStdoutAppender($layout)
        {
            $this->qAppender($layout);
        }

        /**
         * Escribe el mensaje directamente a la respuesta para el cliente
         *
         * @param message string El mensaje a escribir
         * @note No debe llamarse manualmente
         */
        function write($message)
        {
            print $message . "<br/>" . PHP_EOL;
        }

        /**
         * Hace un volcado de la pila de llamadas a funciones.
         */
        function writeStackTrace()
        {
            include_once(APP_ROOT_PATH . "class/misc/utils.class.php");
            print Utils::dumpStackTraceToStr();
        }
    }

?>