<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qviewrenderer.class.php");

    /**
     * @brief Motor para la vista de redirección
     *
     * Añade una cabecera <code>Location</code> en la respuesta HTTP
     *
     * @author  qDevel - info@qdevel.com
     * @date    06/03/2005 19:46
     * @version 1.0
     * @ingroup view
     * @see qRedirectView
     */   
    class qRedirectRenderer extends qViewRenderer
    {
        /**
        * Add function info here
        */
        function qRedirectRenderer()
        {
            $this->qViewRenderer();
        }

        /**
        * Add function info here
        */
        function render(&$view)
        {
            $url = new qUrl($view->getUrl());
            header("Location: " . $url->getUrl());
        }
    }

?>
