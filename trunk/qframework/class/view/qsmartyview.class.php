<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qview.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/template/qtemplateservice.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/locale/qlocale.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qclient.class.php");

    /**
     * Extends the original 'View' class to provide support for common operations, for example
     * to automatically add support for locale. It is recommended
     * that all classes that generate a view extend from this unless strictly necessary
     */
    class qSmartyView extends qView
    {
        var $_templateService;
        var $_template;

        function qSmartyView($templateName, $layout)
        {
            $this->qView();

            $this->_templateService = new qTemplateService();
            $this->_template        = $this->_templateService->Template($templateName, $layout);
        }

        function render()
        {
            $this->setValue("client", new qClient());
            $this->setValue("templateFileName", $this->_template->getTemplateFile());
            $this->_template->assign($this->_params->getAsArray());
            print $this->_template->fetch();
        }

    }
?>
