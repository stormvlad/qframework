<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * @brief Obtención de metadatos de las clases
     *
     * Clase estática parecida a la que proporciona Java para obtener metainformación de las clases.
     *
     * @author  qDevel - info@qdevel.com
     * @date    06/03/2005 19:22
     * @version 1.0
     * @ingroup core
     */

    class qReflection extends qObject
    {
        /**
         * Constructor. Does nothing.
         */
        function qReflection()
        {
            $this->qObject();
        }

        /**
         * Returns true if the class has a method called $methodName
         *
         * @param class qObject An object of the class we want to check
         * @param methodName <code>string</code> Name of the method we want to check
         * @return Returns true if method exists otherwise, false
         */
        function methodExists(&$class, $methodName)
        {
            return method_exists($class, $methodName);
        }

        /**
         * Returns all the methods available in the class. It returns both the methods from the
         * class itself <b>as well as</b> all
         *
         * @param class The class from which we would like to check the methods
         * @return An array containing all the methods available.
         */
        function getClassMethods(&$class)
        {
            return get_class_methods($class);
        }
    }
?>