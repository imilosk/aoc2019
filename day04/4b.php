<?php

function passwordIsValid($password) {
    $digits = array_map('intval', str_split($password));
    $previous = -1;
    $previous2 = -1;
    $frequencies = array_count_values($digits);
    $doubles = array_filter($frequencies, function($n) {
        return $n === 2;
    });

    foreach ($digits as $digit) {
        if ($previous > $digit)
            return false;
        $previous = $digit;
    }
    return count($doubles) >= 1;
}

$file         = fopen("4.txt","r");
$line         = fgets($file);
$range        = explode('-', $line);
$bottom_range = intval($range[0]);
$upper_range  = intval($range[1]);

$numb_different_passwords = 0;
for ($i = $bottom_range; $i < $upper_range + 1; $i++) {
    if (passwordIsValid($i)) {
        $numb_different_passwords++;
    }
}

echo $numb_different_passwords . PHP_EOL;
fclose($file);