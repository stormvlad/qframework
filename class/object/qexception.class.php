<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    if (PHP_VERSION < 5) include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qexception.php4.class.php");

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
        function qthrow()
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
                    if (($trace["function"] != "standard") 
                         && (basename($trace["file"]) != "qerrorlogger.class.php" )
                         && (basename($trace["file"]) != "qlogger.class.php" )
                         && ($trace["file"] != __FILE__ ))
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

?>
