<?php
file_put_contents("composer-installer", file_get_contents("https://getcomposer.org/installer"));
exec("php composer-installer",$x,$rValue1);
exec("php composer.phar install",$x,$rValue2);
if(($rValue1!=$rValue2)||($rValue1!=0))
    die();

$here = __DIR__.'/';
mkdir($here.'storage/data', 0777, true);
mkdir($here.'storage/cache', 0777, true);
fopen("storage/data/black.txt","a");
$db = new SQLite3('storage/data/stat.db');

$sql=file_get_contents($here.'stat.sql');
$db->query($sql);
?>