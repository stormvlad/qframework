<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/filter/qhostsfilter.class.php");

    /**
     * @brief Control de seguridad para permitir nombres de host y IP.
     *
     * @author  qDevel - info@qdevel.com
     * @date    07/03/2005 23:46
     * @version 1.0
     * @ingroup filter     
     */
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