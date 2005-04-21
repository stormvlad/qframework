<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qview.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qimagerenderer.class.php");

    /**
     * @brief Vista para mostrar imágenes
     *
     * Vista que muestra una imagen enviando la cabecera con el
     * Content-Type adecuado y los datos binarios de la imagen
     *
     * @author  qDevel - info@qdevel.com
     * @date    21/04/2005 12:43
     * @version 1.0
     * @ingroup view
     * @see qImageRenderer
     */
    class qImageView extends qView
    {
        var $_content;
        var $_mimeType;

        /**
         * Constructor
         */
        function qImageView($content, $mimeType)
        {
            $this->qView(new qImageRenderer());

            $this->_content  = $content;
            $this->_mimeType = $mimeType;
        }

        /**
        * Add function info here
        */
        function getContent()
        {
            return $this->_content;
        }

        /**
        * Add function info here
        */
        function getMimeType()
        {
            return $this->_mimeType;
        }

        /**
        * Add function info here
        */
        function setContent($content)
        {
            $this->_content = $content;
        }

        /**
        * Add function info here
        */
        function setMimeType($type)
        {
            $this->_mimeType = $type;
        }
    }
?>
