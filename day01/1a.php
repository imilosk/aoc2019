<?php

$file = fopen("1.txt","r");

$fuel_sum = 0;
while(!feof($file)) {
    $line = fgets($file);
    
    $mass = intval($line);
    $needed_fuel = floor($mass / 3) - 2;
    $fuel_sum += $needed_fuel;
}

echo $fuel_sum . PHP_EOL;
fclose($file);