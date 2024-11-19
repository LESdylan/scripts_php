<?php
include_once 'architecture\include\headers.php'; // include the content page from the path
require_once 'architecture\include\constants.php'; //include the content page only after verifying if the page exist.
require_once "display/list.php";
$data = ['Chaque élémént', 's\'affiche', 'sur une ligne'];
$dataToDisplay = displayList($data);
include 'html/page.php';
?>
<p>Welcome to everyone</p>