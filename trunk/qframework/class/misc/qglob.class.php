<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * Alternative implementation of the glob() function, since the latter is only
     * available in php versions 4.3 or higher and many many hosts have not updated
     * yet.
     * Original glob function: http://www.php.net/manual/en/function.glob.php.
     * Original fnmatch function: http://www.php.net/manual/en/function.fnmatch.php.
     *
     * The class is capable of detecting the version of php and using the native (and probably
     * faster) version instead of the custom one.
     */
    class qGlob extends qObject
    {
        /**
         * This function checks wether we're running a version of php at least or newer than
         * 4.3. If its newer, then we will use the native version of glob otherwise we will use
         * our own version. The order of the parameters is <b>not</b> the same as the native version
         * of glob, but they will be converted. The <i>flags</i> parameter is not used when
         * using the custom version of glob.
         *
         * @param folder The folder where would like to search for files. This function is <b>not</b>
         * recursive.
         * @param pattern The shell pattern that will match the files we are searching for.
         * @param flags This parameter is only used when using the native version of glob. For possible
         * values of this parameter, please check the glob function page.
         * @return Returns an array of the files that match the given pattern in the given
         * folder or false if there was an error.
         * @static
         */
        function qGlob( $folder = ".", $pattern = "*", $flags = 0 )
        {
            if( function_exists("glob")) {
                // call the native glob function with parameters
                $fileName = $folder;
                if( substr($fileName, -1) != "/" )
                    $fileName .= "/";
                $fileName .= $pattern;

                return glob( $fileName, $flags );
            }
            else {
                // call our own implementation
                return Glob::myGlob( $folder, $pattern );
            }
        }

        /**
         * Front-end function that does the same as the glob function above but this time with the fnmnatch version.
         * Checks the php version and if it is at least or greater than 4.3, then we will use
         * the native and faster version of fnmatch or otherwise we will fall back to using our
         * own custom version.
         *
         * @param pattern The shell pattern.
         * @param file The filename we would like to match.
         * @return True if the file matches the pattern or false if not.
         * @static
         */
        function fnmatch( $pattern, $file )
        {
            if( function_exists("fnmatch")) {
                // use the native fnmatch version
                return fnmatch( $pattern, $file );
            }
            else {
                // otherwise, use our own
                return Glob::myFnmatch( $pattern, $file );
            }
        }

        /**
         * Our own implementation of the glob function for those sites which run a version
         * of php lower than 4.3. For more information on the function glob:
         * http://www.php.net/manual/en/function.glob.php
         *
         * Returns an array with all the files and subdirs that match the given shell expression.
         * @param folder Where to start searching.
         * @param pattern A shell expression including wildcards '*' and '?' defining which
         * files will match and which will not.
         * @return An array with the matching files and false if error.
         */
        function myGlob( $folder = ".", $pattern = "*" )
        {
            // Note that !== did not exist until 4.0.0-RC2
            if ($handle = opendir( $folder )) {
                $files = Array();

                while (false !== ($file = readdir($handle))) {
                    if( $file !="." && $file != ".." )    // ignore '.' and '..'
                        if( Glob::fnmatch($pattern,$file)) {
                            if( $folder[strlen($folder)-1] != "/")
                                $filePath = $folder."/".$file;
                            else
                                $filePath = $folder.$file;
                            array_push( $files, $filePath );

                        }
                }

                closedir($handle);
            }
            else
                return false;

            return $files;
        }

        /**
         * Our own equivalent of fnmatch that is only available in php 4.3.x.
         *
         * Based on a user-contributed code for the fnmatch php function here:
         * http://www.php.net/manual/en/function.fnmatch.php
         */
        function myFnmatch( $pattern, $file )
        {


            for($i=0; $i<strlen($pattern); $i++) {
                if($pattern[$i] == "*") {
                    for($c=$i; $c<max(strlen($pattern), strlen($file)); $c++) {
                        if(Glob::myFnmatch(substr($pattern, $i+1), substr($file, $c))) {
                            return true;
                        }
                    }
                    return false;
                }
                if($pattern[$i] == "[") {
                    $letter_set = array();
                    for($c=$i+1; $c<strlen($pattern); $c++) {
                        if($pattern[$c] != "]") {
                            array_push($letter_set, $pattern[$c]);
                        }
                        else
                            break;
                    }
                    foreach ($letter_set as $letter) {
                        if(Glob::myFnmatch($letter.substr($pattern, $c+1), substr($file, $i))) {
                            return true;
                        }
                    }
                    return false;
               }
               if($pattern[$i] == "?") {
                      continue;
              }
              if($pattern[$i] != $file[$i]) {
                  return false;
              }
         }
         return true;
       }
    }
?>
