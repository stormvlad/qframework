<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qproperties.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/locale/qlocalefilestorage.class.php");
    require_once 'Date/Calc.php';

    define("DEFAULT_LOCALE_CODE", "es_ES");
    define("DEFAULT_LOCALE_PATH", APP_ROOT_PATH . "locale/");

    /**
     * @defgroup i18n Internacionalización
     *
     * qFramework proporciona un método estándard para hacer que las aplicaciones sean internacionalizables y
     * localizables, esto se puede llevar a cabo con este grupo de clases.
     *
     * Todas las cadenas o frases mostradas por la aplicación susceptibles de traducirse deberan ser
     * estableciadas con un identificador único ya sea con un idioma base de partida, o una abreviación
     * o combinación que sea fácil de relacionar con su contenido.
     *
     * Además se añadira en todas las cadenas una simple función de internacionalización de qLocale
     * que se puede usar directamente, por ejemplo, des de una plantilla de contenido.
     *
     * Para mostrar el idioma concreto qLocale usara un diccionario de traducción que relacionará
     * el identificador con la palabra/frase traducida.
     *
     * Para llevar a cabo la localalización completa de la aplicación deberemos usar una serie de funciones y classes
     * adicionales que nos daran soporte para mostrar correctamte cadenas con fechas, horas, numeros y monedas.
     *
     */

    /**
     * @brief Interfície de internacionalización
     *
     * Esta clase nos da soporte para internacionalizar nuestras aplicaciones
     * conocido también como soporte de lenguage nativo (NLS).
     *
     * qLocale puede verse como un diccionario de traducción para varios idiomas,
     * en el qual le passamos identificadores o lenguage inicial y nos devuelve
     * la traducción según el idioma deseado.
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 17:33
     * @version 1.0
     * @ingroup i18n
     */
    class qLocale extends qObject
    {
        var $_storage;
        var $_messages;

        /**
         * @brief Constructor
         */
        function qLocale(&$storage)
        {
            $this->qObject();

            $this->_storage   = &$storage;
            $this->_messages  = new qProperties();

            $this->load();
        }

        /**
         * @brief Devuelve la única instancia de qLocale
         *
         * @note Basado en el patrón Singleton. El objectivo de este método es asegurar que exista sólo una instancia de esta clase y proveer de un punto global de accesso a ella.
         * @return qLocale
         */
        function &getInstance()
        {
            static $localeInstance;

            if (!isset($localeInstance))
            {
                $localeInstance = new qLocale(new qLocaleFileStorage(DEFAULT_LOCALE_FILE_STORAGE));
            }

            return $localeInstance;
        }

        /**
         * @brief Devuelve el código de pais
         *
         * Recuperamos el código de pais de 2 carácteres definido según el código de Localización.
         *
         * @return string
         */
        function getCountryId()
        {
            return substr($this->getLocaleCode(), 3, 2);
        }

        /**
         * @brief Devuelve el código de lenguaje
         *
         * Recuperamos el código del lenguaje de 2 carácteres definido según el código de Localización.
         *
         * @return string
         */
        function getLanguageId()
        {
            return substr($this->getLocaleCode(), 0, 2);
        }

        /**
         * @brief Devuelve el código de localización
         *
         * El código define el pais y lenguaje usado. Por ejemplo: es_ES, ca_ES, en_UK, ...
         *
         * @return string
         */
        function getLocaleCode()
        {
            return $this->getValue("__locale_code__");
        }

        /**
         * @brief Devuelve la descripción del idioma
         *
         * @return string
         */
        function getDescription()
        {
            return $this->getValue("__description__");
        }

        /**
         * @brief Devuelve el identificador del juego de carácteres usado en esta localización
         *
         * @return string
         */
        function getCharset()
        {
            return $this->getValue("__charset__");
        }

        /**
         * @brief Devuelve la dirección de escritura en este idioma
         *
         * Valores possibles: <code>ltr</code> (de izquierda a derecha), <code>rtl</code> (de derecha a izquierda)
         *
         * @return string
         */
        function getDirection()
        {
            return $this->getValue("__direction__");
        }

        /**
         * @brief Devuelve el separador de decimales
         *
         * Recuperamos el carácter usado para separar en los numeros con decimales,
         * la parte entera de la parte decimal.
         *
         * @return string
         */
        function getDecimalSymbol()
        {
            return $this->getValue("__decimal_symbol__");
        }

        /**
         * @brief Devuelve el separador de millares
         *
         * Recuperamos el carácter usado para separar los grupos de 3 cifras.
         *
         * @return string
         */
        function getThousandsSeparator()
        {
            return $this->getValue("__thousands_separator__");
        }

        /**
         * @brief Devuelve el símbolo de moneda
         *
         * Recuperamos el carácter usado para expresar cántidades monetárias en esta localización
         * Por ejemplo: <code>$</code>, <code>?</code>
         *
         * @return string
         * @note Este símbolo se usara cuando la salida sea HTML
         * @see formatCurrency
         */
        function getCurrencySymbol()
        {
            return $this->getValue("__currency_symbol__");
        }

        /**
         * @brief Devuelve el nombre del símbolo de moneda
         *
         * Recuperamos el nombre de la moneda en esta localización
         * Por ejemplo: <code>dolar</code>, <code>euro</code>
         *
         * @return string
         * @note Este símbolo se usara cuando la salida no sea HTML
         * @see formatCurrency
         */
        function getCurrencySymbol2()
        {
            return $this->getValue("__currency_symbol2__");
        }

        /**
         * @brief Devuelve la posición del símbolo de moneda
         *
         * Valores possibles: <code>L</code> (en la izquierda del número), <code>R</code> (a la derecha)
         *
         * @return string
         */
        function getCurrencySymbolPosition()
        {
            return $this->getValue("__currency_symbol_position__");
        }

        /**
         * @brief Devuelve el número de decimales usado para expresar cantidades monetárias
         *
         * @return integer
         */
        function getCurrencyDecimals()
        {
            return $this->getValue("__currency_decimals__");
        }

        /**
         * @brief Devuelve el formato por defecto para expresar la hora
         *
         * @return string
         */
        function getTimeFormat()
        {
            return $this->getValue("__time_format__");
        }

        /**
         * @brief Devuelve el formato por defecto para expresar una fecha
         *
         * @return string
         */
        function getDateFormat()
        {
            return $this->getValue("__date_format__");
        }

        /**
         * @brief Devuelve el formato corto para expresar una fecha
         *
         * @return string
         */
        function getDateFormatShort()
        {
            return $this->getValue("__date_format_short__");
        }

        /**
         * @brief Devuelve el formato corto para expresar una fecha
         *
         * @return string
         */
        function getDateTimeFormat()
        {
            return $this->getValue("__date_time_format__");
        }

        /**
         * @brief Devuelve el primer dia de la semana.
         *
         * Valores possibles: 0 (domingo), 1 (lunes), 2 (martes), 3 (miércoles), 4 (jueves), 5 (viernes), 6 (sábado)
         *
         * @return integer
         */
        function getFirstDayOfWeek()
        {
            return $this->getValue("__first_day_of_week__");
        }

        /**
         * @brief Devuelve el formato de papel usado por defecto en la localización
         *
         * Por ejemplo: A4, letter, ...
         *
         * @return string
         */
        function getPaperFormat()
        {
            return $this->getValue("__paper_format__");
        }

        /**
         * @brief Devuelve un array con todos los nombres de los dias de la semana
         *
         * @return array
         */
        function getDaysOfWeek()
        {
            return $this->getValue("_days");
        }

        /**
         * @brief Devuelve un array con todos los nombres cortos de los dias de la semana
         *
         * @return array
         */
        function getDaysOfWeekShort()
        {
            return $this->getValue("_days_short");
        }

        /**
         * @brief Devuelve un array con todos los meses
         *
         * @return array
         */
        function getMonths()
        {
            return $this->getValue("_months");
        }

        /**
         * @brief Devuelve un array con todos los nombres cortos de los meses
         *
         * @return array
         */
        function getMonthsShort()
        {
            return $this->getValue("_months_short");
        }

        /**
         * @brief Establece el código de localización
         *
         * El código define el pais y lenguaje usado. Por ejemplo: es_ES, ca_ES, en_UK, ...
         *
         * @param code <code>string</code> Código de la localización
         */
        function setCode($code)
        {
            $this->setValue("__locale_code__", $code);
        }

        /**
         * @brief Establece el nombre de la localización
         *
         * Nombre descriptivo con el nombre del pais y lenguaje usado. Por ejemplo: English (United Kingdom), Idioma español (España)
         *
         * @param description <code>string</code> Nombre de la localización
         */
        function setDescription($description)
        {
            $this->setValue("__description__", $description);
        }

        /**
         * @brief Establece el identificador del juego de carácteres usado en esta localización
         *
         * @param charset <code>string</code> Juego de carácteres
         */
        function setCharset($charset)
        {
            $this->setValue("__charset__", $charset);
        }

        /**
         * @brief Establece la dirección de escritura en este idioma
         *
         * @param $direction <em>string</em> Valores possibles: <code>ltr</code> (de izquierda a derecha), <code>rtl</code> (de derecha a izquierda)
         */
        function setDirection($direction)
        {
            $this->setValue("__direction__", $direction);
        }

        /**
         * @brief Establece el separador de decimales
         *
         * @param $symbol <em>string</em> Carácter usado para separar la parte entera de los decimales en los números
         */
        function setDecimalSymbol($symbol)
        {
            $this->setValue("__decimal_symbol__", $symbol);
        }

        /**
         * @brief Establece el separador de millares
         *
         * @param $separator <em>string</em> Carácter usado para separar los grupos de 3 cifras.
         */
        function setThousandsSeparator($separator)
        {
            $this->setValue("__thousands_separator__", $separator);
        }

        /**
         * @brief Establece el símbolo de moneda
         *
         * @param $symbol <em>string</em> Carácter usado para expresar cántidades monetárias en esta localización
         * @note Este símbolo se usa cuando la salida és HTML
         * @see formatCurrency
         */
        function setCurrencySymbol($symbol)
        {
            $this->setValue("__currency_symbol__", $symbol);
        }

        /**
         * @brief Establece el nombre del símbolo de moneda
         *
         * @param $symbol <em>string</em> Nombre de la moneda en esta localización
         * @note Este símbolo se usa cuando la salida no és HTML
         * @see formatCurrency
         */
        function setCurrencySymbol2($symbol)
        {
            $this->setValue("__currency_symbol2__", $symbol);
        }

        /**
         * @brief Establece la posición del símbolo de moneda
         *
         * @param $position <em>string</em> Valores possibles: <code>L</code> (en la izquierda del número), <code>R</code> (a la derecha)
         */
        function setCurrencySymbolPosition($position)
        {
            $this->setValue("__currency_symbol_position__", $position);
        }

        /**
         * @brief Establece el número de decimales usado para expresar cantidades monetárias
         *
         * @param $num <em>integer</em> Número de cifras decimales
         */
        function setCurrencyDecimals($num)
        {
            $this->setValue("__currency_decimals__", $num);
        }

        /**
         * @brief Establece el formato para expresar la hora
         *
         * @param $format <em>string</em> Cadena de formato
         * @see format
         */
        function setTimeFormat($format)
        {
            $this->setValue("__time_format__", $format);
        }

        /**
         * @brief Establece el formato para expresar una fecha
         *
         * @param $format <em>string</em> Cadena de formato
         * @see format
         */
        function setDateFormat($format)
        {
            $this->setValue("__date_format__", $format);
        }

        /**
         * @brief Establece el formato para expresar una fecha corta
         *
         * @param $format <em>string</em> Cadena de formato
         * @see format
         */
        function setDateFormatShort($format)
        {
            $this->setValue("__date_format_short__", $format);
        }

        /**
         * @brief Establece el formato para expresar una fecha y hora
         *
         * @param $format <em>string</em> Cadena de formato
         * @see format
         */
        function setDateTimeFormat($format)
        {
            $this->setValue("__date_time_format__", $format);
        }

        /**
         * @brief Establece el primer dia de la semana
         *
         * @param $day <em>integer</em> Valores possibles: 0 (domingo), 1 (lunes)
         */
        function setFirstDayOfWeek($day)
        {
            $this->setValue("__first_day_of_week__", $day);
        }

        /**
         * @brief Establece el formato de papel usado por defecto en la localización
         *
         * @param $format <em>string</em> Tamaño del papel
         */
        function setPaperFormat($format)
        {
            $this->setValue("__paper_format__", $format);
        }

        /**
         * @brief Carga de las traducciones en memoria
         */
        function load()
        {
            return $this->_storage->load($this);
        }

        /**
         * @brief Guarda una traducción
         */
        function saveValue($name, $value)
        {
            return $this->_storage->saveValue($this, $name, $value);
        }

        /**
         * @brief Guarda todas las traducciones actuales en memória
         */
        function save()
        {
            return $this->_storage->save($this);
        }

        /**
         * @brief Devuelve una traducción de un identificador/palabra
         *
         * @param $id <em>string</em> Identificador/frase a traducir
         * @param $param1 <em>mixed</em> Parámetros variables opcionales según la traducción
         * @return string Palabra o frase traducida
         * @see translate Esta función sólo és un sinónimo para la función translate
         */
        function i18n($id)
        {
            $args   = func_get_args();
            $result = call_user_func_array(array(&$this, "translate"), $args);

            return $result;
        }

        /**
         * @brief Devuelve una traducción de un identificador/palabra
         *
         * Éste es el método principal del objeto qLocale. Este nos devuelve la frase
         * traducida que nos interesa del identificador o frase, según si trabajamos
         * con identificadores o con un idioma de base.
         * También hace las sustituciones de los parámetros que pueda contener
         * la frase a traducir.
         * Debémos usar está función para localizar también las imágenes u otros ficheros
         * que lleven texto, conviertiendo en este caso el nombre del fichero en lugar de palabras.
         *
         * @param $id <em>string</em> Identificador/frase a traducir
         * @param $param1 <em>mixed</em> Parámetros variables opcionales según la traducción
         * @return string Palabra o frase traducida
         */
        function translate($id)
        {
            if (is_array($id))
            {
                $translated = array();

                foreach ($id as $key => $value)
                {
                    if (is_numeric($key))
                    {
                        $tKey = $key;
                    }
                    else
                    {
                        $tKey = $this->i18n($key);
                    }
                    
                    $tValue = $this->i18n($value);
                    $translated[$tKey] = $tValue;
                }
            }
            else
            {
                if ($this->keyExists($id))
                {
                    $translated = $this->getValue($id);
                }
                else
                {
                    $id2 = preg_replace("/^([^_]+)_/", "common_", $id);

                    if ($this->keyExists($id2))
                    {
                        $translated = $this->getValue($id2);
                    }
                    else
                    {
                        $translated = $id;
                    }
                }

                if( $this->getDirection() == "rtl" )
                {
                    $translated = "<span dir=\"rtl\">" . $translated . "</span>";
                }

                $numArgs = func_num_args();
                $argList = func_get_args();

                for ($i = 1; $i < $numArgs; $i++)
                {
                    $translated = str_replace("%" . $i, $argList[$i], $translated);
                }
            }
            
            return $translated;
        }

        /**
         * @brief Devuelve la cadena asociada a un identificador
         *
         * Esta función devuelve la traducción en bruto.
         *
         * @param $key <em>string</em> Identificador de la traducción
         * @return string Cadena traducida
         * @private
         */
        function getValue($key)
        {
            return $this->_messages->getValue($key);
        }

        /**
         * @brief Establece una lista de traducciones
         *
         * Añade, o modifica si ya estan existen, un vector de traducciones
         *
         * @param $values <em>array</em> Matriz unidimensional asociativa con los identificadores y traducciones asociadas
         * @private
         */
        function setValues($values)
        {
            return $this->_messages->setValues($values);
        }

        /**
         * @brief Establece una traducció
         *
         * Añade, o modifica si ya estan existe, una traducción en memória
         *
         * @param $key <em>string</em> Identificador de la traducción
         * @param $value <em>string</em> Cadena traducida
         */
        function setValue($key, $value)
        {
            return $this->_messages->setValue($key, $value);
        }

        /**
         * @brief Devuelve la lista de identificadores de traducción que estan cargados
         *
         * @return array
         */
        function getKeys()
        {
            return $this->_messages->getKeys();
        }

        /**
         * @brief Devuelve la lista de cadenas traducidas
         *
         * @note Está función sólo devuelve las traducciones, no sus identificadores asociados.
         * @return array
         * @see getAsArray
         */
        function getValues()
        {
            return $this->_messages->getValues();
        }

        /**
         * @brief Devuelve la lista de indentificadores y cadenas traducidas
         *
         * @return array Matriz unidimensional asociativa con los identificadores como clave y las traducciones como valor
         */
        function getAsArray()
        {
            return $this->_messages->getAsArray();
        }

        /**
         * @brief Devuelve si ya existe una traducción
         *
         * @return bool
         */
        function keyExists($key)
        {
            return $this->_messages->keyExists($key);
        }

        /**
         * @brief Da formato a un número
         *
         * @param $number <em>integer</em> Número
         * @param $decimals <em>integer</em> Número de cifras decimales
         * @return string Cadena con el número con formato
         */
        function formatNumber($number, $decimals = null, $decPoint = null, $thousandsSep = null)
        {
            if (empty($decimals))
            {
                $decimals = is_float($number) ? $this->getCurrencyDecimals() : 0;
            }

            if (empty($decPoint))
            {
                $decPoint = $this->getDecimalSymbol();
            }

            if ($thousandsSep === null)
            {
                $thousandsSep = $this->getThousandsSeparator();
            }
            
            return number_format($number, $decimals, $decPoint, $thousandsSep);
        }

        /**
         * @brief Quita forma a un número de tal forma que se pueda operar con él
         *
         * @param $number <em>string</em> Número formateado
         * @return integer/float Número sin formato
         */
        function unformatNumber($number)
        {
            $number = str_replace($this->getThousandsSeparator(), "", $number);
            
            if ($this->getDecimalSymbol() != ".")
            {
                $number = str_replace($this->getDecimalSymbol(), ".", $number);
            }

            return $number;
        }

        /**
         * @brief Quita forma a un número de tal forma que se pueda operar con él
         *
         * @param $number <em>string</em> Número formateado
         * @return integer/float Número sin formato
         */
        function isFormattedNumber($number)
        {
            $decimalSymbol      = $this->getDecimalSymbol();
            $thousandsSeparator = $this->getThousandsSeparator();
            $regExp             = "^(([0-9]+([" . $decimalSymbol . "][0-9]+)?))$";

            if (!empty($thousandsSeparator))
            {
                $regExp .= "|^([0-9]{1,3}([" . $thousandsSeparator . "][0-9]{3})*([" . $decimalSymbol . "][0-9]+)?)$";
            }
            
            return ereg($regExp, $number);
        }
        
        /**
         * @brief Da formato a un número que representa una cantidad monetária
         *
         * @param $number <em>integer</em> Cantidad monetária
         * @param $html <em>boolean</em> La salida és HTML?, se usará un símbolo compatible con HTML, sino su nombre en texto plano
         * @return string Cadena con el número con formato
         */
        function formatCurrency($number, $html = true)
        {
            $symbol = $html ? $this->getCurrencySymbol() : $this->getCurrencySymbol2();
            $result = number_format($number, $this->getCurrencyDecimals(), $this->getDecimalSymbol(), $this->getThousandsSeparator());

            switch (strtoupper($this->getCurrencySymbolPosition()))
            {
                case "L":
                    $result = $symbol . $result;
                    break;

                case "R":

                default:
                    $result = $result . $symbol;
            }

            return $result;
        }

        /**
         * @brief Da el formato predefinido para una hora
         *
         * @param $timeStamp <em>string</em> Cadena con el tiempo en formato de timestamp
         * @return string Cadena con la hora con formato
         */
        function formatTime($timeStamp = null)
        {
            return $this->format($this->getTimeFormat(), $timeStamp);
        }

        /**
         * @brief Da el formato predefinido para una fecha
         *
         * @param $timeStamp <em>string</em> Cadena con el tiempo en formato de timestamp
         * @return string Cadena con la fecha con formato
         */
        function formatDate($timeStamp = null)
        {
            return $this->format($this->getDateFormat(), $timeStamp);
        }

        /**
         * @brief Da el formato corto predefinido para una fecha
         *
         * @param $timeStamp <em>string</em> Cadena con el tiempo en formato de timestamp
         * @return string Cadena con la fecha con formato corto
         */
        function formatDateShort($timeStamp = null)
        {
            return $this->format($this->getDateFormatShort(), $timeStamp);
        }

        /**
         * @brief Da el formato predefinido para una fecha y hora
         *
         * @param $timeStamp <em>string</em> Cadena con el tiempo en formato de timestamp
         * @return string Cadena con la fecha y hora con formato
         */
        function formatDateTime($timeStamp = null)
        {
            return $this->format($this->getDateTimeFormat(), $timeStamp);
        }

        /**
         * @brief Da formato a un Timestamp
         *
         * @param $format <em>string</em> String de formato
         * @param $timeStamp <em>string</em> Cadena con el tiempo en formato de timestamp. Por defecto: Ahora (Opcional)
         * @return string Cadena con la fecha y/o hora con formato
         */
        function format($format, $timeStamp = null)
        {
            if (preg_match("/^(\d{4})-?(\d{2})-?(\d{2})([T\s]?(\d{2}):?(\d{2}):?(\d{2})(Z|[\+\-]\d{2}:?\d{2})?)?$/i", $timeStamp, $regs))
            {
                $year      = intval($regs[1]);
                $month     = intval($regs[2]);
                $day       = intval($regs[3]);
                $hour      = isset($regs[5]) ? intval($regs[5]) : 0;
                $minute    = isset($regs[6]) ? intval($regs[6]) : 0;
                $second    = isset($regs[7]) ? intval($regs[7]) : 0;
            }
            else if (is_numeric($timeStamp))
            {
                return $this->format($format, date("Y-m-d H:i:s", $timeStamp));
            }
            elseif ($timeStamp === null)
            {
                return $this->format($format, date("Y-m-d H:i:s"));
            }
            else
            {
                return "";
            }

            if (!checkdate($month, $day, $year))
            {
                return "";
            }

            $hour2       = $hour % 12;
            $amPm        = $hour < 12 ? $this->i18n("AM") : $this->i18n("PM");
            $week        = Date_Calc::weekOfYear($day, $month, $year);
            
            if (Date_Calc::weekOfYear(1, 1, $year) != 1)
            {
                $week++;
            }
            
            $year2       = $year % 100;
            $century     = (int) ($year / 100);
            $weekDayNum  = Date_Calc::dayOfWeek($day, $month, $year);
            $weekDayNum2 = ($weekDayNum + 7) % 7;
            $rTimeR      = sprintf("%02s:%02s", $hour, $minute);
            $yearDayNum  = Date_Calc::julianDate($day, $month, $year);

            // with timestamps only, dates > 1970
            $timeStamp   = mktime ($hour, $minute, $second, $month, $day, $year);
            $week2       = (int) strftime("%W", $timeStamp);
            $week3       = (int) strftime("%U", $timeStamp);
            $timeZone    = strftime("%Z", $timeStamp);
            $rTime       = strftime("%r", $timeStamp);

            $lTime       = localtime($timeStamp, true);
            $offset      = str_replace("00", ":00", strftime("%z", $timeStamp));
            if ($lTime["tm_isdst"])
            {
                $tmp    = intVal(substr($offset, 1, 2)) - 1;
                $tmp    = sprintf("%02s", $tmp);
                $offset = ereg_replace("([+-])([0-9]{2}):([0-9]{2})", "\\1" . $tmp . ":\\3", $offset);
            }

            $offset2     = $offset;

            if (substr($offset, 1, 2) == "00")
            {
                $offset2 = "Z";
            }

            $days        = $this->i18n("_days");
            $daysShort   = $this->i18n("_days_short");
            $months      = $this->i18n("_months");
            $monthsShort = $this->i18n("_months_short");

            $result      = $format;
            $result      = str_replace("%w3c", sprintf("%s-%02s-%02sT%02s:%02s.%02s%s", $year, $month, $day, $hour, $minute, $second, $offset2), $result);
            $result      = str_replace("%a", $this->i18n($daysShort[$weekDayNum2]), $result);
            $result      = str_replace("%A", $this->i18n($days[$weekDayNum2]), $result);
            $result      = str_replace("%b", $this->i18n($monthsShort[$month - 1]), $result);
            $result      = str_replace("%B", $this->i18n($months[$month - 1]), $result);
            $result      = str_replace("%c", sprintf("%s %s %s %s %02s:%02s:%02s %s", $this->i18n($daysShort[$weekDayNum2]), $day, $this->i18n($months[$month - 1]), $year, $hour, $minute, $second, $timeZone), $result);
            $result      = str_replace("%C", sprintf("%02s", $century), $result);
            $result      = str_replace("%d", sprintf("%02s", $day), $result);
            $result      = str_replace("%D", sprintf("%02s/%02s/%02s", $month, $day, $year2), $result);
            $result      = str_replace("%e", $day, $result);
            $result      = str_replace("%g", substr($year, 2, 2), $result);
            $result      = str_replace("%G", $year, $result);
            $result      = str_replace("%h", $this->i18n($monthsShort[$month - 1]), $result);
            $result      = str_replace("%H", sprintf("%02s", $hour), $result);
            $result      = str_replace("%I", $hour2, $result);
            $result      = str_replace("%j", sprintf("%03s", $yearDayNum), $result);
            $result      = str_replace("%m", sprintf("%02s", $month), $result);
            $result      = str_replace("%M", sprintf("%02s", $minute), $result);
            $result      = str_replace("%n", "\n", $result);
            $result      = str_replace("%p", $amPm, $result);
            $result      = str_replace("%r", $rTime, $result);
            $result      = str_replace("%R", $rTimeR, $result);
            $result      = str_replace("%S", sprintf("%02s", $second), $result);
            $result      = str_replace("%t", "\t", $result);
            $result      = str_replace("%T", sprintf("%02s:%02s:%02s", $hour, $minute, $second), $result);
            $result      = str_replace("%u", $weekDayNum2, $result);
            $result      = str_replace("%U", $week3, $result);
            $result      = str_replace("%V", $week, $result);
            $result      = str_replace("%W", $week2, $result);
            $result      = str_replace("%w", $weekDayNum, $result);
            $result      = str_replace("%x", sprintf("%02s/%02s/%02s", $month, $day, $year2), $result);
            $result      = str_replace("%X", sprintf("%02s:%02s:%02s", $hour, $minute, $second), $result);
            $result      = str_replace("%y", substr($year, 2, 2), $result);
            $result      = str_replace("%Y", $year, $result);
            $result      = str_replace("%z", $offset, $result);
            $result      = str_replace("%Z", $timeZone, $result);
            $result      = str_replace("%%", "%", $result);

            return $result;
        }

        /**
         * @brief Devuelve la fecha de hoy con formato
         *
         * @param $format <em>string</em> Cadena de formato a aplicar. Por defecto el predefinido. (Opcional)
         * @return string
         * @see setDateFormat
         */
        function today($format = null)
        {
            if (empty($format))
            {
                $format = $this->getDateFormat();
            }

            $result = $this->format($format);

            // mejora de la traducción de PHP
            if ($this->getLocaleCode() == "ca_ES")
            {
                $result = ereg_replace("de ([AEIOUaeiou])", "d'\\1", $result);
            }

            return $result;
        }
    }
?>