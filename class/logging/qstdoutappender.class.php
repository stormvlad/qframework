<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/logging/qappender.class.php");

    /**
     * qStdoutAppender logs a message directly to the requesting client.
     *
     * @package qframework
     * @since   1.0
     */
    class qStdoutAppender extends qAppender
    {
        /**
         * Create a new FileAppender instance.
         *
         * @param Layout A Layout instance.
         *
         * @public
         * @since  1.0
         */
        function &qStdoutAppender ($layout)
        {
            parent::qAppender($layout);
        }

        /**
         * Write a message directly to the requesting client.
         *
         * <br/><br/>
         *
         * <note>
         *     This should never be called manually.
         * </note>
         *
         * @param string The message to be written.
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