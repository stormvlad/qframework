<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/libs/smarty/Smarty.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qviewrenderer.class.php");

    define("DEFAULT_SMARTY_CACHE_DIR", "tmp/");
    define("DEFAULT_SMARTY_COMPILE_DIR", "tmp/");
    define("DEFAULT_SMARTY_TEMPLATES_DIR", "templates/");
    define("DEFAULT_SMARTY_TEMPLATES_EXTENSION", ".template");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qSmartyRenderer extends qViewRenderer
    {
        /**
        * Add function info here
        */
        function qSmartyRenderer($templatesDir = DEFAULT_SMARTY_TEMPLATES_DIR, $templatesExtension = DEFAULT_SMARTY_TEMPLATES_EXTENSION)
        {
            $this->qViewRenderer();
            $this->_engine = new Smarty();

            $this->_engine->caching        = false;
            $this->_engine->cache_lifetime = 300;
            $this->_engine->cache_dir      = DEFAULT_SMARTY_CACHE_DIR;
            $this->_engine->compile_dir    = DEFAULT_SMARTY_COMPILE_DIR;
            $this->_engine->template_dir   = $templatesDir;

            $this->_engine->php_handling   = false;
            $this->_engine->use_sub_dirs   = false;

            $this->setTemplatesExtension($templatesExtension);
        }

        /**
        * Add function info here
        */
        function getTemplatesExtension()
        {
            return $this->_templatesExtension;
        }

        /**
        * Add function info here
        */
        function setTemplatesExtension($extension)
        {
            $this->_templatesExtension = $extension;
        }

        /**
        * Add function info here
        */
        function render(&$view)
        {
            $layout = $view->getLayout();

            if (empty($layout))
            {
                $templateFileName = "";
            }
            else
            {
                $templateFileName = $layout . "/";
            }

            $templateFileName .= $view->getTemplateName() . $this->_templatesExtension;
            $view->setValue("templateFileName", $templateFileName);

            $this->_engine->_templateFile = $templateFileName;
            $this->_engine->assign($view->getAsArray());

            return $this->_engine->fetch($templateFileName);
        }
    }

?>
