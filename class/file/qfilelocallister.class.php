<?php

     include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qfilelister.class.php");
     include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qfilelistentry.class.php");

    /**
     * @brief Servicio para listar los archivos de un directorio local
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 13:58
     * @version 1.0
     * @ingroup file
     * @see qFileList
     */
     class qFileLocalLister extends qFileLister
     {
        /**
        *    Add function info here
        */
        function qFileLocalLister()
        {
            $this->qFileLister();
        }

        /**
        *  Add function info here
        */
        function ls($dir = null)
        {
            if (empty($dir))
            {
                $dir = "./";
            }

            if (!is_dir($dir))
            {
                return array();
            }

            $handler = opendir($dir);

            if (empty($handler))
            {
                return array();
            }

            $result = array();

            while (($file = readdir($handler)) !== false)
            {
                if ($file != "." && $file != "..")
                {
                    $f = new qFile($dir . $file);
                    array_push($result, new qFileListEntry($file, $f->getSize(), $f->getPermissions(), $f->getOwner(), $f->getGroup(), $f->getTimeStamp(), $f->isDir()));
                }
            }

            closedir($handler);
            return $result;
        }
     }
?>
