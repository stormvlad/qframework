<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/filter/qfilter.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qurl.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/timer/qtimer.class.php");

    /**
     * @brief Cronometro de la ejecución
     *
     * Esta clase nos permite mostrar el tiempo requerido para procesar la petición
     * y preparar la respuesta reemplazado una marca dentro del código HTML de salida.
     *
     * @author  qDevel - info@qdevel.com
     * @date    07/03/2005 23:46
     * @version 1.0
     * @ingroup filter     
     */
    class qTimingFilter extends qFilter
    {
        /**
         * Constructor
         */
        function qTimingFilter()
        {
            $this->qFilter();
        }

        /**
        * Add function info here
        */
        function run(&$filtersChain)
        {
            ob_start();
            $t = new qTimer();
            $filtersChain->run();
            $t->stop();
            $text = ob_get_contents();
            ob_end_clean();

            print str_replace("[TIMING_FILTER_TIME]", $t->get(), $text);
        }
    }
?>