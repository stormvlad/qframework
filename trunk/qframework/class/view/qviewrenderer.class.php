<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qViewRenderer extends qObject
    {
        /**
        * Add function info here
        */
        function qViewRenderer()
        {
            $this->qObject();
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
