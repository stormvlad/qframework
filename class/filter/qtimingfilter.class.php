<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/filter/qfilter.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qurl.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/timer/qtimer.class.php");

    /**
    * Add function info here
    */
    class qTimingFilter extends qFilter
    {
        /**
        * Add function info here
        */
        function qTimingFilter(&$controllerParams)
        {
            $this->qFilter($controllerParams);
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