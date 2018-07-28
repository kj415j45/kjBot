<?php
function delDir($directory){if(file_exists($directory)){if($dir_handle=@opendir($directory)){while($filename=readdir($dir_handle)){if($filename!='.'&&$filename!='..'){$subFile=$directory."/".$filename;if(is_dir($subFile)){delDir($subFile);}if(is_file($subFile)){unlink($subFile);}}}closedir($dir_handle);rmdir($directory);}}}
delDir('module');
delDir('middleWare');
mkdir('module');
mkdir('middleWare');
file_put_contents('middleWare/Chain.php', "<?php\n");
?>
