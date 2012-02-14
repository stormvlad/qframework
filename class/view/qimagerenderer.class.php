<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qviewrenderer.class.php");

    /**
     * @brief Motor de renderizado para imágenes
     *
     * @author  qDevel - info@qdevel.com
     * @date    21/04/2005 12:42
     * @version 1.0
     * @ingroup view
     * @see qImageView
     */
     class qImageRenderer extends qViewRenderer
     {
        /**
        * Add function info here
        */
        function qImageRenderer()
        {
            $this->qViewRenderer();
        }

        /**
        * Add function info here
        */
        function render(&$view)
        {
            header("Content-Type: " . $view->getMimeType());
            return $view->getContent();
        }
    }

?>