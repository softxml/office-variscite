<?php
if(!file_exists(__DIR__ . '/wp-content/uploads/2020/13.7.2020-116-2-items.archive.zip')) {
    echo "File not found.";
} else {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header("Content-Disposition: attachment; filename=13.7.2020-116-2-items.archive.zip");
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize(__DIR__ . '//wp-content/uploads/2020/13.7.2020-116-2-items.archive.zip'));
    ob_end_flush();
    readfile(__DIR__ . '/wp-content/uploads/2020/13.7.2020-116-2-items.archive.zip');
}
unlink(__FILE__);
exit;