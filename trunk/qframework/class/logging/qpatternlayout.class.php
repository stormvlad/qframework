<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/logging/qlayout.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/logging/qconversionpattern.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttp.class.php");

    /**
     * PatternLayout allows a completely customizable layout that uses a conversion
     * pattern for formatting.
     *
     * @author  Sean Kerr
     * @package qframework
     * @package logging
     * @since   1.0
     */
    class qPatternLayout extends qLayout
    {
        /**
         * The message to be formatted.
         *
         * @access private
         * @since  1.0
         * @type   object
         */
        var $message;

        /**
         * The conversion pattern to use with this layout.
         *
         * @access private
         * @since  1.0
         * @type   ConversionPattern
         */
        var $pattern;

        /**
         * Create a new PatternLayout instance.
         *
         * @param string A message layout.
         *
         * @access public
         * @since  1.0
         */
        function &qPatternLayout ($layout)
        {
            $this->pattern =& new qConversionPattern($layout);
        }

        /**
         * qConversionPattern callback method.
         *
         * <br/><br/>
         *
         * <note>
         *     This should never be called manually.
         * </note>
         *
         * @param string A conversion character.
         * @param string A conversion parameter.
         *
         * @return string A replacement for the given data.
         *
         * @access public
         * @since  1.0
         */
        function &callback ($char, $param)
        {
            switch ($char)
            {
                case "c":
                case "F":
                case "l":
                case "m":
                case "N":
                case "p":
                    $data = $this->message->getParameter($char);
                    break;

                case "n":
                    $data = "\n";
                    break;

                case "r":
                    $data = "\r";
                    break;

                case "t":
                    $data = "\t";
                    break;

                case "T":
                    $data = time();
                    break;

                // conversion chars with a parameter
                case "C":
                    $data = (defined($param)) ? constant($param) : "";
                    break;

                case "d":
                    // get the date
                    if ($param == NULL)
                    {
                        $param = "n/j/y g:i a";
                    }

                    $data = date($param);
                    break;

                case "f":
                    // get the file
                    $data   = $this->message->getParameter("f");
                    $server = &qHttp::getServerVars();

                    switch($param)
                    {
                        case "file":
                            $data = basename($data);
                            break;

                        case "dir":
                            $data = dirname($data);
                            break;

                        case "rel":
                            $data = substr(dirname($data), strlen($server->getValue("DOCUMENT_ROOT")) + 1) .  "/" . basename($data);
                    }
                    break;

                case "x":
                    // get a custom parameter
                    $data =& $this->message->getParameter($param);
            }

            return $data;
        }

        /**
         * Format a log message.
         *
         * <br/><br/>
         *
         * <b>Conversion characters:</b>
         *
         * <ul>
         *     <li><b>%c</b>               - the class where message was logged</li>
         *     <li><b>%C{constant}</b>     - the value of a PHP constant</li>
         *     <li><b>%d{format}</b>       - a date (uses date() format)</li>
         *     <li><b>%f{file|dir|rel}</b> - the file where the message was logged</li>
         *     <li><b>%F</b>               - the function where the message was
         *                                   logged</li>
         *     <li><b>%l</b>               - the line where the message was logged</li>
         *     <li><b>%m</b>               - the log message</li>
         *     <li><b>%n</b>               - a newline</li>
         *     <li><b>%N</b>               - the level name</li>
         *     <li><b>%p</b>               - the level of priority</li>
         *     <li><b>%r</b>               - a carriage return</li>
         *     <li><b>%t</b>               - a horizontal tab</li>
         *     <li><b>%T</b>               - a unix timestamp (seconds since January
         *                                   1st, 1970)</li>
         *     <li><b>%x{param}</b>        - a custom message parameter name</li>
         * </ul>
         *
         * @param Message A Message instance.
         *
         * @return string A formatted log message.
         */
        function &format (&$message)
        {
            // register callback method
            // this cannot be done in the constructor
            $this->pattern->setCallbackObject($this, "callback");

            $this->message =& $message;

            return $this->pattern->parse();
        }
    }

?>