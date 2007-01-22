<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/request/qrequest.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/request/qrequestparser.class.php");

    /**
     * @brief Representa una petición para clientes web
     *
     * Añade el soporte para manipulacion de subida de ficheros y cookies.
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 18:13
     * @version 1.0
     * @ingroup core request
     */
    class qWebRequest extends qRequest
    {
        var $_parser;
        
        function qWebRequest(&$parser)
        {
            $this->qRequest();
        
            if (isset($_SERVER["REQUEST_METHOD"]))
            {
                switch ($_SERVER["REQUEST_METHOD"])
                {
                    case "GET":
                        $this->setMethod(REQUEST_METHOD_GET);
                        break;
    
                    case "POST":
                        $this->setMethod(REQUEST_METHOD_POST);
                        break;
    
                    default:
                        $this->setMethod(REQUEST_METHOD_GET);
                }
            }
            else
            {
                // set the default method
                $this->setMethod(REQUEST_METHOD_GET);
            }
            
            $this->_parser = &$parser;
    
            // load parameters from GET/PATH_INFO/POST
            $this->loadParameters();
        }

        /**
         * Devuelve una lista de ficheros subidos.
         *
         * @param  name  <em>string</em> Nombre del fichero
         * @return array Vector asociativo con la información del fichero, si existe, sino null..
         */
        function getFile ($name)
        {
            if (isset($_FILES[$name]))
            {
                return $_FILES[$name];
            }
    
            return null;
        }
    
        /**
         * Indica si ha ocurrido un error en la subida de un fichero.
         *
         * @param name <em>string</em> Nombre del fichero
         * @return int One of the following error codes:
         *
         *             - <b>UPLOAD_ERR_OK</b>        (no error)
         *             - <b>UPLOAD_ERR_INI_SIZE</b>  (the uploaded file exceeds the
         *                                           upload_max_filesize directive
         *                                           in php.ini)
         *             - <b>UPLOAD_ERR_FORM_SIZE</b> (the uploaded file exceeds the
         *                                           MAX_FILE_SIZE directive that
         *                                           was specified in the HTML form)
         *             - <b>UPLOAD_ERR_PARTIAL</b>   (the uploaded file was only
         *                                           partially uploaded)
         *             - <b>UPLOAD_ERR_NO_FILE</b>   (no file was uploaded)
         */
        function getFileError ($name)
        {
            if (isset($_FILES[$name]))
            {
                return $_FILES[$name]["error"];
            }
    
            return $retval;
        }
    
        /**
         * Devuelve el nombre original del fichero en la máquina del cliente
         *
         * @param  name   <em>string</em> Nombre del fichero
         * @return string A file name, if the file exists, otherwise null.
         */
        function getFileName ($name)
        {
            if (isset($_FILES[$name]))
            {
    
                return $_FILES[$name]["name"];
    
            }
    
            return null;
        }
    
        /**
         * Devuelve una lista con los nombres de fichero
         *
         * @return array Un vector indexado con los nombres de fichero
         */
        function getFileNames ()
        {
            return array_keys($_FILES);
        }
    
        /**
         * Devuelve una lista con los ficheros subidos.
         *
         * Este vector contiene toda la información de los ficheros subidos.
         * Equivale a la variable $_FILES de PHP.
         *
         * @return array Vector unidimensional asociativo 
         */
        function getFiles ()
        {
            return $_FILES;
        }
    
        /**
         * Devuelve la ruta completa del nombre del fichero temporal guardado en el servidor.
         *
         * @param  name   <em>string</em> Nombre de fichero
         * @return string Ruta al fichero, si existe, sino null.
         */
        function getFilePath ($name)
        {
            if (isset($_FILES[$name]))
            {
                return $_FILES[$name]["tmp_name"];
            }
    
            return null;
        }
    
        /**
         * Devuelve el tamaño del fichero
         *
         * @param  name <em>string</em> Nombre del fichero
         * @return int  Tamaño del fichero en bytes, si existe, sino null.
         */
        function getFileSize ($name)
        {
            if (isset($_FILES[$name]))
            {
                return $_FILES[$name]["size"];
            }
    
            return null;
        }
    
        /**
         * Devuelve el tipo MIME del fichero
         *
         * El tipo MIME del fichero si el navegador proporciona esta información.
         * Un ejemplo podria ser "image/gif".
         *
         * @param  name   <em>string</em> Nombre del fichero
         * @return string Tipo de fichero, si existe, sino null.
         */
        function getFileType ($name)
        {
            if (isset($_FILES[$name]))
            {
                return $_FILES[$name]["type"];
            }
    
            return null;
        }
    
        /**
         * Indica si se ha subido un fichero con un nombre
         *
         * @param name <em>string</em> Nombre del fichero
         * @return bool true, si exite, sino false.
         */
        function hasFile ($name)
        {
            return isset($_FILES[$name]);
        }
    
        /**
         * Indica si existe un error en la subida de un fichero
         *
         * @param  name <em>string</em> Nombre del fichero
         * @return bool true, si existe un error, sino false
         */
        function hasFileError ($name)
        {
            if (isset($_FILES[$name]))
            {
                return ($_FILES[$name]["error"] != UPLOAD_ERR_OK);
            }
    
            return false;
        }
    
        /**
         * Indica si ha ocurrido un error en la subida de ficheros
         *
         * @return bool true, si ha ocurrido un error, sino false.
         */
        function hasFileErrors ()
        {
            foreach ($_FILES as $file)
            {
                if ($file["error"] != UPLOAD_ERR_OK)
                {
                    return true;
                }
            }
    
            return false;
        }
    
        /**
         * Indica si se ha subido algun fichero
         *
         * @return bool true, si existe algun fichero, sino false
         */
        function hasFiles ()
        {
            return (count($_FILES) > 0);
        }
        
        /**
         * Guarda un fichero subido durante la petición
         *
         * @param name     <em>string</em> Nombre del fichero en el formulario
         * @param file     <em>string</em> Ruta absoluta dónde queremos guardar el fichero
         *                                 Esto incluye el nuevo nombre del fichero
         * @param fileMode <em>int</em>    Permisos para el nuevo fichero
         * @param create   <em>bool</em>   Indica si debe de crearse el directorio antes de mover el fichero
         * @param dirMode  <em>int</em>    Permisos para el directorio creado
         * @return boolean true, si se guarda el fichero, sino false
         */
        function moveFile ($name, $file, $fileMode = 0666, $create = true, $dirMode = 0777)
        {
            if (isset($_FILES[$name]) && $_FILES[$name]["error"] == UPLOAD_ERR_OK && $_FILES[$name]["size"] > 0)
            {
                $directory = dirname($file);
    
                if (!is_readable($directory))
                {
                    $fmode = 0777;
    
                    if ($create && !@mkdir($directory, $dirMode, true))
                    {
                        trigger_error("Failed to create file upload directory '" . $directory . "'", E_USER_WARNING);
                        return false;
                    }
    
                    // chmod the directory since it doesn't seem to work on
                    // recursive paths
                    @chmod($directory, $dirMode);
    
                } 
                else if (!is_dir($directory))
                {
                    trigger_error("File upload path '" . $directory . "' exists, but is not a directory.", E_USER_WARNING);
                    return false;
                }
                else if (!is_writable($directory))
                {
                    trigger_error("File upload path '" . $directory . "' exists, but is not writable.", E_USER_WARNING);
                    return false;
                }
    
                if (@move_uploaded_file($_FILES[$name]["tmp_name"], $file))
                {
                    @chmod($file, $fileMode);
    
                    return true;
                }    
            }
    
            return false;
        }

        /**
         * Establece el analizador que se usara para procesar las peticiones
         *
         * @param $parser qRequestParser Analizador de la URL de petición HTTP GET
         * @private
         */
        function setRequestParser($parser)
        {
            if ($parser == null)
            {
                $parser = new qRawRequestParser();
            }
            elseif (!is_a($parser, qRequestParser))
            {
                trigger_error("This isn't a valid request parser.", E_USER_WARNING);
                return;
            }

            $this->_parser = $parser;
        }
    
        /**
         * Carga las variables del HTTP GET, POST y PATH_INFO en la lista de parametros.
         *
         * @private
         */
        function loadParameters ()
        {        
            $this->_parser->parse($this);
        }
    }

?>