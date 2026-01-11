<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__)
    ->notName('*template.php'); //хитрое условие для ВСЕ PHP файлы где есть template в имени

return (new Config())
    ->setFinder($finder)
    ->setRules([
        "@auto" => true,
        "@PSR12" => true,
    ]);