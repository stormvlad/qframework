<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qviewrenderer.class.php");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qRedirectRenderer extends qViewRenderer
    {
        /**
        * Add function info here
        */
        function qRedirectRenderer()
        {
            $this->qViewRenderer();
        }

        /**
        * Add function info here
        */
        function render(&$view)
        {
            header("Location: " . $view->getUrl());
        }
    }

?>
