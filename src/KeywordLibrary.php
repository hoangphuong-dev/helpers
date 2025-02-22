<?php

namespace Helpers;

use Collective\Html\HtmlFacade;

class KeywordLibrary
{
    public static function convertToUtf8(string $text): bool|string
    {
        $encoding = mb_detect_encoding($text, mb_detect_order());

        if ($encoding == 'UTF-8' || $encoding === false) {
            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        }

        return iconv(mb_detect_encoding($text, mb_detect_order()), 'UTF-8//IGNORE', $text);
    }

    /**
     * Chuẩn hóa nội dung trang để trả về chỉ có dạng text
     */
    public static function getTextOnly(string $text): mixed
    {
        $text = static::removeSortSentences($text);

        // Tách đoạn văn bản thành các dòng
        $lines = array_filter(explode("\n", $text));

        // Loại bỏ khoảng trắng thừa ở đầu và cuối mỗi dòng
        $trimmed_lines = array_map('trim', $lines);

        // Ghép các dòng lại thành một đoạn văn bản
        $text = implode("\n", $trimmed_lines);

        $text = trim(static::convertToUtf8($text));
        return $text;
    }

    /**
     * Tô đậm từ khoá trong một đoạn text
     * @param $key
     * @param $title
     * @return string
     */
    public static function searchKeyword(?string $key, string $title): string
    {
        if (empty($key)) return $title;
        $search_text = mb_strtolower(Formatter::removeAccent($key), "UTF-8");
        $title_lower = mb_strtolower(Formatter::removeAccent($title), "UTF-8");

        $search_text_array = array_unique(explode(" ", $search_text));

        $arrTitle = explode(' ', $title);
        $arrTitleLower = explode(' ', $title_lower);

        $marked_title = [];
        foreach ($arrTitleLower as $index => $word) {
            if (in_array($word, $search_text_array)) {
                $marked_title[] = '<mark data-markjs="true">' . $arrTitle[$index] . '</mark>';
            } else {
                $marked_title[] = $arrTitle[$index];
            }
        }

        return implode(' ', $marked_title);
    }

    /**
     * lấy chuyển chuỗi tiếng anh thành tiếng việt
     */
    public static function convertViToEn($str): array|string|null
    {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        return $str;
    }

    /**
     * Loại bỏ các ký tự đặc biệt, chỉ để lại các ký tự chấp nhận pattern \w \d hoặc \.
     */
    public static function removeSymbols($text): mixed
    {
        // $text = static::removeSortSentences($text);
        $text = preg_replace('/[^\w^\d^\.^]+/uis', ' ', $text);
        return preg_replace("/\.+/", '.', $text);
    }

    /**
     * Xóa bỏ các câu ngắn hơn 3 từ
     *
     * @param  string  $string
     *
     * @return string
     */
    public static function removeSortSentences(string $string): string
    {
        $startPosition = 0;
        $newText       = '';
        $string = trim($string, ".\n\r\t\v\0");
        $string = preg_replace('/\s+/', ' ', $string);

        while (($endPosition = strpos($string, '.', $startPosition)) !== false) {
            // Tìm câu từ vị trí bắt đầu đến vị trí kết thúc
            $sentence = substr($string, $startPosition, $endPosition - $startPosition + 1);

            // Kiểm tra số từ và thêm câu vào văn bản mới
            if (self::wordsCount($sentence) >= 3) {
                $newText .= $sentence;
            }

            // Cập nhật vị trí bắt đầu cho lần lặp tiếp theo
            $startPosition = $endPosition + 1;
        }

        $lastSentence = substr($string, $startPosition);
        if (self::wordsCount($lastSentence) >= 3) {
            $newText .= $lastSentence;
        }

        return $newText;
    }

    public static function wordsCount(string $string)
    {
        return count(self::extractWords($string));
    }

    public static function extractWords(string $string)
    {
        $string = preg_replace("/[\(\[].*[\)\]]/", ' ', $string);
        $string = preg_replace("/[\W\p{Z}\p{N}]/u", ' ', $string);
        $string = preg_replace("/\s{2,}/", ' ', $string);

        $latin = $cjk = $hangul = [];
        if (preg_match_all('/[a-zẠ-ỹàâçéèêëîïôûùüÿñæœ]{2,}/ui', $string, $matches)) {
            $latin = $matches[0];
        }
        if (preg_match_all("/[\p{Hiragana}\p{Katakana}\p{Han}]/ui", $string, $matches)) {
            $cjk = $matches[0];
        }
        if (preg_match_all("/[\p{Hangul}]/ui", $string, $matches)) {
            $hangul = $matches[0];
        }

        return [...$latin, ...$cjk, ...$hangul];
    }

    public static function removeExt(string $title): string
    {
        // Remove các định dạng file.
        $title = preg_replace('/\.docx/i', '', $title);
        $title = preg_replace('/\.pptx/i', '', $title);
        $title = preg_replace('/\.doc/i', '', $title);
        $title = preg_replace('/\.ppt/i', '', $title);
        $title = preg_replace('/\.pdf/i', '', $title);
        $title = preg_replace('/\.pot/i', '', $title);
        $title = preg_replace('/\.potx/i', '', $title);
        $title = preg_replace('/\.zip/i', '', $title);
        $title = preg_replace('/\.rar/i', '', $title);
        return $title;
    }

    /**
     * Method standardTitle
     *
     * @param string $title
     *
     * @return string
     */
    public static function standardTitle(string $title): string
    {
        $title = static::removeExt($title);
        $title = static::keywordToStandard($title);

        // Tách các từ.
        $arrWord = preg_split('/[-_\s\.]/', $title);
        if ($arrWord) {
            if (count($arrWord) == 1) {
                // Các từ liền nhau ví dụ: KhoaHocThuongThuc
                $arrWord = preg_split('/([A-Z][^A-Z]+)|([A-Z]+)/', $arrWord[0], -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            }
            if ($arrWord) {
                // Nối các từ.
                $title = '';
                foreach ($arrWord as $key => $value) {
                    if ($key > 0) {
                        if (strlen($value) > 1 && preg_match('/[A-Z]{2}/', $value) === true) {
                            $arrWord[$key] = $value;
                        } else {
                            $arrWord[$key] = strtolower($value);
                        }
                    }
                    $title .= $arrWord[$key] . ' ';
                }
            }
        }

        // Viết hoa chữ cái đầu tiên của title
        $title = mb_strtoupper(mb_substr($title, 0, 1), 'UTF-8') . mb_substr($title, 1);
        return trim($title);
    }


    /**
     * keywordToStandard : đưa tag về dạng chuẩn
     *
     * @param  mixed  $keyword
     * @return array|bool|string|null
     */
    public static function keywordToStandard(mixed $keyword = ""): array|bool|string|null
    {
        //chuẩn hóa nếu là json
        $keyword    = self::standardTextJson($keyword);
        $strToSpace = array('+', '=', '_', '.', ',', "–", '\\', "-");
        $strSpecial = array(
            chr(9),
            chr(10),
            chr(13),
            '"',
            ".",
            "?",
            ":",
            "*",
            "%",
            "#",
            "|",
            "/",
            "\\",
            ",",
            "‘",
            "’",
            '“',
            '”',
            "&nbsp;",
            '@',
            '~',
            '[',
            ']',
            '(',
            ')',
            "'",
            "'",
            '%',
            '$',
            '#',
            '&',
            '^',
            "–",
            "&quot;",
            "&#34;",
            "\"",
            "&apos;",
            "&#39;",
            "'",
            "&laquo;",
            "&#171;",
            "«",
            "&raquo;",
            "&#187;",
            "»",
            "?",
            ":",
            "“",
            "”",
            "(",
            ")",
            "!",
            "-",
            "_",
            "[",
            "]",
            "{",
            "}",
            "|",
            "\\",
            "/",
            "%",
            "#",
            "&",
            "@",
            "$",
            "^",
            "&",
            "*",
            "+",
            ".",
            "=",
            ";",
            "<",
            ">",
            "...",
            "…",
            "–"
        );
        $keyword    = str_replace($strToSpace, " ", $keyword);
        $keyword    = str_replace($strSpecial, "", $keyword);
        $keyword    = preg_replace('/ +/', ' ', $keyword);
        $keyword    = trim($keyword);
        $keyword    = mb_strtolower($keyword, "UTF-8");
        return $keyword;
    }

    /**
     * Standard chuoi đã có sẵn trong database
     * Input: Text đg bị json encode
     * Output: Text ngon, đã loại bỏ các string đã bị utf8 decode, hay nói riêng là json encode
     */
    public static function standardTextJson($str)
    {
        $strOgriginal = $str;

        $str = strtolower($str);
        $str = str_replace("u0", "\u0", $str);
        $str = str_replace("u1", "\u1", $str);
        $obj = json_decode('{"key":"' . $str . '"}');
        if (!$obj) {
            return $strOgriginal;
        }
        return $obj->key;
    }
}
