<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/pdml/pdml.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qviewrenderer.class.php");

    define(DEFAULT_PDF_TEMPLATES_DIR, "pdml/");
    define(DEFAULT_PDF_TEMPLATES_EXTENSION, ".pdml");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qPdfRenderer extends qViewRenderer
    {
        var $_pdfTemplatesDir;
        var $_pdfTemplatesExtension;

        /**
        * Add function info here
        */
        function qPdfRenderer()
        {
            $this->qViewRenderer();

            $this->_pdfTemplatesDir       = DEFAULT_PDF_TEMPLATES_DIR;
            $this->_pdfTemplatesExtension = DEFAULT_PDF_TEMPLATES_EXTENSION;
        }

        /**
        * Add function info here
        */
        function render(&$view)
        {
            $smartyView = new qSmartyView($view->getTemplateName());
            $renderer   = &$smartyView->getRenderer();
            $renderer->setTemplatesDir($this->_pdfTemplatesDir);
            $renderer->setTemplatesExtension($this->_pdfTemplatesExtension);
            $smartyView->setValues($view->getAsArray());
            $output     = $smartyView->render();

            $pdml = new PDML("P","pt","A4");
            //$pdml->AddFont("Verdana", "", "verdana.php");


            $pdml->compress = 0;
            $pdml->ParsePDML($output);
            $output = $pdml->Output("","S");

            header("Content-Type: application/pdf");
            header("Content-Length: " . strlen($output));
            header("Content-disposition: inline; filename=doc.pdf");

            return $output;
        }
    }

?>
