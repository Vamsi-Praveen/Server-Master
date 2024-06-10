<?php

	$target_file = '/etc/nginx/sites-available/default';

	$content = file_get_contents($target_file);

	echo $content ."<br/>";

	echo "writing into file";

	$content_write = "hello test file";

	$result = file_put_contents($target_file, $content_write,FILE_APPEND);

	echo $result;


?>