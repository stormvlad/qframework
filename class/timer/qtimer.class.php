<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    define("DEFAULT_TIMER_PRECISION", 6);

    class qTimer extends qObject
    {
        var $_precision;
        var $_marks;

        /**
        *    Add function info here
        */
        function qTimer($precision = DEFAULT_TIMER_PRECISION)
        {
            $this->qObject();
            $this->_marks     = array();
            $this->_precision = $precision;
            $this->start();
        }

        /**
        *    Add function info here
        */
        function &getTimer()
        {
            static $timerInstance;

            if (!isset($timerInstance))
            {
                $timerInstance = new qTimer();
            }

            return $timerInstance;
        }

        /**
        *    Add function info here
        */
        function start()
        {
            $this->addMark("__start__");
        }

        /**
        *    Add function info here
        */
        function stop()
        {
            $this->addMark("__stop__");
        }

        /**
        *    Add function info here
        */
        function get($start = null, $stop = null)
        {
            if (empty($start))
            {
                $start = "__start__";
            }

            if (!array_key_exists($start, $this->_marks))
            {
                throw(new qException("qTimer::get: Start mark '" . $start . "' doesn't exist."));
                die();
            }

            if (!empty($stop) && !array_key_exists($stop, $this->_marks))
            {
                throw(new qException("qTimer::get: Stop mark '" . $stop . "' doesn't exist."));
                die();
            }

            if ($stop)
            {
                return bcsub($this->_marks[$stop], $this->_marks[$start], $this->_precision);
            }
            else if (array_key_exists("__stop__", $this->_marks))
            {
                return bcsub($this->_marks["__stop__"], $this->_marks[$start], $this->_precision);
            }
            else
            {
                return bcsub($this->_getTime(), $this->_marks[$start], $this->_precision);
            }
        }

        /**
        *    Add function info here
        */
        function addMark($name)
        {
            $this->_marks[$name] = $this->_getTime();
        }

        /**
        *    Add function info here
        */
        function setPrecision($precision)
        {
            $this->_precision   = $precision;
        }

        /**
        *    Add function info here
        */
        function _getTime()
        {
            list($usec, $sec) = explode(" ", microtime());
            return $sec . substr($usec, 1);
        }
    }

?>