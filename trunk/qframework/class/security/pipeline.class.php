<?php

    include_once("framework/class/object/object.class.php" );
    include_once("framework/class/security/pipelineresult.class.php" );

    /**
     * Implementation of a basic security framework based on a
     * pipeline. Every element of the pipeline implements a simple
     * security mechanism. When one of the filters in the pipeline
     * find a condition that is matched by the incoming request, the
     * request will be blocked
     */
    class Pipeline extends Object {

        var $_filters;
        var $_result;

        /**
         * Constructor
         *
         * @param httpRequest The HTTP request
         * that is currently executing this pipeline
         */
        function Pipeline()
        {
            $this->Object();

            $this->_filters = array();
            $this->_result  = new PipelineResult(true);
        }

        /**
         * Loads all the filters
         * @private
         */
        function addFilter($filter)
        {
            array_push($this->_filters, $filter);
        }

        /**
         * Loads all the filters
         * @private
         */
        function getFilters()
        {
            return $this->_filters;
        }

        /**
         * Processes the pipeline, using the request and blogInfo
         * objects as given in the constructor.
         */
        function process()
        {
            foreach ($this->_filters as $filter)
            {
                $this->_result = $filter->filter();

                if (!$this->_result->isValid())
                {
                    return $this->_result;
                }
            }

            return $this->_result;
        }
    }
?>
