<?php

     include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
     include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/misc/qutils.class.php");
     include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qfilelist.class.php");
     include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qfilelocallister.class.php");

     define("DEFAULT_FILE_DIRECTORY_UMASK", 0777);

    /**
     * Encapsulation of a class to manage files. It is basically a wrapper
     * to some of the php functions.
     * http://www.php.net/manual/en/ref.filesystem.php
     */
     class qFile extends qObject
     {
        var $_fileName;
        var $_handler;

        /**
        *    Add function info here
        */
        function qFile($fileName)
        {
            $this->qObject();

            $this->_fileName = $fileName;
            $this->_handler  = null;
        }

        /**
         * Opens the file in the specified mode
         * http://www.php.net/manual/en/function.fopen.php
         * Mode by default is 'r' (read only)
         * Returns 'false' if operation failed
         */
        function open($mode = "r")
        {
            $this->_handler = fopen($this->_fileName, $mode);
            return $this->_handler;
        }

        /**
         * Closes the stream
         */
        function close()
        {
            fclose($this->_handler);
        }

        /**
         * Read the whole file and put it into an array, where every position
         * of the array is a line of the file (new-line characters not included)
         */
        function readFile($file = null)
        {
            if (empty($file))
            {
                $file = $this->_fileName;
            }

            $contents = Array();
            $contents = file($file);

            for ($i = 0; $i < count($contents); $i++)
            {
                $contents[$i] = rtrim($contents[$i], "\r\n");
            }

            return $contents;
        }

        /**
         * Reads a line from the file
         *
         * @param size Amount of bytes we'd like to read from the file. It is set
         * to 4096 by default.
         */
        function read($size = 4096)
        {
            return fread($this->_handler, $size);
        }

        /**
         * Returns true if we reached the end of the file
         */
        function eof()
        {
            return feof($this->_handler);
        }

        /**
         * Writes data to disk
         */
        function write($data)
        {
            return fwrite($this->_handler, $data);
        }

        /**
        *    Add function info here
        */
        function truncate($length = 0)
        {
            return ftruncate($this->_handler, $length);
        }

        /**
         * Writes an array of text lines to the file.
         *
         * @param lines The array with the text.
         * @return Returns true if successful or false otherwise.
         */
        function writeLines($lines)
        {
            $this->truncate();

            foreach ($lines as $line)
            {
                if (!$this->write($line, strlen($line)))
                {
                    return false;
                }
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
        function exists($file = null)
        {
            if (empty($file) && !empty($this->_fileName))
            {
                $file = $this->_fileName;
            }

            return file_exists($file);
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
        function isDir($file = null)
        {
            if (empty($file) && !empty($this->_fileName))
            {
                $file = $this->_fileName;
            }

            return is_dir($file);
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
        function isWritable($file = null)
        {
            if (empty($file) && !empty($this->_fileName))
            {
                $file = $this->_fileName;
            }

            return is_writable($file);
        }

        /**
        *    Add function info here
        */
        function isReadable($file = null)
        {
            if (empty($file) && !empty($this->_fileName))
            {
                $file = $this->_fileName;
            }

            return is_readable($file);
        }

        /**
        *    Add function info here
        */
        function _rmDir($file)
        {
            $l       = new qFileList(new qFileLocalLister());
            $entries = $l->ls($file);
            $result  = true;

            foreach ($entries as $entry)
            {
                $result &= qFile::rm($file . $entry->getName());
            }

            $result &= rmdir($file);
            return $result;
        }

        /**
        *    Add function info here
        */
        function rm($file = null)
        {
            if (empty($file) && !empty($this->_fileName))
            {
                $file = $this->_fileName;
            }

            if (qFile::isDir($file))
            {
                $result = qFile::_rmDir(qUtils::addDirSep($file));
            }
            else
            {
                $result = unlink($file);
            }

            return $result;
        }

        /**
        *    Add function info here
        */
        function _cpDir($source, $dest)
        {
            if (!qFile::exists($dest))
            {
                if (!qFile::mkdir($dest))
                {
                    return false;
                }
            }

            $l       = new qFileList(new qFileLocalLister());
            $entries = $l->ls($source);
            $result  = true;

            foreach ($entries as $entry)
            {
                $result &= qFile::cp($source . $entry->getName(), $dest . $entry->getName());
            }

            return $result;
        }

        /**
        *    Add function info here
        */
        function cp($source, $dest = null)
        {
            if (empty($dest) && !empty($this->_fileName))
            {
                $dest   = $source;
                $source = $this->_fileName;
            }

            if (qFile::isDir($source))
            {
                $result = qFile::_cpDir(qUtils::addDirSep($source), qUtils::addDirSep($dest));
            }
            else
            {
                $result = copy($source, $dest);
            }

            return $result;
        }

        /**
         * Creates a new folder
         *
         * @static
         * @param dirName The name of the new folder
         * @return Returns true if no problem or false otherwise.
         */
        function mkdir($dirName, $mode = DEFAULT_FILE_DIRECTORY_UMASK)
        {
            return mkdir($dirName, $mode);
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
        function getSize($file = null)
        {
            if (empty($file) && !empty($this->_fileName))
            {
                $file = $this->_fileName;
            }

            return filesize($file);
        }

        /**
        * Add function info here
        */
        function getNormalizedSize($decimals = null, $file = null)
        {
            $size  = $this->getSize($file);
            $sizes = array("B", "KB", "MB", "GB", "TB", "PB", "EB");
            $ext   = $sizes[0];
            $count = count($sizes);

            for ($i = 1; ($i < $count) && ($size >= 1024); $i++)
            {
                $size = $size / 1024;
                $ext  = $sizes[$i];
            }

            return round($size, $decimals). " " . $ext;
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
        function rename($inFile, $outFile = null)
        {
            if (empty($outFile) && !empty($this->_fileName))
            {
                $outFile = $inFile;
                $inFile  = $this->_fileName;
            }

            return rename($inFile, $outFile);
        }

        /**
        *    Add function info here
        */
        function getExtension($file = null)
        {
            if (empty($file) && !empty($this->_fileName))
            {
                $file = $this->_fileName;
            }

            if (($pos = strrpos($file, ".")) !== false)
            {
                return substr($file, $pos + 1, strlen($file) - $pos - 1);
            }
            else
            {
                return false;
            }
        }

        /**
        *    Add function info here
        */
        function getPermissions($file = null)
        {
            if (empty($file) && !empty($this->_fileName))
            {
                $file = $this->_fileName;
            }

            $permissions = fileperms($file);
            $perms       = "";

            /*if (($permissions & 0x4000) === 0x4000)
            {
                $perms = "d";
            }
            else
            {
                $perms = "-";
            }*/

            $bin = substr(decbin($permissions), -9) ;
            $a   = explode(".", substr(chunk_split($bin, 1, "."), 0, 17));
            $i   = 0;

            foreach ($a as $item)
            {
                if ($i % 3 == 0)
                {
                    $char = "r";
                }
                elseif ($i % 3 == 1)
                {
                    $char = "w";
                }
                else
                {
                    $char = "x";
                }

                if ($item == "1")
                {
                    $perms .= $char;
                }
                else
                {
                    $perms .= "-";
                }

                $i++;
            }

            return $perms;
        }

        /**
        *    Add function info here
        */
        function getUid($file = null)
        {
            if (empty($file) && !empty($this->_fileName))
            {
                $file = $this->_fileName;
            }

            return fileowner($file);
        }

        /**
        *    Add function info here
        */
        function getOwner($file = null)
        {
            $result = posix_getpwuid(qFile::getUid($file));

            return $result["name"];
        }

        /**
        *    Add function info here
        */
        function getGid($file = null)
        {
            if (empty($file) && !empty($this->_fileName))
            {
                $file = $this->_fileName;
            }

            return filegroup($file);
        }

        /**
        *    Add function info here
        */
        function getGroup($file = null)
        {
            $result = posix_getgrgid(qFile::getGid($file));

            return $result["name"];
        }

        /**
        *    Add function info here
        */
        function getTimeStamp($file = null)
        {
            if (empty($file) && !empty($this->_fileName))
            {
                $file = $this->_fileName;
            }

            return filemtime($file);
        }

        /**
         * Deletes a file
         */
        function delete($file = NULL)
        {
            if (empty($file) && !empty($this->_fileName))
            {
                $file = $this->_fileName;
            }

            return unlink($file);
        }
    }
?>