<!--
На странице есть блок текста, кнопка 1 и кнопка 2. Напишите код, который по нажатию сначала на кнопку 1, а потом на кнопку 2 скрывает блок, если он раскрыт, и раскрывает, если он скрыт.
Примечание: помимо ES6 можно использовать JQuery для решения
-->
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        let flag_button_1 = 0;//Была ли нажата 1 кнопка

        $(document).on("click", "#but_1,#but_2", function() { hide_or_show($(this)); });

        function hide_or_show(obj) {
            if(obj.attr("id")=="but_1") {
                flag_button_1 = 1;
                obj.addClass("btn-primary").removeClass("btn-secondary");
            }
            else if(obj.attr("id")=="but_2") {
                if(flag_button_1) {
                    obj.addClass("btn-primary").removeClass("btn-secondary");
                    $("#block_1").toggle(800, function() {
                        $(".btn-primary").addClass("btn-secondary").removeClass("btn-primary");
                    });
                }
                flag_button_1 = 0;

            }
        }
</script>
</head>
<body>
    <div class="container col-8 align-items-center">
    <div id="block_1">
        <h4>На странице есть блок текста, кнопка 1 и кнопка 2.</br>Напишите код, который по нажатию сначала на кнопку 1, а потом на кнопку 2 скрывает блок, если он раскрыт, и раскрывает, если он скрыт.</h4>
        <p>Примечание 1: помимо ES6 можно использовать JQuery</p>
        <p>Примечание 2: в задачу согласовано добавлено изменения стиля кнопок</p>
    </div>
    <div>
        <button class="btn btn-secondary" id="but_1" type="button" >Кнопка 1</button>
        <button class="btn btn-secondary" id="but_2" type="button" >Кнопка 2</button>
    </div>
</body>
</html>