<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/libs/smarty/Smarty.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qsmartyrenderer.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/timer/qtimer.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qfile.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qcurl.class.php");
    
    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qFopRenderer extends qSmartyRenderer
    {
        /**
        * Add function info here
        */
        function qFopRenderer()
        {
            $this->qSmartyRenderer();
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
            
            $curl = new qCurl($view->_fopUrl);
            
            $result = str_replace("&","&amp;",$result);
            $result = str_replace("&amp;lt;","&lt;", $result);
            $result = str_replace("&amp;gt;","&gt;", $result);
            $result = str_replace("�","&#x20ac;",$result);
            
            $variables  = "fo=" . utf8_encode(urlencode($result));
            $variables .= "&file=/tmp/" . md5(microtime()) . ".fo";
            
            $curl->setVariables($variables);
            
            $result = $curl->execute();

            $params["seconds"] = $timer->get();
            $this->sendEvent(2, $params);

            return $result;
        }
    }

?>
