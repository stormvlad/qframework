<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qViewRenderer extends qObject
    {
        var $_engine;

        /**
        * Add function info here
        */
        function qViewRenderer()
        {
            $this->qObject();
            $this->_engine = null;
        }

        /**
        * Add function info here
        */
        function &getEngine()
        {
            if (empty($this->_engine))
            {
                throw(new qException("qViewRenderer::getEgine: This class do not work with any renderer engine."));
                die();
            }

            return $this->_engine;
        }

        /**
        * Add function info here
        */
        function setEngine($engine)
        {
            $this->_engine = &$engine;
        }

        /**
        * Add function info here
        */
        function render(&$view)
        {
            throw(new qException("qViewRenderer::render: This method must be implemented by child classes."));
            die();
        }
    }

?>
