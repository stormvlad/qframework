<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qview.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qpdfrenderer.class.php");

    define(DEFAULT_PDF_VIEW_BASE_FONT, "courier");
    define(DEFAULT_PDF_VIEW_BASE_FONT_SIZE, 10);
    define(DEFAULT_PDF_VIEW_LEFT_MARGIN, 2.5);
    define(DEFAULT_PDF_VIEW_RIGHT_MARGIN, 2);
    define(DEFAULT_PDF_VIEW_TOP_MARGIN, 2);
    define(DEFAULT_PDF_VIEW_BOTTOM_MARGIN, 2);
    define(DEFAULT_PDF_VIEW_ORIENTATION, "portrait");
    define(DEFAULT_PDF_VIEW_PAGE_SIZE, "A4");
    define(DEFAULT_PDF_VIEW_CONTENT_DISPOSITION, "attachment");
    define(DEFAULT_PDF_VIEW_CONTENT_FILENAME, "doc.pdf");

    /**
     * Extends the original 'View' class to provide support for common operations, for example
     * to automatically add support for locale. It is recommended
     * that all classes that generate a view extend from this unless strictly necessary
     */
    class qPdfView extends qView
    {
        var $_templateName;
        var $_baseFont;
        var $_baseFontSize;
        var $_leftMargin;
        var $_rightMargin;
        var $_topMargin;
        var $_bottomMargin;
        var $_orientation;
        var $_pageSize;
        var $_contentDisposition;
        var $_contentFileName;

        /**
        *    Add function info here
        */
        function qPdfView($templateName, $pdfFileName = DEFAULT_PDF_VIEW_CONTENT_FILENAME)
        {
            $this->qView(new qPdfRenderer());

            $this->_templateName       = $templateName;
            $this->_baseFont           = DEFAULT_PDF_VIEW_BASE_FONT;
            $this->_baseFontSize       = DEFAULT_PDF_VIEW_BASE_FONT_SIZE;
            $this->_leftMargin         = DEFAULT_PDF_VIEW_LEFT_MARGIN;
            $this->_rightMargin        = DEFAULT_PDF_VIEW_RIGHT_MARGIN;
            $this->_topMargin          = DEFAULT_PDF_VIEW_TOP_MARGIN;
            $this->_bottomMargin       = DEFAULT_PDF_VIEW_BOTTOM_MARGIN;
            $this->_orientation        = DEFAULT_PDF_VIEW_ORIENTATION;
            $this->_pageSize           = DEFAULT_PDF_VIEW_PAGE_SIZE;
            $this->_contentDisposition = DEFAULT_PDF_VIEW_CONTENT_DISPOSITION;
            $this->_contentFileName    = $pdfFileName;
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
        function getBaseFontSize()
        {
            return $this->_baseFontSize;
        }

        /**
        *    Add function info here
        */
        function setBaseFontSize($size)
        {
            $this->_baseFontSize = $size;
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

        /**
        *    Add function info here
        */
        function getOrientation()
        {
            return $this->_orientation;
        }

        /**
        *    Add function info here
        */
        function setOrientation($orientation)
        {
            $this->_orientation = $orientation;
        }

        /**
        *    Add function info here
        */
        function getPageSize()
        {
            return $this->_pageSize;
        }

        /**
        *    Add function info here
        */
        function setPageSize($size)
        {
            $this->_pageSize = $size;
        }

        /**
        *    Add function info here
        */
        function getContentDisposition()
        {
            return $this->_contentDisposition;
        }

        /**
        *    Add function info here
        */
        function setContentDisposition($disposition)
        {
            $this->_contentDisposition = $disposition;
        }

        /**
        *    Add function info here
        */
        function getContentFileName()
        {
            return $this->_contentFileName;
        }

        /**
        *    Add function info here
        */
        function setContentFileName($fileName)
        {
            $this->_contentFileName = $fileName;
        }
    }
?>
