<?php

$folderPath = '../amostra';

$files = glob($folderPath . "/*.txt");
$letters = [];

$count = 0;
$groupCount = 0;
foreach ($files as $file) {
    if ($count % 5 == 0) {
        $groupCount++;
    }
    if ($groupCount <= 20) {
        $content = file_get_contents($file);
        $letter = substr($content, 0, 1);
        $letters[] = $letter;
    }
    $count++;
}

$pass = implode('', array_slice($letters, 0, 16));

echo $pass;

?>