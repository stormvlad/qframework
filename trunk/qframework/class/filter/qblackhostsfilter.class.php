<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/filter/qhostsfilter.class.php");

    class qBlackHostsFilter extends qHostsFilter
    {
        /**
        * Add function info here
        */
        function qBlackHostsFilter()
        {
            $this->qHostsFilter();
            $this->setOrder("Deny,Allow");
        }

        /**
        * Add function info here
        */
        function run(&$filtersChain)
        {
            parent::run($filtersChain);
        }
    }
?>