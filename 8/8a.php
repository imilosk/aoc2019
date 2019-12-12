<?php

$file = fopen("8.txt","r");
$line = fgets($file);
$chars = str_split($line);

$width = 25;
$height = 6;

$layers = [];
$layer_length = $width * $height;
$layer_numb = 0;
$current_layer_length = 0;

for ($i = 0; $i < count($chars); $i += $width) {
    $row = [];
    for ($j = 0; $j < $width; $j++) {
        $row[] = $chars[$i + $j];
    }
    if (!isset($layers[$layer_numb])) 
        $layers[$layer_numb] = [];

    $layers[$layer_numb] = array_merge($layers[$layer_numb], $row);
    $current_layer_length += $width;
    if ($current_layer_length === $layer_length) {
        $current_layer_length = 0;
        $layer_numb++;
    }
}

$min_zeros_layer_index = -1;
$min_zeros = PHP_INT_MAX;
for ($i = 0; $i < count($layers); $i++) {
    $layer = $layers[$i];
    $zeros = count(array_keys($layer, 0));
    if ($zeros < $min_zeros && $zeros > 0) {
        $min_zeros = $zeros;
        $min_zeros_layer_index = $i;
    }
}

$n1 = count(array_keys($layers[$min_zeros_layer_index], 1));
$n2 = count(array_keys($layers[$min_zeros_layer_index], 2));

echo $n1 * $n2;

fclose($file);