<?php

    include_once("framework/class/object/object.class.php" );
    include_once("framework/class/object/exception.class.php" );
    include_once("framework/class/security/pipelineresult.class.php" );

    /**
     * This is the base class from which all the objects that will be used in the
     * pipeline will inherit. It defines the basic operations and methods
     * that they'll have to use
     */
    class PipelineFilter extends Object {

        /**
        * Add function info here
        */
        function PipelineFilter()
        {
            $this->Object();
        }

        /**
        * Add function info here
        */
        function filter()
        {
            throw(new Exception("PipelineFilter::filter: This method must be implemented by child classes!"));
            die();
        }
    }
?>