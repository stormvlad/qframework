<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * qAppender allows you to log messags to any location.
     *
     * @package qframework
     * @since   1.0
     */
    class qAppender extends qObject
    {
        /**
         * The layout to be used for this appender.
         *
         * @access private
         * @since  1.0
         * @type   Layout
         */
        var $layout;

        /**
         * Create a new Appender instance.
         *
         * @param Layout A Layout instance.
         *
         * @access public
         * @since  1.0
         */
        function &qAppender (&$layout)
        {
            parent::qObject();

            $this->layout =& $layout;
        }

        /**
         * Cleanup appender resources if any exist.
         *
         * <br/><br/>
         *
         * <note>
         *     This should never be called manually.
         * </note>
         *
         * @access public
         * @since  1.0
         */
        function cleanup ()
        {

        }

        /**
         * Retrieve the layout this appender is using.
         *
         * @return Layout A Layout instance.
         *
         * @access public
         * @since  1.0
         */
        function & getLayout ()
        {
            return $this->layout;
        }

        /**
         * Set the layout this appender will use.
         *
         * @param Layout A Layout instance.
         *
         * @access public
         * @since  1.0
         */
        function setLayout (&$layout)
        {
            $this->layout =& $layout;
        }

        /**
         * Write to this appender.
         *
         * <br/><br/>
         *
         * <note>
         *     This should never be called manually.
         * </note>
         *
         * @param message The message to write.
         *
         * @access public
         * @since  1.0
         */
        function write ($message)
        {
            throw(new qException("qAppender::write: This method must be implemented by child classes."));
            die();
        }
    }

?>