<?php

     include_once("framework/class/object/object.class.php" );

     define( "FILE_DEFAULT_DIRECTORY_CREATION_MODE", 0700 );

    /**
     * Encapsulation of a class to manage files. It is basically a wrapper
     * to some of the php functions.
     * http://www.php.net/manual/en/ref.filesystem.php
     */
     class File extends Object {

        var $_fileName;
        var $_handler;
        var $_mode;

        function File( $fileName )
        {
            $this->Object();

            $this->_fileName = $fileName;
        }

        /**
         * Opens the file in the specified mode
         * http://www.php.net/manual/en/function.fopen.php
         * Mode by default is 'r' (read only)
         * Returns 'false' if operation failed
         */
        function open( $mode = "r" )
        {
            $this->_handler = fopen( $this->_fileName, $mode );

            $this->_mode = $mode;

            return $this->_handler;
        }

        /**
         * Closes the stream
         */
        function close()
        {
            fclose( $this->_handler );
        }

        /**
         * Read the whole file and put it into an array, where every position
         * of the array is a line of the file (new-line characters not included)
         */
        function readFile()
        {
            $contents = Array();

            $contents = file( $this->_fileName );

            for( $i = 0; $i < count( $contents ); $i++ )
                $contents[$i] = trim( $contents[$i] );

            return $contents;
        }

        /**
         * Reads a line from the file
         *
         * @param size Amount of bytes we'd like to read from the file. It is set
         * to 4096 by default.
         */
        function read( $size = 4096 )
        {
            return( fread( $this->_handler, $size ));
        }

        /**
         * Returns true if we reached the end of the file
         */
        function eof()
        {
            return feof( $this->_handler );
        }

        /**
         * Writes data to disk
         */
        function write( $data )
        {
            return fwrite( $this->_handler, $data );
        }

        function truncate( $length = 0 )
        {
            return ftruncate( $this->_handler, $length );
        }

        /**
         * Writes an array of text lines to the file.
         *
         * @param lines The array with the text.
         * @return Returns true if successful or false otherwise.
         */
        function writeLines( $lines )
        {
            // truncate the file to remove the old contents
            $this->truncate();

            foreach( $lines as $line ) {
                //print("read: \"".htmlentities($line)."\"<br/>");
                if( !$this->write( $line, strlen($line))) {
                    return false;
                }
                /*else
                    print("written: \"".htmlentities($line)."\"<br/>");*/
            }

            return true;
        }

        /**
         * Returns true wether the file is a directory. See
         * http://fi.php.net/manual/en/function.is-dir.php for more details.
         *
         * @param file The filename we're trying to check. If omitted, the current file
         * will be used (note that this function can be used as static as long as the
         * file parameter is provided)
         * @return Returns true if the file is a directory.
         */
        function isDir( $file = null )
        {
            if( $file == null )
                $file = $this->_fileName;

            return is_dir( $file );
        }

        /**
         * Returns true if the file is writable by the current user.
         * See http://fi.php.net/manual/en/function.is-writable.php for more details.
         *
         * @param file The filename we're trying to check. If omitted, the current file
         * will be used (note that this function can be used as static as long as the
         * file parameter is provided)
         * @return Returns true if the file is writable, or false otherwise.
         */
        function isWritable( $file = null )
        {
            if( $file == null )
                $file = $this->_fileName;

            return is_writable( $file );
        }

        function isReadable( $file = null )
        {
            if( $file == null )
                $file = $this->_fileName;

            return is_readable( $file );
        }

        function delete( $file = null )
        {
            if( $file == null )
                $file = $this->_fileName;

            if( File::isDir( $file ))
                $result = rmdir( $file );
            else
                $result = unlink( $file );

            return $result;
        }

        /**
         * Creates a new folder
         *
         * @static
         * @param dirName The name of the new folder
         * @return Returns true if no problem or false otherwise.
         */
        function createDir( $dirName, $mode = FILE_DEFAULT_DIRECTORY_CREATION_MODE )
        {
            return mkdir( $dirName, $mode );
        }

        /**
         * returns a temporary filename in a pseudo-random manner
         *
         */
        function getTempName()
        {
            return md5(microtime());
        }

        /**
         * Returns the size of the file.
         *
         * @param string fileName An optional parameter specifying the name of the file. If omitted,
         * we will use the file that we have currently opened. Please note that this function can
         * be used as static if a file name is specified.
         * @return An integer specifying the size of the file.
         */
        function getSize( $fileName = null )
        {
            if( $fileName == null )
                $fileName = $this->_fileName;

            $size = filesize( $fileName );
            if( !$size )
                return -1;
            else
                return $size;
        }

        /**
         * renames a file
         *
         * http://www.php.net/manual/en/function.rename.php
         *
         * This function can be used as static if inFile and outFile are both not
         * empty. if outFile is empty, then the internal file of the current object
         * will be used as the input file and the first parameter of this method
         * will become the destination file name.
         *
         * @param inFile Original file
         * @param outFile Destination file.
         * @return Returns true if file was renamed ok or false otherwise.
         */
        function rename( $inFile, $outFile = null )
        {
            // check how many parameters we have
            if( $outFile == null ) {
                $outFile = $inFile;
                $inFile  = $this->_fileName;
            }

            // and rename everything
            return rename( $inFile, $outFile );
        }

        function getExtension($fileName)
        {
            if (($pos = strrpos($fileName, ".")) !== false)
            {
                return substr($fileName, $pos + 1, strlen($fileName) - $pos - 1);
            }
            else
            {
                return false;
            }
        }
     }
?>
