<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/filter/qhostsfilter.class.php");

    /**
     * @brief Filtro peticiones de direcciones mediante su nombre, IP o rango.
     *
     * @author  qDevel - info@qdevel.com
     * @date    07/03/2005 23:46
     * @version 1.0
     * @ingroup filter     
     */
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