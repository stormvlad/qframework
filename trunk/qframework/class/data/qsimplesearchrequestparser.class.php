<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qsearchrequestparser.class.php");

    /**
     * @brief Analizador de cadenas de búsqueda
     * 
     * Descompone la cadena en terminos deshechando los espacios encontrados entre palabras.
     * Cada palabra equivale a un termino de búsqueda.
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 17:03
     * @version 1.0
     * @ingroup data
     */
    class qSimpleSearchRequestParser extends qSearchRequestParser
    {
        var $_terms;

        /**
        * Constructor
        */
        function qSimpleSearchRequestParser($colors = null)
        {
            $this->qSearchRequestParser($colors);
            $this->_terms = array();
        }

        /**
        * Add function info here
        */
        function reset()
        {
            $this->_terms = array();
        }

        /**
        * Add function info here
        */
        function getTerms()
        {
            return $this->_terms;
        }

        /**
        * Add function info here
        */
        function getSearchTermsString()
        {
            $totalTerms  = count($this->_terms);
            $totalColors = count($this->_colors);

            if ($totalColors == 0)
            {
                return trim(implode($this->_terms));
            }

            $i = 0;
            $result = "";

            foreach ($this->_terms as $term)
            {
                $color   = $this->_colors[$i++ % $totalColors];
                $result .= "<span style=\"background:" . $color . "\">" . $term . "</span> ";
            }

            return trim($result);
        }

        /**
        * Add function info here
        */
        function parse($request)
        {
            $this->reset();
            $this->_terms = split("[[:alnum:]_]+", trim($request));

            return true;
        }
    }

?>