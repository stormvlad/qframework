<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /** 
     * @defgroup filter Filtros
     *
     * <p>El sistema de filtros del qFramework se basa en la implementación del patrón 'IterceptingFilter'.
     * Los filtros preprocesan las peticiones y postprocesan las respuestas.</p>
     * <p>Podemos crear filtros conectables para procesar servicios comunes de una forma estándar sin requerir 
     * cambios en el código principal del procesamiento de la petición. Los filtros interceptan las peticiones 
     * entrantes y las respuestas salientes, permitiendo un pre y post-procesamiento. Podemos añadir y eliminar 
     * estos filtros a discrección, sin necesitar cambios en nuestro código existente.
     * </p>
     * <p>Podemos, en efecto, decorar nuestro procesamiento principal con una veriedad de servicios comunes, 
     * como la seguridad, el logging, el depurado, etc. Estos filtros son componentes independientes del código 
     * de la aplicación principal, y pueden añadirse o eliminarse de forma declarativa. Por ejemplo, se podría 
     * modificar un fichero de configuración de despliegue para configurar una cadena de filtros. 
     * Cuando un cliente pide un recurso que corresponde con este mapeo de URL configurado, se procesa cada 
     * filtro de la cadena antes de poder invocar el recurso objetivo. </p>
     * <p>Mas información:</p>
     * - http://java.sun.com/blueprints/corej2eepatterns/Patterns/InterceptingFilter.html
     * - http://www.programacion.com/java/tutorial/patrones/3/ (es)
     * - http://wact.sourceforge.net/index.php/InterceptingFilter
     *
     */
      
    /**
     * @brief Filtro de preprocesado y postprocesado genérico
     *
     * Classe abstracta de la cual se derivan todos los objetos que se usan en el pipeline.
     * Define las operaciones básicas y métodos que se tienen usar. 
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
         * Ejecuta el preproceso de la lógica del filtro, llama al siguiente filtro de la cadena
         * y por último ejecuta el postproceso.
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