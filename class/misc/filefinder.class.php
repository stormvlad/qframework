<?php

    include_once("framework/class/object/object.class.php" );
    include_once("framework/class/file/file.class.php" );

    /**
     * This class helps to find specific commands. It takes an array of paths
     * and a command, and will return the full path to where the command can be
     * found, out of the ones we used as a parameter.
     */
    class FileFinder extends Object
    {
        var $_folderArray;

        function FileFinder( $folderArray = Array())
        {
            $this->Object();

            $this->_folderArray = $folderArray;
        }

        /**
         * Returns the full path the file $file can be found. If the $folder parameter
         * is set to 'null', then we will use the array of folders that was used as a
         * parameter in the constructor, or else, this method can be used as static.
         * This method will only return the first coincidence with the file.
         *
         * @param $file The file we're looking for
         * @param folder An array of folders. If it is set to 'null', then this method
         * cannot be used as static and we will use the array of folders passed as a parameter
         * in the constructor.
         * @return Returns empty string if the file was not found in any of the folders or
         * the full path to the file if it was.
         */
        function find( $file, $folders = null )
        {
            if( $folders == null )
                $folders = $this->_folderArray;

            $found = false;
            $i = 0;
            while( !$found && $i < count($folders)) {
                // get the current folder
                $currentFolder = $folders[$i];
                // see if the file's there
                $fullPath = $currentFolder.$file;
                if( File::isReadable( $fullPath ))
                    $found = true;
                else
                    $i++;
            }

            if( $found )
                return $fullPath;
            else
                return "";
        }
    }
?>