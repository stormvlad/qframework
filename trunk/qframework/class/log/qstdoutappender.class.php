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
         * @param layout A Layout instance.
         */
        function &qStdoutAppender ($layout)
        {
            parent::qAppender($layout);
        }

        /**
         * Write a message directly to the requesting client.
         *
         * @note This should never be called manually.
         * @param message string The message to be written.
         *
         * @public
         * @since  1.0
         */
        function write ($message)
        {
            echo $message . "<br/>\n";
        }
    }

?>