<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qproperties.class.php");

    /**
     * @defgroup view Vista
     * @ingroup core
     */

    /**
     * @brief Representa la capa de presentaci�n
     *
     * Cada vista qView representa la capa de presentaci�n de una acci�n.
     * La salida puede ser rellenada con atributos, que la plantilla puede
     * manipular i mostrar libremente.
     *
     * @author  qDevel - info@qdevel.com
     * @date    06/03/2005 19:36
     * @version 1.0
     * @ingroup core view
     */
     
    class qView extends qObject
    {
        var $_renderer;
        var $_params;

        /**
         * Constructor. Por defecto no hace nada
         *
         * @param renderer qViewRenderer Objeto de render
         */
        function qView(&$renderer)
        {
            $this->qObject();

            $this->_renderer = &$renderer;
            $this->_params   = new qProperties();
        }

        /**
         * Establece el sistema de renderizado si existe
         *
         * @param renderer qViewRenderer Objeto de render
         */
        function setRenderer(&$renderer)
        {
            $this->_renderer = &$renderer;
        }

        /**
         * Recupera una referencia al sistema de renderizado
         *
         * @return qViewRenderer
         */
        function &getRenderer()
        {
            return $this->_renderer;
        }

        /**
         * Establece un s�lo par�metro. 
         *
         * Este valor se podr� usar desde la plantilla
         *
         * @param name string Nombre del par�metro
         * @param value object Valor del par�metro
         */
        function setValue($name, $value)
        {
            $this->_params->setValue($name, $value);
        }

        /**
         * Establece un array de valores para la vista
         *
         * @param values array Array asociativo con nombre y valor de los par�metros
         */
        function setValues($values)
        {
            $this->_params->setValues($values);
        }

        /**
         * Devuelve el valor que se identifica con este nombre
         *
         * @param name string Nombre del par�metro
         * @return object
         */
        function getValue($name)
        {
            return $this->_params->getValue($name);
        }

        /**
         * Devuelve un array asociativo con los valores identificados con una clave
         *
         * @return array Array con nombre y valor de los par�metros
         */
        function getAsArray()
        {
            return $this->_params->getAsArray();
        }

        /**
        * Add function info here
        */
        function &getRendererEngine()
        {
            return $this->_renderer->getEngine();
        }

        /**
         * Renderiza la vista. Here we would ideally call a template engine, using the
         * values in $this->_params to fill the template 'context' and then display
         * everything.
         *
         * By default does nothing and it has no parameters
         */

        function render()
        {
            return $this->_renderer->render($this);
        }
     }
?>
