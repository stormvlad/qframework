<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/log/qappender.class.php");

    /**
     * qStdoutAppender logs a message directly to the requesting client.
     *
     * @since   1.0
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