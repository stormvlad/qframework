<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/log/qlogger.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/log/qmessage.class.php");

    /**
    * qPerformanceLogger
    */
    class qPerformanceLogger extends qLogger
    {
        var $_queryCount;
        var $_timeSql;
        var $_timeNonSql;
        var $_timeRender;
        var $_timeTotal;

        /**
        * Constructor
        */
        function qPerformanceLogger()
        {
            $this->qLogger();

            $this->_queryCount  = 0;
            $this->_timeSql     = 0;
            $this->_timeNonSql  = 0;
            $this->_timeRender  = 0;

            $this->priority     = 0;
            $this->exitPriority = 1000;

            $layout   = new qPatternLayout("SQL: %x{timeSql} (%x{queryCount} queries)<br />Non SQL: %x{timeNonSql} <br />Render: %x{timeRender}<br />Total: %x{timeTotal}<br />");
            $appender = new qStdoutAppender($layout);
            $this->addAppender("stdout", $appender);
        }

        /**
        * Add function info here
        */
        function handlerSqlQuery(&$obj, $args)
        {
            $this->_queryCount++;
            $this->_timeSql += $args["time"];
        }

        /**
        * Add function info here
        */
        function handlerRenderMethodEnds(&$obj, $args)
        {
            $this->_timeRender = $args["seconds"];
        }

        /**
        * Add function info here
        */
        function handlerProcessMethodEnds(&$obj, $args)
        {
            $args["queryCount"] = $this->_queryCount;
            $args["timeTotal"]  = $args["seconds"];
            $args["timeSql"]    = $this->_timeSql;
            $args["timeRender"] = $this->_timeRender;
            $args["timeNonSql"] = $args["timeTotal"] - ($args["timeSql"] + $args["timeRender"]);

            unset($args["seconds"]);

            $message = new qMessage($args);
            $this->log($message);
        }
    }

?>