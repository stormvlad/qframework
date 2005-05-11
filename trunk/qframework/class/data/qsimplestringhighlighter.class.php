<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qstringhighlighter.class.php");

    /**
     * @brief Resaltador simple de terminos encontrados en una cadena
     *
     * Añade en una cadena el código HTML necesario para resaltar el fondo
     * de los terminos que se especifiquen y se encuentren en la misma cadena.
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 17:25
     * @version 1.0
     * @ingroup data
     * @note Se puede usar como un modificador de Smarty
     */
    class qSimpleStringHighlighter extends qStringHighlighter
    {
        /**
        * Add function info here
        */
        function qSimpleStringHighlighter($colors = null)
        {
            $this->qStringHighlighter($colors);
        }

        /**
        * Add function info here
        */
        function highlight($str, $terms, $exactWords = false, $caseSensitive = false)
        {
            $totalTerms  = count($terms);
            $totalColors = count($this->_colors);

            if ($totalColors == 0 || $totalTerms == 0)
            {
                return $str;
            }

            for ($i = 0; $i < $totalTerms; $i++)
            {
                $term  = preg_replace("|([/+-?*])|", "\\$1", trim($terms[$i]));
                $term  = qFormat::regexpSearchExpand($term, $caseSensitive);
                $term  = str_replace("/", "\\/", $term);
                $color = $this->_colors[$i % $totalColors];
                $str   = $this->highlightTerm($str, $term, $color, $exactWords, $caseSensitive);
            }

            return $str;
        }
    }

?>