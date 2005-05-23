<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qproperties.class.php");

    /**
     * @defgroup view Vista
     * @ingroup core
     */

    /**
     * @brief Representa la capa de presentaci�n
     *
     * Cada vista qView representa la capa de presentaci�n de una acci�n.
     * La salida puede ser rellenada con atributos, que la plantilla puede
     * manipular i mostrar libremente.
     *
     * Esta es una clase abstracta que debe derivarse seg�n el motor de
     * renderizado que usemos.
     *
     * @author  qDevel - info@qdevel.com
     * @date    06/03/2005 19:36
     * @version 1.0
     * @ingroup core view
     */

    class qView extends qObject
    {
        var $_renderer;
        var $_params;

        /**
         * @brief Constructor.
         *
         * Por defecto no hace nada
         *
         * @param renderer <em>qViewRenderer</em> Objeto de render
         */
        function qView(&$renderer)
        {
            $this->qObject();

            $this->_renderer = &$renderer;
            $this->_params   = new qProperties();
        }

        /**
         * @brief Establece el sistema de renderizado si existe
         *
         * @param renderer <em>qViewRenderer</em> Objeto de render
         */
        function setRenderer(&$renderer)
        {
            $this->_renderer = &$renderer;
        }

        /**
         * @brief Recupera una referencia al sistema de renderizado
         *
         * @return qViewRenderer
         */
        function &getRenderer()
        {
            return $this->_renderer;
        }

        /**
         * @brief Establece un s�lo par�metro.
         *
         * Este valor se podr� usar desde la plantilla
         *
         * @param name string Nombre del par�metro
         * @param value object Valor del par�metro
         */
        function setValue($name, $value)
        {
            $this->_params->setValue($name, $value);
        }

        /**
         * @brief Establece un array de valores para la vista
         *
         * @param values array Array asociativo con nombre y valor de los par�metros
         */
        function setValues($values)
        {
            $this->_params->setValues($values);
        }

        /**
         * @brief Devuelve el valor que se identifica con este nombre
         *
         * @param name <em>string</em> Nombre del par�metro
         * @return object
         */
        function getValue($name)
        {
            return $this->_params->getValue($name);
        }

        /**
         * @brief Devuelve un array asociativo con los valores identificados con una clave
         *
         * @return array Array con nombre y valor de los par�metros
         */
        function getAsArray()
        {
            return $this->_params->getAsArray();
        }

        /**
         * @brief Devuelve el motor de renderizado de la vista.
         *
         * @return qViewRenderer Motor de renderizado de la vista
         */
        function &getRendererEngine()
        {
            return $this->_renderer->getEngine();
        }

        /**
         * @brief Renderiza la vista.
         *
         * Llamamos al m�todo qViewRenderer::render del motor de renderizado.
         * El proceso de renderizaci�n consiste basicamente en subsituir todos los par�metros de
         * la plantilla por los valores predefinidos.
         *
         * @return bool Devuelve si se ha concluido con �xito
         * @see qViewRenderer::render Render
         */
        function render()
        {
            return $this->_renderer->render($this);
        }
     }
?>
