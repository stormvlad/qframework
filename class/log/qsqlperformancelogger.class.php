<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/log/qlogger.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/log/qmessage.class.php");

    /**
    * qSqlLogger
    */
    class qSqlPerformanceLogger extends qLogger
    {
        var $_queryCount;
        var $_seconds;

        /**
        * Constructor
        */
        function qSqlPerformanceLogger()
        {
            $this->qLogger();

            $this->_seconds     = 0;
            $this->_queryCount  = 0;

            $this->priority     = 0;
            $this->exitPriority = 1000;

            $layout   = new qPatternLayout("%d{Y/m/d H:i:s} | %x{ip} | %x{script} | %x{queryCount} | %x{time}%n");
            $appender = new qStdoutAppender($layout);
            $this->addAppender("stdout", $appender);
        }

        /**
        * Add function info here
        */
        function handlerSqlQuery(&$obj, $args)
        {
            $this->_queryCount++;
            $this->_seconds += $args["time"];
        }

        /**
        * Add function info here
        */
        function handler(&$obj, $args)
        {
            $args["time"] = $this->_seconds;
            $args["queryCount"] = $this->_queryCount;

            $message = new qMessage($args);
            $this->log($message);
        }
    }

?>