<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qview.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qredirectrenderer.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qurl.class.php");
    
    /**
     * @brief Simple vista de redirección
     *
     * Redirecciona el navegador a otra URL
     *
     * @author  qDevel - info@qdevel.com
     * @date    06/03/2005 19:45
     * @version 1.0
     * @ingroup view
     * @see qRedirectRenderer
     */     
    class qRedirectView extends qView
    {
        var $_url;

        /**
         * Constructor
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
            $this->_url = $url;
        }
    }
?>
