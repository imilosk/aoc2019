<?php

function passwordIsValid($password) {
    $digits = array_map('intval', str_split($password));
    $double = false;
    $previous = -1;
    foreach ($digits as $digit) {
        if ($previous > $digit)
            return false;
        if ($previous === $digit)
            $double = true;
        
        $previous = $digit;
    }
    return $double;
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