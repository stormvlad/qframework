<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * PHP Java-style definition of an Exception object-
     */
    class qException extends qObject
    {
        var $_exceptionString;
        var $_exceptionCode;

        /**
         * Creates a new exception.
         *
         * @param exceptionString Descriptive message carried by the exception
         * @param exceptionCode Numerical error code assigned to this exception
         */
        function qException($exceptionString, $exceptionCode = 0)
        {
            $this->qObject();

            $this->_exceptionString = $exceptionString;
            $this->_exceptionCode   = $exceptionCode;
        }

        /**
         * Throws the exception and stops the execution, dumping some
         * interesting information.
         */
        function throw()
        {
            // gather some information
            print("<br/><b>Exception message</b>: " . $this->_exceptionString . "<br/><b>Error code</b>: " . $this->_exceptionCode."<br/>");
            $this->_printStackTrace();
        }

        function _printStackTrace()
        {
            if (function_exists("debug_backtrace"))
            {
                $info = debug_backtrace();
                print("-- Backtrace --<br/><i>");

                foreach ($info as $trace)
                {
                    if (($trace["function"] != "_internalerrorhandler") && ($trace["file"] != __FILE__ ))
                    {
                        print($trace["file"] . "(" . $trace["line"] . "): ");

                        if (!empty($trace["class"]))
                        {
                            print($trace["class"]. ".");
                        }

                        print($trace["function"] . "<br/>");
                    }
                }

                print("</i>");
            }
            else
            {
                print("<i>Stack trace is not available</i><br/>");
            }
        }
    }


    /**
     * This error handler takes care of throwing exceptions whenever an error
     * occurs.
     */
    function _internalErrorHandler($errorCode, $errorString)
    {
        if ($errorCode != E_NOTICE)
        {
            $e = new qException($errorString, $errorCode);
            $e->throw();
        }
    }

    /**
     * This error handler takes care of throwing exceptions whenever an error
     * occurs.
     */
    function _internalErrorHandlerDummy($errorCode, $errorString)
    {
    }

    /**
     * Throws an exception
     */
    function throw($exception)
    {
        $exception->throw();
    }

    function catch($exception)
    {
        print("Exception catched!");
    }

    $old_error_handler = set_error_handler("_internalErrorHandler");
?>
