<?php
require 'vendor/autoload.php'; // Make sure Composer autoload is included

use Stichoza\GoogleTranslate\GoogleTranslate;

if(isset($_POST['text'])){
    $text = $_POST['text'];
    $tr = new GoogleTranslate('de'); // target language = German
    $translated = $tr->translate($text);
    echo $translated;
}
