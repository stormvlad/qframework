<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    define("DEFAULT_FILE_LIST_CASE_SENSITIVE", true);
    define("FILE_LIST_SORT_NAME", 1);
    define("FILE_LIST_SORT_SIZE", 2);
    define("FILE_LIST_SORT_DATE", 4);

    define("FILE_LIST_SORT_ASC",  1);
    define("FILE_LIST_SORT_DESC", 2);

    /**
     * @brief Lista los archivos de un directorio
     * 
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 13:58
     * @version 1.0
     * @ingroup file
     */
    class qFileList extends qObject
    {
        var $_lister;

        /**
        *  Add function info here
        */
        function qFileList(&$lister)
        {
            $this->qObject();
            $this->_lister = &$lister;
        }

        /**
        *  Add function info here
        */
        function ls($dir = null, $pattern = null, $caseSensitive = DEFAULT_FILE_LIST_CASE_SENSITIVE)
        {
            $entries = $this->_lister->ls($dir);
            $result  = array();

            if (!empty($pattern))
            {
                $pattern = "/" . $pattern . "/";
                
                foreach ($entries as $entry)
                {
                    if ((preg_match($pattern, $entry->getName()) && $caseSensitive) || (preg_match($pattern . "i", $entry->getName()) && !$caseSensitive))
                    {
                        array_push($result, $entry);
                    }
                }
            }
            else
            {
                $result = $entries;
            }

            return $this->sort($result);
        }

        /**
        *  Add function info here
        */
        function sort($entries, $sort = FILE_LIST_SORT_NAME, $order = FILE_LIST_SORT_ASC)
        {
            $unsortedFiles = array();
            $unsortedDirs  = array();
            $sortedDirs    = array();
            $sortedFiles   = array();

            foreach ($entries as $entry)
            {
                switch ($sort)
                {
                    case FILE_LIST_SORT_SIZE:
                        $key = $entry->getSize() . $entry->getName();
                        break;

                    case FILE_LIST_SORT_DATE:
                        $key = $entry->getTimeStamp() . $entry->getName();
                        break;

                    case FILE_LIST_SORT_NAME:

                    default:
                        $key = $entry->getName();
                }

                if ($entry->isDir())
                {
                    $unsortedDirs[$key] = $entry;
                }
                else
                {
                    $unsortedFiles[$key] = $entry;
                }
            }

            switch ($order)
            {
                case FILE_LIST_SORT_DESC:
                    krsort($unsortedDirs);
                    krsort($unsortedFiles);
                    break;

                case FILE_LIST_SORT_ASC:

                default:
                    ksort($unsortedDirs);
                    ksort($unsortedFiles);
            }


            foreach ($unsortedDirs as $dir)
            {
                array_push($sortedDirs, $dir);
            }

            foreach ($unsortedFiles as $file)
            {
                array_push($sortedFiles, $file);
            }

            return array_merge($sortedDirs, $sortedFiles);
        }
    }
?>
