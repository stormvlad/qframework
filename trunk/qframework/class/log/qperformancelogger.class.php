<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/log/qlogger.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/log/qmessage.class.php");

    /**
     * @brief Establece logs para el tiempo de ejecución.
     *
     * Los logs generados por esta clase nos dan información sobre parámetros
     * de rendimiento de la aplicación. Podemos obtener el número de consultas
     * ejecutadas, el tiempo total de ejecución del script, el tiempo empleado
     * en consultas en la base de datos, el tiempo empleado en renderizar la
     * pàgina i el resto.
     *
     * @author  qDevel - info@qdevel.com
     * @date    13/03/2005 04:35
     * @version 1.0
     * @ingroup log
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