<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * Implementation of a basic security framework based on a
     * pipeline. Every element of the pipeline implements a simple
     * security mechanism. When one of the filters in the pipeline
     * find a condition that is matched by the incoming request, the
     * request will be blocked
     */
    class qFiltersChain extends qObject
    {
        var $_filters;
        var $_index;

        /**
         * Constructor
         *
         * @param httpRequest The HTTP request
         * that is currently executing this pipeline
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
