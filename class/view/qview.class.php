<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qproperties.class.php");

    /**
     * Methods provided by the View object that every object inheriting from this
     * should implement
     */
    class qView extends qObject
    {
        var $_renderer;
        var $_params;

        /**
         * Constructor. By default, does nothing
         */
        function qView(&$renderer)
        {
            $this->qObject();

            $this->_renderer = &$renderer;
            $this->_params   = new qProperties();
        }

        /**
        * Sets a single parameter
        * @param name Name of the parameter
        * @param value Value of the parameter
        */
        function setRenderer(&$renderer)
        {
            $this->_renderer = &$renderer;
        }

        /**
        * Sets a single parameter
        * @param name Name of the parameter
        * @param value Value of the parameter
        */
        function &getRenderer()
        {
            return $this->_renderer;
        }

        /**
        * Sets a single parameter
        * @param name Name of the parameter
        * @param value Value of the parameter
        */
        function setValue($name, $value)
        {
            $this->_params->setValue($name, $value);
        }

        /**
        * Sets a single parameter
        * @param name Name of the parameter
        * @param value Value of the parameter
        */
        function setValues($values)
        {
            $this->_params->setValues($values);
        }

        /**
        * Returns the value identified by the key $key
        *
        * @param name The key
        * @return The value associated to that key
        */
        function getValue($name)
        {
            return $this->_params->getValue($name);
        }

        /**
        * Returns the value identified by the key $key
        *
        * @param name The key
        * @return The value associated to that key
        */
        function getAsArray()
        {
            return $this->_params->getAsArray();
        }

        /**
        * Renders the view. Here we would ideally call a template engine, using the
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
