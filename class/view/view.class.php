<?php

    include_once("framework/class/object/object.class.php" );
    include_once("framework/class/config/properties.class.php" );

    /**
     * Methods provided by the View object that every object inheriting from this
     * should implement
     */
    class View extends Object {

        var $_params;

        /**
         * Constructor. By default, does nothing
         */

        function View()
        {
            $this->Object();

            $this->_params = new Properties();
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
         * Renders the view. Here we would ideally call a template engine, using the
         * values in $this->_params to fill the template 'context' and then display
         * everything.
         *
         * By default does nothing and it has no parameters
         */
        function render()
        {
            throw(new Exception("View::render: This method must be implemented by child classes."));
            die();
        }
     }
?>
