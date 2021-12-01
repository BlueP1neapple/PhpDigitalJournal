<?php
// Функции
include "functions/publicFunctions.php";
include "functions/application.php";



$resultApplication = app(
    $_SERVER['REQUEST_URI'], //Полный путь запроса
    $_GET, //Глобальная перменная с параметрами поиска
    'loggerInFile'); //Название функции логирования

render($resultApplication['httpCode'], $resultApplication['result']);