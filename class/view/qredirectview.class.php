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
                $server   = &qHttp::getServerVars();
                $protocol = "http";
                $uri      = $server->getValue("HTTP_HOST") . dirname($server->getValue("PHP_SELF")) . "/";

                if (substr($url, 0, 1) == "?")
                {
                    $uri .= basename($server->getValue("PHP_SELF"));
                }

                if ($server->getValue("HTTPS") == "on" || eregi("^https", $server->getValue("HTTP_REFERER")))
                {
                    $protocol = "https";
                }

                $uri    = str_replace("//", "/", $uri);
                $result = $protocol . "://" . $uri . $url;
            }
            else
            {
                $result = $url;
            }

            $this->_url = $result;
        }
    }
?>
