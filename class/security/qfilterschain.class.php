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
        var $_error;

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
            $this->_error   = false;
        }

        /**
         * Add function info here
         */
        function addFilter($filter)
        {
            array_push($this->_filters, $filter);
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
         * Add function info here
         */
        function _setError($error)
        {
            $this->_error = $error;
        }

        /**
         * Add function info here
         */
        function getError()
        {
            return $this->_error;
        }

        /**
         * Processes the pipeline, using the request and blogInfo
         * objects as given in the constructor.
         */
        function filter(&$controller, &$httpRequest, &$user)
        {
            foreach ($this->_filters as $filter)
            {
                if (!$filter->filter($controller, $httpRequest, &$user))
                {
                    $this->_setError($filter->getError());
                    return false;
                }
            }

            return true;
        }
    }
?>
