<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/logging/qlogger.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/logging/qmessage.class.php");

    /**
    * qSqlLogger
    */
    class qSqlLogger extends qLogger
    {
        /**
        * Constructor
        */
        function qSqlLogger()
        {
            $this->qLogger();

            $this->priority     = 0;
            $this->exitPriority = 1000;

            $layout   = new qPatternLayout("%x{ip} | %x{class} | %x{time} | %x{sql}%n");
            $appender = new qStdoutAppender($layout);
            $this->addAppender("stdout", $appender);
        }

        /**
        * Constructor
        */
        function handler(&$obj, $args)
        {
            $message = new qMessage($args);
            $this->log($message);
        }
    }

?>