<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * @brief Motor de renderizado de la vista
     *
     * Clase abstracta para visualizar una qView. Derivar para cada tipo de vistas.
     *
     * @author  qDevel - info@qdevel.com
     * @date    06/03/2005 19:36
     * @version 1.0
     * @ingroup view
     */
    class qViewRenderer extends qObject
    {
        var $_engine;

        /**
         * Constructor
         */
        function qViewRenderer()
        {
            $this->qObject();
            $this->_engine = null;
        }

        /**
         * Devuelve una referencia al objeto motor de renderizado.
         *
         * @return object 
         * @exception qViewRenderer::getEgine Esta clase no usa motor de renderizado
         */
        function &getEngine()
        {
            if (empty($this->_engine))
            {
                throw(new qException("qViewRenderer::getEgine: This class do not work with any renderer engine."));
                die();
            }

            return $this->_engine;
        }

        /**
        * Add function info here
        */
        function setEngine($engine)
        {
            $this->_engine = &$engine;
        }

        /**
         * @brief Renderiza la vista
         *
         * El proceso de renderización consiste basicamente en subsituir todos los parámetros de 
         * la plantilla por los valores predefinidos.
         *
         * @return object 
         * @exception qViewRenderer::getEgine Esta clase no usa motor de renderizado
         */
        function render(&$view)
        {
            throw(new qException("qViewRenderer::render: This method must be implemented by child classes."));
            die();
        }
    }

?>
