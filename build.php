<?php
file_put_contents("composer-installer", fopen("https://getcomposer.org/installer", 'r'));
exec("php composer-installer");
exec("php composer.phar install");

$here = __DIR__.'/';
mkdir($here.'storage/data', 0777, true);
mkdir($here.'storage/cache', 0777, true);
fopen("storage/data/black.txt","a");
$db = new SQLite3('storage/data/stat.db');

$sql=<<<EOF
PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE record(
user_id BIGINT NOT NULL,
command TEXT NOT NULL,
count NOT NULL
);
COMMIT;
EOF;
$db->query($sql);
?>