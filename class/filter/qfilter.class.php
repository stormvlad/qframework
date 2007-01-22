<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /** 
     * @defgroup filter Filtros
     *
     * <p>El sistema de filtros del qFramework se basa en la implementaci�n del patr�n 'IterceptingFilter'.
     * Los filtros preprocesan las peticiones y postprocesan las respuestas.</p>
     * <p>Podemos crear filtros conectables para procesar servicios comunes de una forma est�ndar sin requerir 
     * cambios en el c�digo principal del procesamiento de la petici�n. Los filtros interceptan las peticiones 
     * entrantes y las respuestas salientes, permitiendo un pre y post-procesamiento. Podemos a�adir y eliminar 
     * estos filtros a discrecci�n, sin necesitar cambios en nuestro c�digo existente.
     * </p>
     * <p>Podemos, en efecto, decorar nuestro procesamiento principal con una veriedad de servicios comunes, 
     * como la seguridad, el logging, el depurado, etc. Estos filtros son componentes independientes del c�digo 
     * de la aplicaci�n principal, y pueden a�adirse o eliminarse de forma declarativa. Por ejemplo, se podr�a 
     * modificar un fichero de configuraci�n de despliegue para configurar una cadena de filtros. 
     * Cuando un cliente pide un recurso que corresponde con este mapeo de URL configurado, se procesa cada 
     * filtro de la cadena antes de poder invocar el recurso objetivo. </p>
     * <p>Mas informaci�n:</p>
     * - http://java.sun.com/blueprints/corej2eepatterns/Patterns/InterceptingFilter.html
     * - http://www.programacion.com/java/tutorial/patrones/3/ (es)
     * - http://wact.sourceforge.net/index.php/InterceptingFilter
     *
     */
      
    /**
     * @brief Filtro de preprocesado y postprocesado gen�rico
     *
     * Classe abstracta de la cual se derivan todos los objetos que se usan en el pipeline.
     * Define las operaciones b�sicas y m�todos que se tienen usar. 
     *
     * @author  qDevel - info@qdevel.com
     * @date    07/03/2005 23:46
     * @version 1.0
     * @ingroup filter     
     */
    class qFilter extends qObject
    {
        /**
         * Constructor
         */
        function qFilter()
        {
            $this->qObject();
        }

        /**
         * Ejecuta el preproceso de la l�gica del filtro, llama al siguiente filtro de la cadena
         * y por �ltimo ejecuta el postproceso.
         *
         * @param filtersChain qFilterChain Pipeline de filtros
         */
        function run(&$filtersChain)
        {
            trigger_error("This method must be implemented by child classes.", E_USER_ERROR);
            return;
        }
    }
?>