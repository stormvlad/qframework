<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttp.class.php");

    define("DEFAULT_ABSOLUTE_URL", false);

    /**
     * @brief Generador de cadenas con una petici�n HTTP GET
     *
     * Generaci�n de forma transparente de una cadena de par�metros para realizar
     * una URL con una petici�n de formato HTTP GET.
     *
     * El uso de esta clase proporciona una forma f�cil de modificar el formato 
     * de las URL en un futuro si fuera necesario de forma f�cil.
     *
     * <pre>
     * $g = new RequestGenerator();
     * $g->addParameter( "op", "ViewArticle" );
     * $g->addParameter( "articleId", 1 );
     * $request = $g->getRequest();
     * </pre>
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 18:21
     * @version 1.0
     * @ingroup request
     */
    class qRequestGenerator extends qObject
    {
        var $_params;
        var $_baseUrl;
        var $_dirName;
        var $_xhtmlEnabled;

        /**
         * Constructor.
         */
        function qRequestGenerator()
        {
            $this->qObject();

            $server              = &qHttp::getServerVars();

            $this->_params       = Array();
            $this->_dirName      = dirname($server->getValue("PHP_SELF"));
            $this->_baseUrl      = "http://" . $server->getValue("SERVER_NAME") . $this->_dirName;
            $this->_xhtmlEnabled = true;
        }

        function getBaseUrl($abs = DEFAULT_ABSOLUTE_URL)
        {
            throw(new qException("RequestGenerator::getBaseUrl: This function must be implemented by child classes."));
            die();
        }

        function getIndexUrl($abs = DEFAULT_ABSOLUTE_URL)
        {
            throw(new qException("RequestGenerator::getIndexUrl: This function must be implemented by child classes."));
            die();
        }

        /**
         * Returns a string representing the request
         *
         * @return A String object representing the request
         */
        function getRequest()
        {
            throw(new qException("RequestGenerator::getRequest: This function must be implemented by child classes."));
            die();
        }

        function getUrl($res, $abs = DEFAULT_ABSOLUTE_URL)
        {
            return ereg_replace("^/+", "/", $this->getBaseUrl($abs) . $res);
        }

        /**
         * Adds a parameter to the request
         *
         * @param paramName Name of the parameter
         * @param paramValue Value given to the parameter
         */
        function addParameter($paramName, $paramValue)
        {
            $this->_params[$paramName] = $paramValue;
        }
        
        /**
         * Extrae una lista de parametros 
         *
         * @param  keys  <em>array</em> Nombre/Identificador de las propiedades
         * @return array Vector unidimensional asociativo con los nombres y valores, 
         *               s�lo las propiedades encontradas
         */       
        function & extract ($keys)
        {
            $array = array();
    
            foreach ($this->_params as $key => $value)
            {
                if (in_array($key, $keys))
                {
                    $array[$key] =& $this->_params[$key];
                }
            }
    
            return $array;
        }      

        /**
         * Establece modo de generaci�n de URLs compatible con XHTML
         *
         * @param enable Name of the parameter
         */
        function setXhtml($enable = true)
        {
            $this->_xhtmlEnabled = $enable;
        }

        /**
         * Devuelve si el generador est� en modo de URLs compatible con XHTML
         *
         * @returns boolean Devuelve true si est� en modo XHTML
         */
        function isXhtml()
        {
            return $this->_xhtmlEnabled;
        }
    }
?>
