<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qview.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qsmartyrenderer.class.php");

    /**
     * Extends the original 'View' class to provide support for common operations, for example
     * to automatically add support for locale. It is recommended
     * that all classes that generate a view extend from this unless strictly necessary
     */
    class qSmartyView extends qView
    {
        var $_templateName;
        var $_layout;

        /**
        *    Add function info here
        */
        function qSmartyView($templateName, $layout = null)
        {
            $this->qView(new qSmartyRenderer());

            $this->_templateName = $templateName;
            $this->_layout       = $layout;
        }

        /**
        *    Add function info here
        */
        function getTemplateName()
        {
            return $this->_templateName;
        }

        /**
        *    Add function info here
        */
        function setTemplateName($templateName)
        {
            $this->_templateName = $templateName;
        }

        /**
        *    Add function info here
        */
        function getLayout()
        {
            return $this->_layout;
        }

        /**
        *    Add function info here
        */
        function setLayout($layout)
        {
            $this->_layout = $layout;
        }
    }
?>
