<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    define("DEFAULT_TIMER_PRECISION", 6);

    /**
     * @brief Temporizador
     *
     * @author  qDevel - info@qdevel.com
     * @date    06/03/2005 18:05
     * @version 1.0
     * @ingroup misc
     */
    class qTimer extends qObject
    {
        var $_precision;
        var $_marks;

        /**
         * @brief Constructor
         * 
         * @param precision <em>integer</em> Número de decimales en los valores
         */
        function qTimer($precision = DEFAULT_TIMER_PRECISION)
        {
            $this->qObject();
            $this->_marks     = array();
            $this->_precision = $precision;
            $this->start();
        }

        /**
         * @brief Devuelve la única instancia de qTimer
         *
         * @note Basado en el patrón Singleton. El objectivo de este método es asegurar que exista sólo una instancia de esta clase y proveer de un punto global de accesso a ella.
         * @return qTimer
         */
        function &getInstance()
        {
            static $timerInstance;

            if (!isset($timerInstance))
            {
                $timerInstance = new qTimer();
            }

            return $timerInstance;
        }

        /**
         * @brief Define el momento actual como inicio
         */
        function start()
        {
            $this->addMark("__start__");
        }

        /**
         * @brief Define el momento actual como final
         */
        function stop()
        {
            $this->addMark("__stop__");
        }

        /**
         * @brief Devuelve el tiempo transcurrido con el temporizador.
         *
         * Si no especificamos ningúna marca se cuenta desde que se inicio
         * el temporizador hasta el momento que se llama esta función.
         * Si se especifica marca de incio o marca de final se cuenta como
         * incio o final esa marca respectivamente para hacer el cálculo del tiempo.
         *
         * @param start <em>string</em> Marca de inicio del temporizador
         * @param stop <em>string</em> Marca final del temporizador
         * @exception qTimer::get: Start mark doesn't exist.
         * @exception qTimer::get: Stop mark doesn't exist.
         * @returns float
         */
        function get($start = null, $stop = null)
        {
            if (empty($start))
            {
                $start = "__start__";
            }

            if (!array_key_exists($start, $this->_marks))
            {
                trigger_error("Start mark '" . $start . "' doesn't exist.", E_USER_WARNING);
                return false;
            }

            if (!empty($stop) && !array_key_exists($stop, $this->_marks))
            {
                trigger_error("Stop mark '" . $start . "' doesn't exist.", E_USER_WARNING);
                return false;
            }

            $bcsubExists = function_exists("bcsub");
            
            if ($stop)
            {
                return $bcsubExists ? bcsub($this->_marks[$stop], $this->_marks[$start], $this->_precision) : ($this->_marks[$stop] - $this->_marks[$start]);
            }
            else if (array_key_exists("__stop__", $this->_marks))
            {
                return $bcsubExists ? bcsub($this->_marks["__stop__"], $this->_marks[$start], $this->_precision) : ($this->_marks["__stop__"] - $this->_marks[$start]);
            }
            else
            {
                return $bcsubExists ? bcsub($this->_getTime(), $this->_marks[$start], $this->_precision) : ($this->_getTime() - $this->_marks[$start]);
            }
        }

        /**
         * @brief Define una marca con el tiempo actual
         *
         * Guardo el momento actual con un nombre para poderlo consultar posteriormente
         * como inicio o final.
         *
         * @param name <em>string</em> Nombre indentificador de la marca
         */
        function addMark($name)
        {
            $this->_marks[$name] = $this->_getTime();
        }

        /**
         * @brief Borra una marca 
         *
         * @param name <em>string</em> Nombre indentificador de la marca
         */
        function removeMark($name)
        {
            unset($this->_marks[$name]);
        }

        /**
         * @brief Especifica una precisión para devolver los valores
         *
         * @param precision <em>integer</em> Número de decimales en los valores
         */
        function setPrecision($precision)
        {
            $this->_precision   = $precision;
        }

        /**
         * Devuelve el tiempo en microsengundos
         *
         * @returns float
         * @private
         */
        function _getTime()
        {
            list($usec, $sec) = explode(" ", microtime());
            return $sec . substr($usec, 1);
        }
    }

?>