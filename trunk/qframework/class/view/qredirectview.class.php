<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qview.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qredirectrenderer.class.php");

    /**
     * Extends the original 'View' class to provide support for common operations, for example
     * to automatically add support for locale. It is recommended
     * that all classes that generate a view extend from this unless strictly necessary
     */
    class qRedirectView extends qView
    {
        var $_url;

        /**
         * Add function info here
         */
        function qRedirectView($url)
        {
            $this->qView(new qRedirectRenderer());
            $this->setUrl($url);
        }

        /**
         * Add function info here
         */
        function getUrl()
        {
            return $this->_url;
        }

        /**
         * Add function info here
         */
        function setUrl($url)
        {
            if (!eregi("^http://", $url))
            {
                $server = &qHttp::getServerVars();
                $url = "http://" . $server->getValue("HTTP_HOST") . dirname($server->getValue("PHP_SELF") . "/" . $url);
            }

            $this->_url = $url;
        }
    }
?>
