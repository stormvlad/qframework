<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/log/qlogger.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/log/qmessage.class.php");

    /**
     * @brief Establece logs para las consultas SQL.
     *
     * @author  qDevel - info@qdevel.com
     * @date    13/03/2005 04:32
     * @version 1.0
     * @ingroup log
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