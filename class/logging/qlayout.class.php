<?php

    /**
     * qLayout provides a customizable way to format data for an appender.
     *
     * @since   1.0
     */
    class qLayout extends qObject
    {
        /**
         * Create a new Layout instance.
         *
         * @public
         * @since  1.0
         */
        function &qLayout()
        {
            parent::qObject();
        }

        /**
         * Format a message.
         *
         * @note This should never be called manually.
         * @param message qMessage A qMessage instance.
         *
         * @public
         * @since  1.0
         */
        function &format (&$message)
        {
            throw(new qException("qLayout::format(&$message) must be overridden"));
            die();
        }
    }

?>