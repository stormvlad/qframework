<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qview.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qpdfrenderer.class.php");

    /**
     * Extends the original 'View' class to provide support for common operations, for example
     * to automatically add support for locale. It is recommended
     * that all classes that generate a view extend from this unless strictly necessary
     */
    class qPdfView extends qView
    {
        var $_templateName;
        var $_baseFont;
        var $_leftMargin;
        var $_rightMargin;
        var $_topMargin;
        var $_bottomMargin;

        /**
        *    Add function info here
        */
        function qPdfView($templateName)
        {
            $this->qView(new qPdfRenderer());

            $this->_templateName = $templateName;
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
        function getBaseFont()
        {
            return $this->_baseFont;
        }

        /**
        *    Add function info here
        */
        function setBaseFont($baseFont)
        {
            $this->_baseFont = $baseFont;
        }

        /**
        *    Add function info here
        */
        function getLeftMargin()
        {
            return $this->_leftMargin;
        }

        /**
        *    Add function info here
        */
        function setLeftMargin($margin)
        {
            $this->_leftMargin = $margin;
        }

        /**
        *    Add function info here
        */
        function getTopMargin()
        {
            return $this->_topMargin;
        }

        /**
        *    Add function info here
        */
        function setTopMargin($margin)
        {
            $this->_topMargin = $margin;
        }

        /**
        *    Add function info here
        */
        function getRightMargin()
        {
            return $this->_rightMargin;
        }

        /**
        *    Add function info here
        */
        function setRightMargin($margin)
        {
            $this->_rightMargin = $margin;
        }

        /**
        *    Add function info here
        */
        function getBottomMargin()
        {
            return $this->_bottomMargin;
        }

        /**
        *    Add function info here
        */
        function setBottomMargin($margin)
        {
            $this->_bottomMargin = $margin;
        }
    }
?>
