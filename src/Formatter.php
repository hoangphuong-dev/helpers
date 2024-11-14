<?php

namespace Helpers;

class Formatter
{
    /**
     * Fix lỗi font đơn giản 1 số ký tự tiếng việt
     *
     * @param  string  $myString
     *
     * @return array|string
     */
    public static function simpleFixVn(string $myString = ''): array|string
    {
        $arrayFck  = [
            "&Agrave;", "&Aacute;", "&Acirc;", "&Atilde;",
            "&Egrave;", "&Eacute;", "&Ecirc;",
            "&Igrave;", "&Iacute;", "&Icirc;", "&Iuml;",
            "&ETH;",
            "&Ograve;", "&Oacute;", "&Ocirc;", "&Otilde;",
            "&Ugrave;", "&Uacute;",
            "&Yacute;",
            "&agrave;", "&aacute;", "&acirc;", "&atilde;",
            "&egrave;", "&eacute;", "&ecirc;",
            "&igrave;", "&iacute;",
            "&ograve;", "&oacute;", "&ocirc;", "&otilde;",
            "&ugrave;", "&uacute;", "&ucirc;",
            "&yacute;",
        ];
        $arrayText = [
            "À", "Á", "Â", "Ã",
            "È", "É", "Ê",
            "Ì", "Í", "Î", "Ï",
            "Ð",
            "Ò", "Ó", "Ô", "Õ",
            "Ù", "Ú",
            "Ý",
            "à", "á", "â", "ã",
            "è", "é", "ê",
            "ì", "í",
            "ò", "ó", "ô", "õ",
            "ù", "ú", "û",
            "ý",
        ];

        return str_replace($arrayFck, $arrayText, $myString);
    }

    /**
     * Bỏ dấu tiếng việt
     *
     * @param  string  $myString
     *
     * @return array|string
     */
    public static function removeAccent(string $myString = ''): array|string
    {
        $marTViet = [
            'a' => ["à", "á", "ạ", "ả", "ã", "â", "ầ", "ấ", "ậ", "ẩ", "ẫ", "ă", "ằ", "ắ", "ặ", "ẳ", "ẵ"],
            'A' => ["À", "Á", "Ạ", "Ả", "Ã", "Â", "Ầ", "Ấ", "Ậ", "Ẩ", "Ẫ", "Ă", "Ằ", "Ắ", "Ặ", "Ẳ", "Ẵ"],
            'e' => ["è", "é", "ẹ", "ẻ", "ẽ", "ê", "ề", "ế", "ệ", "ể", "ễ"],
            'E' => ["È", "É", "Ẹ", "Ẻ", "Ẽ", "Ê", "Ề", "Ế", "Ệ", "Ể", "Ễ"],
            'i' => ["ì", "í", "ị", "ỉ", "ĩ"],
            'I' => ["Ì", "Í", "Ị", "Ỉ", "Ĩ"],
            'o' => ["ò", "ó", "ọ", "ỏ", "õ", "ô", "ồ", "ố", "ộ", "ổ", "ỗ", "ơ", "ờ", "ớ", "ợ", "ở", "ỡ"],
            'O' => ["Ò", "Ó", "Ọ", "Ỏ", "Õ", "Ô", "Ồ", "Ố", "Ộ", "Ổ", "Ỗ", "Ơ", "Ờ", "Ớ", "Ợ", "Ở", "Ỡ"],
            'u' => ["ù", "ú", "ụ", "ủ", "ũ", "ư", "ừ", "ứ", "ự", "ử", "ữ"],
            'U' => ["Ù", "Ú", "Ụ", "Ủ", "Ũ", "Ư", "Ừ", "Ứ", "Ự", "Ử", "Ữ"],
            'y' => ["ỳ", "ý", "ỵ", "ỷ", "ỹ"],
            'Y' => ["Ỳ", "Ý", "Ỵ", "Ỷ", "Ỹ"],
            'd' => ["đ"],
            'D' => ["Đ"],
            ''  => ["'"],
        ];

        foreach ($marTViet as $replace => $search) {
            $myString = str_replace($search, $replace, $myString);
        }

        return $myString;
    }

    /**
     * Loại bỏ các ký tự đặc biệt
     *
     * @param  string  $myString
     *
     * @return array|string
     */
    public static function removeSpecialCharacter(string $myString = ''): array|string
    {
        $marCharacter = [
            ''  => [
                '&mdash;', '&quot;', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')',
                '_', '+', '{', '}', '|', ':', '"', '<', '>', '?', '[', ']', '\\', ';',
                "'", ',', '/', '*', '+', '~', '`', '=', '“', '”', "'", '‘', '’',
            ],
            " " => ["  ", "-"],
            '-' => ['.', ' ', '--'],
        ];

        foreach ($marCharacter as $replace => $search) {
            $myString = str_replace($search, $replace, $myString);
        }

        return $myString;
    }

    /**
     * Loại bỏ quotes trong chuỗi
     *
     * @param  string  $str
     *
     * @return array|string
     */
    public static function removeQuotes(string $str = ''): array|string
    {
        $temp = str_replace("\'", "'", $str);
        $temp = str_replace("\\", "\\\\", $temp);

        return str_replace("'", "''", $temp);
    }

    /**
     * Loại bỏ nhiều ký tự ['.', ' ', '-', '_', '\n'] lặp lại liền kề nhau
     *
     * @param  string  $str
     *
     * @return string
     */
    public static function removeSomeChar(string $str): string
    {
        //Loại bỏ nhiều dấu ... từ mục lục
        $str = preg_replace("/[\.]{2}/U", ' ', $str);

        // Loại bỏ nhiều khoảng trắng thành 1 khoảng trắng
        $str = preg_replace("/[\s]{2}/U", ' ', $str);

        //Loại bỏ nhiều dấu .space từ mục lục
        $str = preg_replace("/[\.\s]{2}/U", ' ', $str);

        //Loại bỏ nhiều dấu --- từ mục lục
        $str = preg_replace("/[-]{2}/U", ' ', $str);

        //Loại bỏ nhiều dấu ___ từ mục lục
        $str = preg_replace("/[_]{2}/U", ' ', $str);

        // Biến dấu xuống dòng thành 1 khoảng trắng
        $str = preg_replace("/\n/U", ' ', $str);

        return $str;
    }

    /**
     * Chuỗi thành định dạng slug
     *
     * @param  string  $string
     * @param  string  $keyReplace
     *
     * @return array|string
     */
    public static function slug(string $string = '', string $keyReplace = "/"): array|string
    {
        $string = self::removeAccent($string);
        $string = trim(preg_replace("/[^A-Za-z0-9]/i", " ", $string));
        $string = str_replace(" ", "-", $string);
        $string = str_replace("--", "-", $string);
        $string = str_replace("--", "-", $string);
        $string = str_replace("--", "-", $string);
        $string = str_replace("--", "-", $string);
        $string = str_replace("--", "-", $string);
        $string = str_replace("--", "-", $string);
        $string = str_replace("--", "-", $string);
        $string = str_replace($keyReplace, "-", $string);

        return strtolower($string);
    }

    /**
     * Làm sạch keyword
     *
     * @param $string
     *
     * @return string
     */
    public static function cleanKeyword($string): string
    {
        $string = self::simpleFixVn($string);
        $text   = self::removeAccent($string);

        //check số lượng từ khóa ít quá thì ko bẻ nữa
        $arrText = explode(' ', $text);
        if (count($arrText) <= 2) {
            return '';
        }

        $text       = mb_strtolower($text . " " . $string, 'UTF-8');
        $title      = self::removeSpecialCharacter($text);
        $array_text = explode(" ", $title);
        $str_key    = "";
        foreach ($array_text as $i => $iValue) {
            $str_key .= $iValue . " ";
            if (($i + 1) % 2 == 0) {
                $str_key .= ",";
            }
        }

        $str_key = trim($str_key, ",");
        unset($array_text);
        return $str_key;
    }

    /**
     * Array to base64
     *
     * @param  array  $data
     *
     * @return string
     */
    public static function arrayToBase64(array $data): string
    {
        return base64_encode(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Tạo tên đơn giản theo time
     *
     * @return string
     */
    public static function simpleName(): string
    {
        $name = "";
        for ($i = 0; $i < 3; $i++) {
            $name .= chr(rand(97, 122));
        }
        $name .= time();
        return $name;
    }

    /**
     * Chuyển đầu số cũ sang đầu số mới
     *
     * @param  string  $phoneNumber
     *
     * @return string
     */
    public static function mapPhoneNumber(string $phoneNumber): string
    {
        $mapNewPhone = [
            //Viettel
            '016'  => '03',
            //VinaPhone
            '0123' => '083',
            '0124' => '084',
            '0125' => '085',
            '0127' => '081',
            '0129' => '082',
            //Mobiphone
            '0120' => '070',
            '0121' => '079',
            '0122' => '077',
            '0126' => '076',
            '0128' => '078',
            //Gmobile
            '0199' => '059',
            //Vietnammobile
            '018'  => '05',
        ];

        $phoneNumber = preg_replace("/\D/", "", $phoneNumber);
        if (str_starts_with($phoneNumber, '84')) {
            $phoneNumber = preg_replace("/84/", "0", $phoneNumber, 1);
        }

        foreach ($mapNewPhone as $old => $new) {
            if (str_starts_with($phoneNumber, $old)) {
                $phoneNumber = preg_replace("/{$old}/", $new, $phoneNumber, 1);
                break;
            }
        }

        return $phoneNumber;
    }

    /**
     * Định dạng số điện thoại
     *
     * @param $phone
     *
     * @return string
     */
    public static function phoneFormat($phone): string
    {
        preg_match('/^(\d{4})(\d{3})(\d{3})$/', $phone, $matches);
        return $matches[1] . ' ' . $matches[2] . ' ' . $matches[3];
    }

    /**
     * Strip tag
     *
     * @param  string|null  $content
     *
     * @return string
     */
    public static function stripTagsDescription(?string $content = null): string
    {
        $arrayTagAccepted = ['b'];
        return strip_tags($content, $arrayTagAccepted);
    }

    /**
     * Strip tag
     *
     * @param  string|null  $content
     *
     * @return string
     */
    public static function stripTagsFullText(?string $content = null): string
    {
        $arrayTagAccepted = [
            'p', 'b', 'span', 'i', 'ol', 'ul', 'li', 'strong', 'em',
        ];
        return strip_tags($content, $arrayTagAccepted);
    }

    /**
     * Chuyển số sạng dạng ngắn gọn
     *
     * @param $rawNumber
     *
     * @return string
     */
    public static function humanReadable($rawNumber): string
    {
        $units = ['', 'K', 'M', 'B', 'T'];
        for ($i = 0; $rawNumber > 1000; $i++) {
            $rawNumber /= 1000;
        }

        return round($rawNumber, 1) . $units[$i];
    }

    /**
     * Định dạng lại kích thước file từ byte sang đơn vị lớn nhất có thể
     *
     * @param        $size
     * @param  int  $precision
     * @param  bool  $haveSpaceBetween
     *
     * @return string
     */
    public static function byteFormat($size, int $precision = 2, bool $haveSpaceBetween = true): string
    {
        $base     = log($size, 1024);
        $suffixes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        return round(
            pow(1024, $base - floor($base)),
            $precision
        ) . ($haveSpaceBetween ? ' ' : '') . $suffixes[floor($base)];
    }

    /**
     * Định đạng số theo chuẩn Việt Nam
     *
     * @param $num
     *
     * @return array|string
     */
    public static function numberFormatVn($num): array|string
    {
        if ($num == 0) {
            return 'Miễn phí';
        }

        $num = (float) $num;
        if (is_null($num)) {
            return 0;
        }
        return number_format(num: $num, decimal_separator: ',', thousands_separator: '.') . ' VNĐ';
    }

    public static function formatDateTime(string $dateTime)
    {
        // return Carbon::parse($dateTime)->format("d/m/Y H:i");
    }
}
