<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * @brief Base del analizador de cadenas de busqueda 
     * 
     * Descompone una cadena de carácteres compleja en terminos 
     * simples para realizar una búsqueda.
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 17:03
     * @version 1.0
     * @ingroup data
     */
    class qSearchRequestParser extends qObject
    {
        var $_colors;

        /**
        * Constructor
        */
        function qSearchRequestParser($colors = null)
        {
            $this->qObject();

            $this->_colors = $colors;

            if (empty($colors))
            {
                $this->_colors = array("yellow", "lightpink", "aquamarine", "darkgoldenrod", "darkseagreen", "lightgreen", "rosybrown", "seagreen", "chocolate", "violet");
            }
        }

        /**
        * Add function info here
        */
        function getColors()
        {
            return $this->_colors;
        }

        /**
        * Add function info here
        */
        function setColors($colors)
        {
            $this->_colors = $colors;
        }

        /**
        * Add function info here
        */
        function reset()
        {
            trigger_error("This function must be implemented by child classes.", E_USER_ERROR);
            return;
        }

        /**
        * Add function info here
        */
        function getSearchTermsString()
        {
            trigger_error("This function must be implemented by child classes.", E_USER_ERROR);
            return;
        }

        /**
        * Add function info here
        */
        function parse($request)
        {
            trigger_error("This function must be implemented by child classes.", E_USER_ERROR);
            return;
        }
    }

?>