<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/template/smarty/Smarty.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/template/qviewrenderer.class.php");

    define(DEFAULT_TEMPLATE_CACHE_DIR, "tmp/");
    define(DEFAULT_TEMPLATE_COMPILE_DIR, "tmp/");
    define(DEFAULT_TEMPLATE_TEMPLATES_DIR, "templates/");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qSmartyRenderer extends qViewRenderer
    {
        /**
        * Add function info here
        */
        function qSmartyRenderer()
        {
            $this->qViewRenderer();
        }

        /**
        * Add function info here
        */
        function render(&$view)
        {
            $templateFileName = $view->getLayout() . "/" . $view->getTemplateName() . ".template";
            $view->setValue("templateFileName", $templateFileName);

            $smarty = new Smarty();

            $smarty->caching        = $cache;
            $smarty->cache_lifetime = $cacheLifeTime;
            $smarty->cache_dir      = DEFAULT_TEMPLATE_CACHE_DIR;
            $smarty->compile_dir    = DEFAULT_TEMPLATE_COMPILE_DIR;
            $smarty->template_dir   = DEFAULT_TEMPLATE_TEMPLATES_DIR;

            $smarty->_templateFile  = $templateFile;

            $smarty->php_handling   = false;
            $smarty->use_sub_dirs   = false;
            $smarty->assign($view->getAsArray());

            $smarty->display($templateFileName);
        }
    }

?>
