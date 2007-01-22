<?php

    /**
     * @defgroup request Petición
     *
     */
         
    /**
     * @brief Classe abstracta que representa una petición.
     *
     * qRequest proporciona las funciones para manipular la información
     * de la petición del cliente como són los atributos, errores
     * y parámetros. Es posible incluso modificar la petición que
     * originalmente envió el cliente.
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 18:13
     * @version 1.0
     * @ingroup core request
     */        
    class qRequest extends qProperties
    {
        var $_attributes;
        var $_errors;
        var $_method;

        /**
         * Constructor
         */        
        function qRequest()
        {
            $this->qProperties();
        }
        
        /**
         * Borra todos los atribudos asignados
         */
        function clearAttributes ()
        {
            $this->_attributes = null;
            $this->_attributes = array();
        }
    
        /**
         * Devuelve un atributo
         *
         * @param  name <em>string</em> Nombre del atributo
         * @return mixed Valor del atributo, si existe, sino null.
         */
        function & getAttribute ($name)
        {
            $retval = null;
    
            if (isset($this->attributes[$name]))
            {
                return $this->attributes[$name];
            }
    
            return $retval;
        }
        
        /**
         * Devuelve los nombres de atributo asignados actualmente
         *
         * @return array Devuelve un vector indexado con los nombres de atributo.
         */
        function getAttributeNames ()
        {    
            return array_keys($this->attributes);    
        }
        
        /**
         * Devuelve un mensaje de error
         *
         * @param  name <em>string</em> Nombre del error.
         * @return string El mensaje de error, si existe el error, sino null.
         */
        function getError ($name)
        {
            $retval = null;
    
            if (isset($this->errors[$name]))
            {
                $retval = $this->errors[$name];
            }
    
            return $retval;
        }
    
        /**
         * Devuelve un vector con los identificadores de error
         *
         * @return array Vector indexado con todos los identificadores de error
         */
        function getErrorNames ()
        {
            return array_keys($this->errors);
        }
    
        /**
         * Devuelve un vector con todos los errores
         *
         * @return array Vector unidimensional associativo con todos los identificadores y mensajes
         */
        function getErrors ()
        {
            return $this->errors;
        }
    
        /**
         * Devuelve el método de petición fue usado para acceder a la página; 
         * es decir, 'GET', 'HEAD', 'POST', 'PUT'.
         *
         * @return int   Devuelve una de las constantes siguientes: REQUEST_METHOD_GET | REQUEST_METHOD_POST
         * @see    qHttp
         */
        function getMethod ()
        {
            return $this->method;
        }
        
        /**
         * Indica si un atributo existe.
         *
         * @param  name <em>string</em> Nombre del atributo
         * @return bool true, si el atributo existe, sino false.
         */
        function hasAttribute ($name)
        {    
            return isset($this->attributes[$name]);    
        }
       
        /**
         * Indica si ya existe un error
         *
         * @param  name <em>string</em> Nombre del error
         * @return bool true, si el error existe, sino false.
         */
        function hasError ($name)
        {    
            return isset($this->errors[$name]);    
        }
           
        /**
         * Indica si existe algún error
         *
         * @return bool true, si el error existe, sino false.
         */
        function hasErrors ()
        {    
            return (count($this->errors) > 0);    
        }
        
        /**
         * Elimina un atributo.
         *
         * @param  name  <em>string</em> Nombre del atributo
         * @return mixed Devuelve el valor del atributo, si el atributo es borrado, sino null.
         */
        function & removeAttribute ($name)
        {    
            $retval = null;
    
            if (isset($this->attributes[$name]))
            {    
                $retval =& $this->attributes[$name];
    
                unset($this->attributes[$name]);    
            }
    
            return $retval;    
        }
       
        /**
         * Remove an error.
         *
         * @param name string An error name.
         *
         * @return string An error message, if the error was removed, otherwise
         *                null.
         */
        function & removeError ($name)
        {    
            $retval = null;
    
            if (isset($this->errors[$name]))
            {    
                $retval =& $this->errors[$name];    
                unset($this->errors[$name]);    
            }
    
            return $retval;    
        }
        
        /**
         * Establece un atributo
         *
         * Si ya existe un atributo con este nombre se sobreescribe el valor anterior.
         *
         * @param name  <em>string</em> Nombre del atributo
         * @param value <em>mixed</em>  Valor del atributo
         * @see setAttributeByRef Para atributos que sean objetos
         */
        function setAttribute ($name, $value)
        {
            $this->attributes[$name] = $value;
        }
        
        /**
         * Establece un atributo por referencia
         *
         * Si ya existe un atributo con este nombre se sobreescribe el valor anterior.
         *
         * @param name  <em>string</em> Nombre del atributo
         * @param value <em>mixed</em>  Referencia al valor del atributo
         */
        function setAttributeByRef ($name, &$value)
        {
            $this->attributes[$name] =& $value;
        }
       
        /**
         * Establece una lista de atributos (por valor)
         *
         * La lista se añade a los atributos existentes, si ya existiera
         * un atributo con este nombre previamente, el nuevo
         * sobreescribiria el anterior.
         *
         * @param attributes <em>array</em> Vector unidimensional asociativo con los nombres y valores de atributo.
         */
        function setAttributes ($attributes)
        {
            $this->attributes = array_merge($this->attributes, $attributes);
        }
        
        /**
         * Establece una lista de atributos por referencia (sin copia)
         *
         * La lista se añade a los atributos existentes, si ya existiera
         * un atributo con este nombre previamente, el nuevo
         * sobreescribiria el anterior.
         *
         * @param attributes <em>array</em> Vector unidimensional asociativo con los nombres de atributo 
         *                                  y las referencias asociadas a un valor.
         */
        function setAttributesByRef (&$attributes)
        {
            foreach ($attributes as $key => $value)
            {
                $this->attributes[$key] =& $attributes[$value];
            }
        }
        
        /**
         * Añade un error
         *
         * @param name    <em>string</em> Identificador/Nombre del error
         * @param message <em>string</em> Mensaje del error
         *
         * @return void
         */
        function setError ($name, $message)
        {    
            $this->errors[$name] = $message;    
        }
           
        /**
         * Establece una lista de errores
         *
         * La lista se añade a los errores existentes, si ya existiera
         * un error con este indentificador previamente, el nuevo
         * sobreescribiria el anterior.
         *
         * @param errors array Un vector unidimensional asociativo con los indentificadores y mensajes asociados.
         */
        function setErrors ($errors)
        {
            $this->errors = array_merge($this->errors, $errors);
        }
    
        /**
         * Establece el método HTTP de la petición
         *
         * @param method <em>int</em> Introduzca una de las constantes siguientes: REQUEST_METHOD_GET | REQUEST_METHOD_POST
         */
        function setMethod ($method)
        {
            if ($method == REQUEST_METHOD_GET || $method == REQUEST_METHOD_POST)
            {
                $this->method = $method;
                return;
            }
    
            trigger_error("Invalid request method: '" . $method . "'", E_USER_ERROR);
            return;
        }
    }
    
?>