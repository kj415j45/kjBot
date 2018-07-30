<?php
global $User_id;
unlink("../storage/data/osu/mp/{$User_id}");
leave('已停止监听');
?>
