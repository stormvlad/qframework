<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qproperties.class.php");

    /**
     * Una vista qView representa la capa de presentació de la acció.  
     * La sortida pot ser emplenada omplint atributs, que la plantilla 
     * pot manipular i mostrar.
     *
     * Methods provided by the View object that every object inheriting from this
     * should implement
     * 
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
         * Establece un sólo parámetro. 
         *
         * Este valor se podrá usar desde la plantilla
         *
         * @param name string Nombre del parámetro
         * @param value object Valor del parámetro
         */
        function setValue($name, $value)
        {
            $this->_params->setValue($name, $value);
        }

        /**
         * Establece un array de valores para la vista
         *
         * @param values array Array asociativo con nombre y valor de los parámetros
         */
        function setValues($values)
        {
            $this->_params->setValues($values);
        }

        /**
         * Devuelve el valor que se identifica con este nombre
         *
         * @param name string Nombre del parámetro
         * @return object
         */
        function getValue($name)
        {
            return $this->_params->getValue($name);
        }

        /**
         * Devuelve un array asociativo con los valores identificados con una clave
         *
         * @return array Array con nombre y valor de los parámetros
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
