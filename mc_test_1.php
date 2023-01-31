<?php
/**
Будем считать, что строка является "корректным" php-кодом, если в ней правильно расставлены фигурные скобки (что внутри и снаружи скобок - неважно).
Примеры:
"{{lajkdhf{adfa}{}adfasdfadf{}}}" - корректный код.
"{{lajkdhf{adfa" - некорректный код.
Напишите класс, в конструктор которого передается строка, и в котором будет метод, который проверяет, является ли переданная строка корректным кодом.
Уточнение 1: строка без скобок и пустая строка являются "Некорректным кодом"
Уточнение 2: минимальный дополнительный функционал не запрещён
**/

class Valid {
    public string $str;

    function __construct(string $str){ return $this->str = $str; }

    public function validS() {
        if(!preg_match("~[\{\}]{1}~i", $this->str, $temp)) {
            return "Некорректный код"; //or return false;
        }

        $arr_from_str = str_split($this->str);
        $all_bracket_open  = 0;
        $all_bracket_close = 0;
        foreach ($arr_from_str as $key=>$el) {
            if($el=="}" && !$all_bracket_open) {
                return "Некорректный код"; //or return false;
            }
            else if($el=="}") { ++$all_bracket_close; }
            else if($el=="{") { ++$all_bracket_open;  }
        }
        if($all_bracket_open==$all_bracket_close) {
            return "Корректный код"; //or return true;
        }
        return "Некорректный код"; //or return false;
    }
}

$str = "{{lajkdhf{adfa}{}adfasdfadf{}}}"; //$str = "{{lajkdhf{adfa";
$a = new Valid($str);
echo $a->validS();