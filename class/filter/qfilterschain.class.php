<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * @brief Colección ordenada de filtros independientes
     *
     * @author  qDevel - info@qdevel.com
     * @date    07/03/2005 23:46
     * @version 1.0
     * @ingroup filter     
     */
    class qFiltersChain extends qObject
    {
        var $_filters;
        var $_index;

        /**
         * Constructor
         */
        function qFiltersChain()
        {
            $this->qObject();
            $this->_filters = array();
            $this->_index   = 0;
        }

        /**
         * Add function info here
         */
        function addFilter(&$filter)
        {
            $this->_filters[] = &$filter;
        }

        /**
         * Loads all the filters
         * @private
         */
        function &getFilters()
        {
            return $this->_filters;
        }

        /**
         * Processes the pipeline, using the request and blogInfo
         * objects as given in the constructor.
         */
        function run()
        {
            if (count($this->_filters) > 0)
            {
                $filter = &$this->_filters[$this->_index];
                $this->_index++;
                $filter->run($this);
            }
        }
    }
?>
