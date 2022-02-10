<?php
function asset($file){
    global $RELATIVE_PATH;
    if (filter_var($file, FILTER_VALIDATE_URL)) {
	return $file;
    }
    else {
	return preg_replace('~/+~', '/', '/'.trim($RELATIVE_PATH, '/').'/'.trim($file, '/'));
    }
}
