<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * qMessage contains information about a log message.
     *
     * @since   1.0
     */
    class qMessage extends qObject
    {
        /**
         * An associative array of message parameters.
         *
         * @private
         * @since  1.0
         * @type   array
         */
        var $params;

        /**
         * Create a new qMessage instance.
         *
         * @param params An associative array of parameters.
         *
         * @public
         * @since  1.0
         */
        function &qMessage ($params = NULL)
        {
            parent::qObject();

            $this->params = ($params == NULL) ? array() : $params;
        }

        /**
         * Retrieve a parameter.
         *
         * @param name A parameter name.
         *
         * @return string A parameter value, if a parameter with the given name
         *                exists, otherwise <b>NULL</b>.
         *
         * @public
         * @since  1.0
         */
        function &getParameter ($name)
        {
            if (isset($this->params[$name]))
            {
                return $this->params[$name];
            }

            return NULL;
        }

        /**
         * Determine if a parameter was set.
         *
         * @param name string A parameter name.
         *
         * @return bool <b>TRUE</b>, if the parameter has been set, otherwise
         *              <b>FALSE</b>.
         *
         * @public
         * @since  1.0
         */
        function hasParameter ($name)
        {
            return isset($this->params[$name]);
        }

        /**
         * Set a parameter.
         *
         * @param name string A parameter name.
         * @param value string A parameter value.
         *
         * @public
         * @since  1.0
         */
        function setParameter ($name, $value)
        {
            $this->params[$name] = $value;
        }

        /**
         * Set a parameter by reference.
         *
         * @param name string A parameter name.
         * @param value string A parameter value.
         *
         * @public
         * @since  1.0
         */
        function setParameterByRef ($name, &$value)
        {
            $this->params[$name] =& $value;
        }
    }

?>