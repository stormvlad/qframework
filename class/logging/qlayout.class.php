<?php

    /**
     * qLayout provides a customizable way to format data for an appender.
     *
     * @package qframework
     * @since   1.0
     */
    class qLayout extends qObject
    {
        /**
         * Create a new Layout instance.
         *
         * @access public
         * @since  1.0
         */
        function &qLayout()
        {
            parent::qObject();
        }

        /**
         * Format a message.
         *
         * <br/><br/>
         *
         * <note>
         *     This should never be called manually.
         * </note>
         *
         * @param qMessage A qMessage instance.
         *
         * @access public
         * @since  1.0
         */
        function &format (&$message)
        {
            throw(new qException("qLayout::format(&$message) must be overridden"));
            die();
        }
    }

?>