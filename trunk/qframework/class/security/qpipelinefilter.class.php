<?php

    include_once("framework/class/object/qobject.class.php" );
    include_once("framework/class/object/qexception.class.php" );
    include_once("framework/class/security/qpipelineresult.class.php" );

    /**
     * This is the base class from which all the objects that will be used in the
     * pipeline will inherit. It defines the basic operations and methods
     * that they'll have to use
     */
    class qPipelineFilter extends qObject {

        /**
        * Add function info here
        */
        function qPipelineFilter()
        {
            $this->qObject();
        }

        /**
        * Add function info here
        */
        function filter()
        {
            throw(new qException("PipelineFilter::filter: This method must be implemented by child classes!"));
            die();
        }
    }
?>