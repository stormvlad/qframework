<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/libs/smarty/Smarty.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qviewrenderer.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/timer/qtimer.class.php");

    define("DEFAULT_SMARTY_CACHE_DIR", "tmp/");
    define("DEFAULT_SMARTY_COMPILE_DIR", "tmp/");
    define("DEFAULT_SMARTY_TEMPLATES_DIR", "templates/");
    define("DEFAULT_SMARTY_TEMPLATES_EXTENSION", ".template");

    /**
     * @brief Motor de renderizado Smarty
     *
     * @author  qDevel - info@qdevel.com
     * @date    06/03/2005 19:36
     * @version 1.0
     * @ingroup view
     * @see qSmartyView
     */
    class qSmartyRenderer extends qViewRenderer
    {
        var $_templatesExtension;

        /**
         * Constructor
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

            $this->registerEvent(1, "RENDER_METHOD_STARTS");
            $this->registerEvent(2, "RENDER_METHOD_ENDS");
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
            $timer  = new qTimer();
            $server = &qHttp::getServerVars();
            $params = array(
                "ip"         => qClient::getIp(),
                "class"      => $this->getClassName(),
                "script"     => basename($server->getValue("PHP_SELF")),
                "uri"        => $server->getValue("REQUEST_URI")
                );

            $this->sendEvent(1, $params);
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

            $result = $this->_engine->fetch($templateFileName);

            $params["seconds"] = $timer->get();
            $this->sendEvent(2, $params);

            return $result;
        }
    }

?>
