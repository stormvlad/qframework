<?php

    include_once("framework/class/view/view.class.php" );
    include_once("framework/class/template/templateservice.class.php" );
    include_once("framework/class/locale/locale.class.php" );
    include_once("framework/class/net/client.class.php" );

    /**
     * Extends the original 'View' class to provide support for common operations, for example
     * to automatically add support for locale. It is recommended
     * that all classes that generate a view extend from this unless strictly necessary
     */
    class SmartyView extends View {

        var $_templateService;
        var $_template;

        function SmartyView($templateName, $layout)
        {
            $this->View();

            $this->_templateService = new TemplateService();
            $this->_template        = $this->_templateService->Template($templateName, $layout);
        }

        function render()
        {
            $this->setValue("client", new Client());
            $this->setValue("templateFileName", $this->_template->getTemplateFile());
            $this->_template->assign($this->_params->getAsArray());
            print $this->_template->fetch();
        }

    }
?>
