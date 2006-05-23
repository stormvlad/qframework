<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qsmartyview.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qfoprenderer.class.php");

    /**
     * Extends the original 'View' class to provide support for common operations, for example
     * to automatically add support for locale. It is recommended
     * that all classes that generate a view extend from this unless strictly necessary
     */
    class qFopView extends qSmartyView
    {
        var $_templateName;
        var $_layout;
        var $_fopUrl;
        var $_disposition;
        var $_fileName;

        /**
        *    Add function info here
        */
        function qFopView($templateName, $fopUrl, $layout = null)
        {
            $this->qView(new qFopRenderer());

            $this->_templateName = $templateName;
            $this->_fopUrl       = $fopUrl;
            $this->_layout       = $layout;
            $this->_disposition  = 'attachment';
            $this->_fileName     = 'documento.pdf';
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
        
        function getDisposition()
        {
            return $this->_disposition;
        }
        
        function setDisposition($disposition)
        {
            $this->_disposition = $disposition;
        }
        
        function getFileName()
        {
            return $this->_fileName;
        }
        
        function setFileName($fileName)
        {
            $this->_fileName = $fileName;
        }
        
        function render()
        {
            header("Content-type: application/pdf");
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Content-Disposition: " . $this->getDisposition() . "; filename=\"" . $this->getFileName()."\"");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Pragma: public");
            return parent::render();
        }
    }
?>
