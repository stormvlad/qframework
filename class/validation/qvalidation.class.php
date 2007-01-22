<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /** 
      * @defgroup validation Sistema de validación 
      * <p>El sistema de validación del qFramework se basa en una implementación del patron 'Strategy' 
      * tal como puede verse en http://www.phppatterns.com/index.php/article/articleview/13/1/1/.</p>
      * <p>En qFramework se usa este patrón para validar los datos recibidos desde los formularios
      * con reglas y validadores. Cada regla <code>qRule</code> representa un paso simple de validación de datos.
      * Los validadores <code>qValidator</code> estan formados por conjuntos de reglas.</p>
      *
      */

    /**
     * @brief Base del sistema de validación
     *
     * @author  qDevel - info@qdevel.com
     * @date    05/03/2005 19:22
     * @version 1.0
     * @ingroup validation
     */
    class qValidation extends qObject
    {
        var $_error;

        /**
         * The constructor does nothing.
         */
        function qValidation()
        {
            $this->qObject();
            $this->_error = false;
        }

        /**
        *    Add function info here
        **/
        function setError($error)
        {
            $this->_error = $error;
        }

        /**
        *    Add function info here
        **/
        function getError()
        {
            return $this->_error;
        }

        /**
        *    Add function info here
        **/
        function validate($value)
        {
            trigger_error("This function must be implemented by child classes.", E_USER_ERROR);
            return;
        }
    }

?>