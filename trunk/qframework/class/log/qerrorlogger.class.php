<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/log/qlogger.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/log/qmessage.class.php");

    /**
     * qErrorLogger provides a default logging mechanism for errors.
     *
     * @since   1.0
     */
    class qErrorLogger extends qLogger
    {
        /**
         * Constructor
         */
        function &qErrorLogger ()
        {
            parent::qLogger();
        }

        /**
         * Log a message with a debug priority.
         *
         * @note This has a priority level of 1000.
         * @param message string An error message.
         * @param class string The class where message was logged.
         * @param function string The function where message was logged.
         * @param file string The file where message was logged.
         * @param line int    The line where message was logged.
         *
         * @public
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
         * @note This has a priority level of 3000.
         * @param message  string An error message.
         * @param class    string The class where message was logged.
         * @param function string The function where message was logged.
         * @param file     string The file where message was logged.
         * @param line     int    The line where message was logged.
         *
         * @public
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
         * @note This has a priority level of 5000.
         * @param message  string An error message.
         * @param class    string The class where message was logged.
         * @param function string The function where message was logged.
         * @param file     string The file where message was logged.
         * @param line     int    The line where message was logged.
         *
         * @public
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
         * @note This has a priority level of 2000.
         * @param message  string An error message.
         * @param class    string The class where message was logged.
         * @param function string The function where message was logged.
         * @param file     string The file where message was logged.
         * @param line     int    The line where message was logged.
         *
         * @public
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
         * @note Do not call this method directly. Call the standard PHP function
         *       <i>trigger_error()</i>.
         * @param level   int    A priority level.
         * @param message string An error message.
         * @param file    string The file where the error occured.
         * @param line    int    The line where the error occured.
         *
         * @public
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
         * @note This has a priority level of 4000.
         * @param message  string An error message.
         * @param class    string The class where message was logged.
         * @param function string The function where message was logged.
         * @param file     string The file where message was logged.
         * @param line     int    The line where message was logged.
         *
         * @public
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