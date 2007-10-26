<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php" );

    /**
     * @brief Representa a un tipo MIME
     *
     * Mas información: http://www.iana.org/assignments/media-types/
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 16:28
     * @version 1.0
     * @ingroup data
     */
    class qMimeTypes extends qObject
    {
        var $_mimeTypes = array(
            "ai"      => "application/postscript",
            "aif"     => "audio/x-aiff",
            "aifc"    => "audio/x-aiff",
            "aiff"    => "audio/x-aiff",
            "asc"     => "text/plain",
            "au"      => "audio/basic",
            "avi"     => "video/x-msvideo",
            "bcpio"   => "application/x-bcpio",
            "bin"     => "application/octet-stream",
            "bmp"     => "image/bmp",
            "cdf"     => "application/x-netcdf",
            "class"   => "application/octet-stream",
            "cpio"    => "application/x-cpio",
            "cpt"     => "application/mac-compactpro",
            "csh"     => "application/x-csh",
            "css"     => "text/css",
            "dcr"     => "application/x-director",
            "dir"     => "application/x-director",
            "djv"     => "image/vnd.djvu",
            "djvu"    => "image/vnd.djvu",
            "dll"     => "application/octet-stream",
            "dms"     => "application/octet-stream",
            "doc"     => "application/msword",
            "dvi"     => "application/x-dvi",
            "dxr"     => "application/x-director",
            "eps"     => "application/postscript",
            "etx"     => "text/x-setext",
            "exe"     => "application/octet-stream",
            "ez"      => "application/andrew-inset",
            "gif"     => "image/gif",
            "gtar"    => "application/x-gtar",
            "hdf"     => "application/x-hdf",
            "hqx"     => "application/mac-binhex40",
            "htm"     => "text/html",
            "html"    => "text/html",
            "ice"     => "x-conference-xcooltalk",
            "ief"     => "image/ief",
            "iges"    => "model/iges",
            "igs"     => "model/iges",
            "jpe"     => "image/jpeg",
            "jpeg"    => "image/jpeg",
            "jpg"     => "image/jpeg",
            "js"      => "application/x-javascript",
            "kar"     => "audio/midi",
            "kml"     => "application/vnd.google-earth.kml+xml",
            "latex"   => "application/x-latex",
            "lha"     => "application/octet-stream",
            "lzh"     => "application/octet-stream",
            "m3u"     => "audio/x-mpegurl",
            "man"     => "application/x-troff-man",
            "me"      => "application/x-troff-me",
            "mesh"    => "model/mesh",
            "mid"     => "audio/midi",
            "midi"    => "audio/midi",
            "mov"     => "video/quicktime",
            "movie"   => "video/x-sgi-movie",
            "mp2"     => "audio/mpeg",
            "mp3"     => "audio/mpeg",
            "mpe"     => "video/mpeg",
            "mpeg"    => "video/mpeg",
            "mpg"     => "video/mpeg",
            "mpga"    => "audio/mpeg",
            "ms"      => "application/x-troff-ms",
            "msh"     => "model/mesh",
            "mxu"     => "video/vnd.mpegurl",
            "nc"      => "application/x-netcdf",
            "oda"     => "application/oda",
            "pbm"     => "image/x-portable-bitmap",
            "pdb"     => "chemical/x-pdb",
            "pdf"     => "application/pdf",
            "pgm"     => "image/x-portable-graymap",
            "pgn"     => "application/x-chess-pgn",
            "png"     => "image/png",
            "pnm"     => "image/x-portable-anymap",
            "ppm"     => "image/x-portable-pixmap",
            "ps"      => "application/postscript",
            "qt"      => "video/quicktime",
            "ra"      => "audio/x-realaudio",
            "ram"     => "audio/x-pn-realaudio",
            "ras"     => "image/x-cmu-raster",
            "rgb"     => "image/x-rgb",
            "rm"      => "audio/x-pn-realaudio",
            "roff"    => "application/x-troff",
            "rpm"     => "audio/x-pn-realaudio-plugin",
            "rtf"     => "text/rtf",
            "rtx"     => "text/richtext",
            "sgm"     => "text/sgml",
            "sgml"    => "text/sgml",
            "sh"      => "application/x-sh",
            "shar"    => "application/x-shar",
            "silo"    => "model/mesh",
            "sit"     => "application/x-stuffit",
            "skd"     => "application/x-koan",
            "skm"     => "application/x-koan",
            "skp"     => "application/x-koan",
            "skt"     => "application/x-koan",
            "smi"     => "application/smil",
            "smil"    => "application/smil",
            "snd"     => "audio/basic",
            "so"      => "application/octet-stream",
            "spl"     => "application/x-futuresplash",
            "src"     => "application/x-wais-source",
            "sv4cpio" => "application/x-sv4cpio",
            "sv4crc"  => "application/x-sv4crc",
            "swf"     => "application/x-shockwave-flash",
            "t"       => "application/x-troff",
            "tar"     => "application/x-tar",
            "tcl"     => "application/x-tcl",
            "tex"     => "application/x-tex",
            "texi"    => "application/x-texinfo",
            "texinfo" => "application/x-texinfo",
            "tif"     => "image/tif",
            "tiff"    => "image/tiff",
            "tr"      => "application/x-troff",
            "tsv"     => "text/tab-seperated-values",
            "txt"     => "text/plain",
            "ustar"   => "application/x-ustar",
            "vcd"     => "application/x-cdlink",
            "vcs"     => "text/x-vCalendar",
            "vrml"    => "model/vrml",
            "wav"     => "audio/x-wav",
            "wbmp"    => "image/vnd.wap.wbmp",
            "wbxml"   => "application/vnd.wap.wbxml",
            "wml"     => "text/vnd.wap.wml",
            "wmlc"    => "application/vnd.wap.wmlc",
            "wmls"    => "text/vnd.wap.wmlscript",
            "wmlsc"   => "application/vnd.wap.wmlscriptc",
            "wrl"     => "model/vrml",
            "xbm"     => "image/x-xbitmap",
            "xht"     => "application/xhtml+xml",
            "xhtml"   => "application/xhtml+xml",
            "xml"     => "text/xml",
            "xpm"     => "image/x-xpixmap",
            "xsl"     => "text/xml",
            "xwd"     => "image/x-windowdump",
            "xyz"     => "chemical/x-xyz",
            "zip"     => "application/zip"
        );

        var $_defaultType;

        /**
        * Constructor
        */
        function qMimeTypes()
        {
            $this->qObject();
            $this->_defaultType = "application/octet-stream";
        }

        /**
        * Se le puede pasar el nombre del archivo o la extensión directamente
        */
        function getType($file, $withSubtype = true)
        {
            if (($pos = strrpos($file, ".")) === false)
            {
                $extension = $file;
            }
            else
            {
                $extension = substr($file, $pos + 1, strlen($file) - $pos - 1);
            }

            if (empty($this->_mimeTypes[$extension]))
            {
                $type = $this->_defaultType;
            }
            else
            {
                $type = $this->_mimeTypes[$extension];
            }

            if (empty($withSubtype))
            {
                $parts = explode("/", $type);

                if (count($parts) > 0)
                {
                    $type = $parts[0];
                }
            }
            
            return $type;
        }
    }

?>