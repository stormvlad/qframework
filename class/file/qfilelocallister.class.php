<?php

     include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qfilelister.class.php");
     include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qfilelistentry.class.php");

    /**
     * Encapsulation of a class to manage files. It is basically a wrapper
     * to some of the php functions.
     * http://www.php.net/manual/en/ref.filesystem.php
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

            $handler = opendir($dir);

            if (empty($handler))
            {
                return false;
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
