<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qproperties.class.php");

    /**
     * @defgroup http Variables predefinidas
     *
     * Ofrece una capa de compatibilidad con versiones anteriores a PHP 4.1.0.
     *
     * Con esta clase podemos acceder a:
     * - Variables de servidor: $_SERVER
     * - Cookies HTTP: $_COOKIE
     * - Variables HTTP GET: $_GET
     * - Variables HTTP POST: $_POST
     * - Variables de petición: $_REQUEST
     * - Variables de carga de archivos HTTP: $_FILES
     *
     * Mas información:
     * http://es.php.net/manual/es/language.variables.external.php
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 13:36
     * @ingroup net
     */
      
    /**
     * @brief Clase base para variables predefinidas
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 13:36
     * @version 1.0
     * @ingroup net http
     */
    class qHttpVars extends qProperties
    {
        /**
         * Add function info here
         */
        function qHttpVars($params = null)
        {
            $this->qProperties($params);
        }

        /**
         * Add function info here
         */
        function _save(&$vars, $values)
        {
            foreach ($values as $key => $value)
            {
                $vars[$key] = $value;
            }
        }

        /**
         * Add function info here
         */
        function save()
        {
            throw(new qException("qHttpVars::save: This method must be implemented by child classes."));
            die();
        }
    }
?>
