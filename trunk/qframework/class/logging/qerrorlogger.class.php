<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/logging/qlogger.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/logging/qmessage.class.php");

    /**
     * qErrorLogger provides a default logging mechanism for errors.
     *
     * @package qframework
     * @since   1.0
     */
    class qErrorLogger extends qLogger
    {
        /**
         * Create a new qErrorLogger instance.
         *
         * @access public
         * @since  1.0
         */
        function &qErrorLogger ()
        {
            parent::qLogger();
        }

        /**
         * Log a message with a debug priority.
         *
         * <br/><br/>
         *
         * <note>
         *     This has a priority level of 1000.
         * </note>
         *
         * @param string An error message.
         * @param string The class where message was logged.
         * @param string The function where message was logged.
         * @param string The file where message was logged.
         * @param int    The line where message was logged.
         *
         * @access public
         * @since  1.0
         */
        function debug($message, $class = NULL, $function = NULL, $file = NULL, $line = NULL)
        {
            $message =& new qMessage(array('m' => $message,
                                           'c' => $class,
                                           'F' => $function,
                                           'f' => $file,
                                           'l' => $line,
                                           'N' => 'DEBUG',
                                           'p' => 1000));

            $this->log($message);
        }

        /**
         * Log a message with an error priority.
         *
         * <br/><br/>
         *
         * <note>
         *     This has a priority level of 3000.
         * </note>
         *
         * @param string An error message.
         * @param string The class where message was logged.
         * @param string The function where message was logged.
         * @param string The file where message was logged.
         * @param int    The line where message was logged.
         *
         * @access public
         * @since  1.0
         */
        function error ($message, $class = NULL, $function = NULL, $file = NULL, $line = NULL)
        {
            $message =& new qMessage(array('m' => $message,
                                           'c' => $class,
                                           'F' => $function,
                                           'f' => $file,
                                           'l' => $line,
                                           'N' => 'ERROR',
                                           'p' => 3000));

            $this->log($message);
        }

        /**
         * Log a message with a fatal priority.
         *
         * <br/><br/>
         *
         * <note>
         *     This has a priority level of 5000.
         * </note>
         *
         * @param string An error message.
         * @param string The class where message was logged.
         * @param string The function where message was logged.
         * @param string The file where message was logged.
         * @param int    The line where message was logged.
         *
         * @access public
         * @since  1.0
         */
        function fatal ($message, $class = NULL, $function = NULL, $file = NULL, $line = NULL)
        {
            $message =& new qMessage(array('m' => $message,
                                           'c' => $class,
                                           'F' => $function,
                                           'f' => $file,
                                           'l' => $line,
                                           'N' => 'FATAL',
                                           'p' => 5000));

            $this->log($message);
        }

        /**
         * Log a message with a info priority.
         *
         * <br/><br/>
         *
         * <note>
         *     This has a priority level of 2000.
         * </note>
         *
         * @param string An error message.
         * @param string The class where message was logged.
         * @param string The function where message was logged.
         * @param string The file where message was logged.
         * @param int    The line where message was logged.
         *
         * @access public
         * @since  1.0
         */
        function info ($message, $class = NULL, $function = NULL, $file = NULL, $line = NULL)
        {
            if ((substr($message, 0, 17) != "Undefined index: ")) // Don't show this error
            {
                $message =& new qMessage(array('m' => $message,
                                               'c' => $class,
                                               'F' => $function,
                                               'f' => $file,
                                               'l' => $line,
                                               'N' => 'NOTICE',
                                               'p' => 2000));
                $this->log($message);
            }
        }

        /**
         * Log an error handled by PHP.
         *
         * <br/><br/>
         *
         * <note>
         *     Do not call this method directly. Call the standard PHP function
         *     <i>trigger_error()</i>.
         * </note>
         *
         * @param int    A priority level.
         * @param string An error message.
         * @param string The file where the error occured.
         * @param int    The line where the error occured.
         *
         * @access public
         * @since  1.0
         */
        function standard ($level, $message, $file, $line)
        {
            // don't want to print supressed errors
            if (error_reporting() > 0)
            {
                switch ($level)
                {
                    case E_STRICT:
                        // do nothing
                        break;

                    case E_NOTICE:
                    case E_USER_NOTICE:
                        $this->info($message, NULL, NULL, $file, $line);
                        break;

                    case E_WARNING:
                        $this->warning($message, NULL, NULL, $file, $line);
                        break;

                    case E_USER_WARNING:
                    case E_USER_ERROR:
                    default:
                        $this->fatal($message, NULL, NULL, $file, $line);
                }
            }
        }

        /**
         * Log a message with a warning priority.
         *
         * <br/><br/>
         *
         * <note>
         *     This has a priority level of 4000.
         * </note>
         *
         * @param string An error message.
         * @param string The class where message was logged.
         * @param string The function where message was logged.
         * @param string The file where message was logged.
         * @param int    The line where message was logged.
         *
         * @access public
         * @since  1.0
         */
        function warning ($message, $class = NULL, $function = NULL, $file = NULL, $line = NULL)
        {
            $message =& new qMessage(array('m' => $message,
                                           'c' => $class,
                                           'F' => $function,
                                           'f' => $file,
                                           'l' => $line,
                                           'N' => 'WARNING',
                                           'p' => 4000));

            $this->log($message);
        }
    }

?>