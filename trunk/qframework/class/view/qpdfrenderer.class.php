<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/libs/pdml/pdml.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qviewrenderer.class.php");

    define("DEFAULT_PDF_TEMPLATES_DIR", "pdml/");
    define("DEFAULT_PDF_TEMPLATES_EXTENSION", ".pdml");

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
        function qPdfRenderer($templatesDir = DEFAULT_PDF_TEMPLATES_DIR, $templatesExtension = DEFAULT_PDF_TEMPLATES_EXTENSION)
        {
            $this->qViewRenderer();

            $this->_pdfTemplatesDir       = $templatesDir;
            $this->_pdfTemplatesExtension = $templatesExtension;
        }

        /**
        * Add function info here
        */
        function getPdfTemplatesDir()
        {
            return $this->_pdfTemplatesDir;
        }

        /**
        * Add function info here
        */
        function getPdfTemplatesExtension()
        {
            return $this->_pdfTemplatesExtension;
        }

        /**
        * Add function info here
        */
        function setPdfTemplatesDir($dir)
        {
            $this->_pdfTemplatesDir = $dir;
        }

        /**
        * Add function info here
        */
        function setPdfTemplatesExtension($dir)
        {
            $this->_pdfTemplatesExtension = $dir;
        }

        /**
        * Add function info here
        */
        function render(&$view)
        {
            $smartyView          = new qSmartyView($view->getTemplateName());
            $renderer            = &$smartyView->getRenderer();

            $renderer->setTemplatesDir($this->_pdfTemplatesDir);
            $renderer->setTemplatesExtension($this->_pdfTemplatesExtension);
            $smartyView->setValues($view->getAsArray());

            $output              = $smartyView->render();

            $orientation         = substr($view->getOrientation(), 0, 1);
            $pageSize            = $view->getPageSize();
            $leftMargin          = $view->getLeftMargin() * 28.35;
            $rightMargin         = $view->getRightMargin() * 28.35;
            $topMargin           = $view->getTopMargin() * 28.35;
            $bottomMargin        = $view->getBottomMargin() * 28.35;
            $baseFont            = $view->getBaseFont();
            $baseFontSize        = $view->getBaseFontSize();

            $pdml                = new PDML($orientation, "pt", $pageSize);
            $pdml->compress      = 0;
            $pdml->left_margin   = array($leftMargin);
            $pdml->right_margin  = array($rightMargin);
            $pdml->bottom_margin = array($bottomMargin);
            $pdml->font_face     = array($baseFont);
            $pdml->font_size     = array($baseFontSize);

            $pdml->SetTopMargin($topMargin);

            $pdml->ParsePDML($output);
            $output = $pdml->Output("", "S");

            header("Content-Type: application/pdf");
            header("Content-Length: " . strlen($output));
            header("Content-disposition: " . $view->getContentDisposition() . "; filename=" . $view->getContentFileName());

            return $output;
        }
    }

?>