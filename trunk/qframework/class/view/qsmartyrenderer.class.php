<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/smarty/Smarty.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qviewrenderer.class.php");

    define(DEFAULT_SMARTY_CACHE_DIR, "tmp/");
    define(DEFAULT_SMARTY_COMPILE_DIR, "tmp/");
    define(DEFAULT_SMARTY_TEMPLATES_DIR, "templates/");
    define(DEFAULT_SMARTY_TEMPLATES_EXTENSION, ".templates");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qSmartyRenderer extends qViewRenderer
    {
        var $_cacheDir;
        var $_compileDir;
        var $_templatesDir;
        var $_templatesExtension;

        /**
        * Add function info here
        */
        function qSmartyRenderer()
        {
            $this->qViewRenderer();

            $this->_cacheDir           = DEFAULT_SMARTY_CACHE_DIR;
            $this->_compileDir         = DEFAULT_SMARTY_COMPILE_DIR;
            $this->_templatesDir       = DEFAULT_SMARTY_CACHE_DIR;
            $this->_templatesExtension = DEFAULT_SMARTY_TEMPLATES_EXTENSION;
        }

        /**
        * Add function info here
        */
        function getCacheDir()
        {
            return $this->_cacheDir;
        }

        /**
        * Add function info here
        */
        function setCacheDir($dir)
        {
            $this->_cacheDir = $dir;
        }

        /**
        * Add function info here
        */
        function getCompileDir()
        {
            return $this->_compileDir;
        }

        /**
        * Add function info here
        */
        function setCompileDir($dir)
        {
            $this->_compileDir = $dir;
        }

        /**
        * Add function info here
        */
        function getTemplatesDir()
        {
            return $this->_templatesDir;
        }

        /**
        * Add function info here
        */
        function setTemplatesDir($dir)
        {
            $this->_templatesDir = $dir;
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

            $smarty = new Smarty();

            $smarty->caching        = $cache;
            $smarty->cache_lifetime = $cacheLifeTime;
            $smarty->cache_dir      = $this->_cacheDir;
            $smarty->compile_dir    = $this->_compileDir;
            $smarty->template_dir   = $this->_templatesDir;

            $smarty->_templateFile  = $templateFile;

            $smarty->php_handling   = false;
            $smarty->use_sub_dirs   = false;
            $smarty->assign($view->getAsArray());

            return $smarty->fetch($templateFileName);
        }
    }

?>
