<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/filter/qhostsfilter.class.php");

    class qWhiteHostsFilter extends qHostsFilter
    {
        /**
        * Add function info here
        */
        function qWhiteHostsFilter()
        {
            $this->qHostsFilter();
            $this->setOrder("Allow,Deny");
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