<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/log/qlogger.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/log/qmessage.class.php");

    /**
    * qSqlLogger
    */
    class qSqlQueryLogger extends qLogger
    {
        /**
        * Constructor
        */
        function qSqlQueryLogger()
        {
            $this->qLogger();

            $this->priority     = 0;
            $this->exitPriority = 1000;

            $layout   = new qPatternLayout("%d{Y/m/d H:i:s} | %x{ip} | %x{script} | %x{queryCount} | %x{time} | %x{sql}%n");
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