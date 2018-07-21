<?php
global $Queue, $Text;
use \Statickidz\GoogleTranslate;

$source = nextArg();
$target = nextArg();
$trans = new GoogleTranslate();

$Queue[]= sendBack($trans->translate($source, $target, $Text));

?>
