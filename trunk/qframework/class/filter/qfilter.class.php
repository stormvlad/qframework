<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * This is the base class from which all the objects that will be used in the
     * pipeline will inherit. It defines the basic operations and methods
     * that they'll have to use
     */
    class qFilter extends qObject
    {
        var $_controllerParams;

        /**
        * Add function info here
        */
        function qFilter(&$controllerParams)
        {
            $this->qObject();
            $this->_controllerParams = &$controllerParams;
        }

        /**
        * Add function info here
        */
        function run(&$filtersChain)
        {
            throw(new qException("qFilter::run: This method must be implemented by child classes."));
            die();
        }
    }
?>