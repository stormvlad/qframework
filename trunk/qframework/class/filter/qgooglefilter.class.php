<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/filter/qfilter.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qurl.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qgooglestringhighlighter.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qgooglesearchrequestparser.class.php");

    /**
     * @brief Resalta las palabras búscadas mediante Google
     *
     * Filtro para resaltar las palabras buscadas con el motor Google si la petícion procede de allí.
     *
     * @author  qDevel - info@qdevel.com
     * @date    07/03/2005 23:46
     * @version 1.0
     * @ingroup filter
     */
    class qGoogleFilter extends qFilter
    {
        var $_colors;
        
        /**
        * Add function info here
        */
        function qGoogleFilter($colors = null)
        {
            $this->qFilter();
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
        function run(&$filtersChain)
        {
            ob_start();
            $filtersChain->run();
            $text = ob_get_contents();
            ob_end_clean();

            $server = &qHttp::getServerVars();
            $url    = new qUrl($server->getValue("HTTP_REFERER"));

            if (preg_match("/^http:\/\/w?w?w?\.?google.*[?&]q=.*/i", $url->getUrl()) && preg_match("/^(.+<body[^>]*>)(.*)(<\/body>.+)$/si", $text, $regs))
            {
                $pre  = $regs[1];
                $body = $regs[2];
                $post = $regs[3];
            
                $queryArray = $url->getQueryArray();

                if (!empty($queryArray["q"]))
                {
                    $parser = new qGoogleSearchRequestParser($this->_colors);
                    $parser->parse($queryArray["q"]);
                    
                    $terms    = $parser->getAllTerms();
                    $strTerms = $parser->getSearchTermsString();
                    $lighter  = new qGoogleStringHighlighter($this->_colors);
                    $body     = $lighter->highlight($body, $terms, true, false);
                    $text     = $pre . str_replace("[:GOOGLE_FILTER_TERMS:]", $strTerms, $body) . $post;
                }
            }

            print $text;
        }
    }
?>