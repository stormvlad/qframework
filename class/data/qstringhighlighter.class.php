<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * @brief Resaltador de terminos encontrados en una cadena
     * 
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 17:03
     * @version 1.0
     * @ingroup data
     * @note Se puede usar como un modificador de Smarty
     */
    class qStringHighlighter extends qObject
    {
        var $_colors;

        /**
        * Add function info here
        */
        function qStringHighlighter($colors = null)
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
        function highlight($str, $terms, $exactWords = false, $caseSensitive = false)
        {
            throw(new qException("qStringHighlighter::highlight: This method must be implemented by child classes."));
            die();
        }
    }

?>