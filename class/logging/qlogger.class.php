<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * qLogger provides an interface for logging messages to multiple appenders.
     *
     * @package qframework
     * @since   1.0
     */
    class qLogger extends qObject
    {
        /**
         * An associative array of appenders.
         *
         * @access private
         * @since  1.0
         * @type   array
         */
        var $appenders;

        /**
         * The priority level that must be met or exceeded in order for qframework
         * to exit upon the logging of a message.
         *
         * @access private
         * @since  1.0
         * @type   int
         */
        var $exitPriority;

        /**
         * The priority level that must be met or exceeded in order for this logger
         * to log a message.
         *
         * @access private
         * @since  1.0
         * @type   int
         */
        var $priority;

        /**
         * Create a new Logger instance.
         *
         * @access public
         * @since  1.0
         */
        function &qLogger()
        {
            parent::qObject();

            // set default minimum priority levels
            if (parent::isDebug())
            {
                $this->priority     = 0; // SHOW ALL ERRORS
                $this->exitPriority = 1; // EXIT ON ANY ERROR, ALSO NOTICES
            }
            else
            {
                $this->priority     = 3000; // ERROR
                $this->exitPriority = 3000; // ERROR
            }
        }

        /**
         * Add an appender.
         *
         * <br/><br/>
         *
         * <note>
         *     If an appender with the given name already exists, an error will be
         *     reported.
         * </note>
         *
         * @param string   An appender name.
         * @param Appender An Appender instance.
         *
         * @access public
         * @since  1.0
         */
        function addAppender ($name, &$appender)
        {
            if (isset($this->appenders[$name]))
            {
                throw(new qException("qLogger::addAppender: qLogger already has appender " . $name));
                die();
            }

            $this->appenders[$name] =& $appender;
            return;
        }

        /**
         * Cleanup all appenders.
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
            $keys  = array_keys($this->appenders);
            $count = sizeof($keys);

            for ($i = 0; $i < $count; $i++)
            {
                $this->appenders[$keys[$i]]->cleanup();
            }
        }

        /**
         * Retrieve an appender.
         *
         * @param string An appender name.
         *
         * @return Appender An Appender instance, if the given appender exists,
         *                  otherwise <b>NULL</b>.
         *
         * @access public
         * @since  1.0
         */
        function &getAppender ($name)
        {
            if (isset($this->appenders[$name]))
            {
                return $this->appenders[$name];
            }

            return NULL;
        }

        /**
         * Retrieve the priority level that must be met or exceeded in order for
         * qframework to exit upon the logging of a message.
         *
         * @return int A priority level.
         *
         * @access public
         * @since  1.0
         */
        function getExitPriority ()
        {
            return $this->exitPriority;
        }

        /**
         * Retrieve the priority level that must be met or exceeded in order for
         * this logger to log a message.
         *
         * @return int A priority level.
         *
         * @access public
         * @since  1.0
         */
        function getPriority ()
        {
            return $this->priority;
        }

        /**
         * Log a message.
         *
         * @param Message A Message instance.
         *
         * @access public
         * @since  1.0
         */
        function log (&$message)
        {
            // retrieve message priority
            $msgPriority =& $message->getParameter("p");

            if ($this->priority == 0 || $msgPriority >= $this->priority)
            {
                // loop through appenders and write to each one
                $keys  = array_keys($this->appenders);
                $count = sizeof($keys);

                for ($i = 0; $i < $count; $i++)
                {
                    $appender =& $this->appenders[$keys[$i]];
                    $layout   =& $appender->getLayout();
                    $result   =& $layout->format($message);

                    $appender->write($result);
                }
            }

            // should we exit?
            if ($this->exitPriority > 0 && $msgPriority >= $this->exitPriority)
            {
                throw(new qException($message->getParameter("m"), $message->getParameter("p")));
                // sayonara baby
                exit;
            }
        }

        /**
         * Remove an appender.
         *
         * @param string An appender name.
         *
         * @access public
         * @since  1.0
         */
        function removeAppender ($name)
        {
            if (isset($this->appenders[$name]))
            {
                $appender =& $this->appenders[$name];
                $appender->cleanup();

                unset($this->appenders[$name]);
            }
        }

        /**
         * Set the priority level that must be met or exceeded in order for qframework
         * to exit upon the logging of a message.
         *
         * <br/><br/>
         *
         * <note>
         *     A priority level of 0 will turn of exiting.
         * </note>
         *
         * @param int A priority level.
         *
         * @access public
         * @since  1.0
         */
        function setExitPriority ($priority)
        {
            $this->exitPriority = $priority;
        }

        /**
         * Set the priority level that must be met or exceeded in order for this
         * logger to log a message.
         *
         * <br/><br/>
         *
         * <note>
         *     A priority level of 0 will log any message.
         * </note>
         *
         * @param int A priority level.
         *
         * @access public
         * @since  1.0
         */
        function setPriority ($priority)
        {
            $this->priority = $priority;
        }
    }

?>