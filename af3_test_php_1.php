<?php
/**
Примечание от работодателя: игнорируем принципы KISS и YAGNI
Примечание -- 2: основная задача демонстрация применения ООП
 */
/**
Написать на PHP парсер html страницы (на входе url), который на выходе будет отображать количество и название всех используемых html тегов. Использование готовых парсеров и библиотек запрещено, включая модуль DOM.
(обязательно использование ООП подхода, демонстрирующее основные принципы структурирования и взаимодействия объектов
не нужно придерживаться принципа KISS, приветствуется преувеличение уровня абстракции).
Основная цель задания не получить верный ответ, а продемонстрировать какие либо навыки организации кода с использованием ООП.
Допускаются предположения не описанные в задаче, оверкодинг.
По завершению тестового задания, определитесь для себя,  сколько времени у вас ушло на выполнение задачи и сообщите нам. Использование ООП обязательно.
 **/
//Trait to work with URL for several part our script
trait UrlLib {
    //Method works with URL witch have "200" http header response code
    public function urlExist(string $url) : bool  {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return false; //url no valid
        }
        $headers = get_headers($url);
        if(strpos($headers[0],"200")===false) {
            return false; //no have html
        }
        return true;
    }
    //Method return html code form url or false
    public function htmlGet(string $url) :string|bool {
        if($this->urlExist($url)) {
            return file_get_contents($url);
        }
        return false;
    }
}
//Joint interface for view in page class
interface View {
    public function viewModule(mixed $str) :string;
}
//Class with BizLogic
class Logic {
    use UrlLib;//unclude method urlExist, htmlGet

    //Method for get array with tag name and tag quantity
    //or if array not isset - return false
    public function htmlParseTag($html) :Array|bool {
        if(trim($html)==""){ return false;}

        preg_match_all("~(?<=[\<])[a-z]+(?=[\s\>])~uis", $html, $arr_find);
        if(!is_array($arr_find)) { return false;}

        $arr_tag = array();
        foreach($arr_find[0] as $el) {
           if(!isset($arr_tag[$el])) { $arr_tag[$el] = 0; }
           ++$arr_tag[$el];
        }
        return $arr_tag;
    }
}
//Class for view logic in page
class ViewLogic extends Logic implements View {
    //Default interface view method
    public function viewModule(mixed $arr): string {
        $str = "";
        if(is_array($arr)) {
            foreach($arr as $key=>$el) {
                $str .= "<tr><td>{$key}</td><td>{$el}</td></tr>";
            }
            $str = "<table>{$str}</table>";
        }
        return $str;
    }
    //View method with tag quantities sort param
    public function viewModuleSort($arr,$sort_param = "DESC") :string {
        if($sort_param=="DESC") { arsort($arr); }
        else { asort($arr); }

        $str = $this->viewModule($arr);
        return $str;
    }
}

## Start Main part
$url = "https://lenta.ru/";
$logic = new ViewLogic();
$html = $logic->htmlGet($url);
if($html === false) {
    die("url not correct or page not exist");
}
$tag_arr = $logic->htmlParseTag($html);

$view = new ViewLogic();
echo $view->viewModuleSort($tag_arr, "DESC");
## End Main part