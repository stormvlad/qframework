<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * qAppender allows you to log messags to any location.
     *
     * @since   1.0
     */
    class qAppender extends qObject
    {
        /**
         * The layout to be used for this appender.
         *
         * @private
         * @since  1.0
         * @type   Layout
         */
        var $layout;

        /**
         * Constructor
         *
         * @param layout Layout A Layout instance.
         */
        function &qAppender (&$layout)
        {
            parent::qObject();

            $this->layout =& $layout;
        }

        /**
         * Cleanup appender resources if any exist.
         *
         * @note This should never be called manually.
         * @public
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
         * @public
         * @since  1.0
         */
        function & getLayout ()
        {
            return $this->layout;
        }

        /**
         * Set the layout this appender will use.
         *
         * @param layout Layout A Layout instance.
         *
         * @public
         * @since  1.0
         */
        function setLayout (&$layout)
        {
            $this->layout =& $layout;
        }

        /**
         * Write to this appender.
         *
         * @note This should never be called manually.
         * @param message The message to write.
         *
         * @public
         * @since  1.0
         */
        function write ($message)
        {
            throw(new qException("qAppender::write: This method must be implemented by child classes."));
            die();
        }
    }

?>