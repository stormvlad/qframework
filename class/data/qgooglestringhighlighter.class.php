<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qstringhighlighter.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qformat.class.php");

   /**
     * @brief Resaltador tipo Google de terminos encontrados en una cadena
     *
     * Añade en una cadena el código HTML necesario para resaltar el fondo
     * de los terminos que se especifiquen y se encuentren en la misma cadena.
     *
     * Por ejemplo se puede crear un efecto visual que resalte las palabras de una
     * búsqueda, tal como lo hace Google en sus búsquedas.
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 17:25
     * @version 1.0
     * @ingroup data
     * @note Se puede usar como un modificador de Smarty
     */
    class qGoogleStringHighlighter extends qStringHighlighter
    {
        /**
        * Add function info here
        */
        function qGoogleStringHighlighter($colors = null)
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
            
            for ($i = 0, $j = 0; $i < $totalTerms; $i++, $j++)
            {
                $term = trim($terms[$i]);
                $char = substr($term, 0, 1);

                if ($char == "-")
                {
                    $j--;
                }
                else
                {
                    $color = $this->_colors[$j % $totalColors];

                    if ($char == "+")
                    {
                        $term = substr($term, 1);
                        $term = preg_quote($term, "/");
                    }
                    else if ($char == "\"")
                    {
                        $term = substr($term, 1, -1);
                        $term = preg_quote($term, "/");
                    }
                    else
                    {
                        $term = preg_quote($term, "/");
                        $term = qFormat::regexpSearchExpand($term, $caseSensitive);
                    }

                    $str = $this->highlightTerm($str, $term, $color, $exactWords, $caseSensitive);
                }
            }

            return $str;
        }
    }

?>