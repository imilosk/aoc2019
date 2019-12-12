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

$number_of_layers = count($layers);
$current_layer = 0;
$result_image = [];
for ($i = 0; $i < $layer_length; $i++) {
    $current_layer = -1;
    do {
        $current_layer++;
        if ($current_layer == $number_of_layers)
            $current_layer = 0;
        $result_image[$i] = $layers[$current_layer][$i];
    } while($layers[$current_layer][$i] == 2);
}

for ($i = 0; $i < $layer_length; $i++) {
    if ($i % $width == 0 )
        echo PHP_EOL;
    
    $c = $result_image[$i];
    if ($c == 0)
        echo ' ';
    elseif ($c == 1) 
        echo 'X';
}

fclose($file);