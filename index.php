<?php
// Функции
include "functions/publicFunctions.php";
include "functions/application.php";



$resultApplication = app();

render($resultApplication['httpCode'], $resultApplication['result']);