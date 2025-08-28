<?php
require 'vendor/autoload.php';

use Stichoza\GoogleTranslate\GoogleTranslate;

if(isset($_POST['text'])){
    $text = $_POST['text'];
    $tr = new GoogleTranslate('de');
    echo $tr->translate($text);
}
