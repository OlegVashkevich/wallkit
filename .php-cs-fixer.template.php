<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__)
    ->notName('/^(?!.*template).*\.php$/i'); //хитрое условие для ВСЕ PHP файлы где есть template в имени

return (new Config())
    ->setFinder($finder)
    ->setRules([
        "@auto" => true,
        // Запрещаем закрывающие теги
        'no_closing_tag' => true,

        // Используем короткий синтаксис echo
        'echo_tag_syntax' => [
            'format' => 'short',
            'shorten_simple_statements_only' => false,
        ],

        // Удаляем ненужные точки с запятой
        'no_singleline_whitespace_before_semicolons' => true,

        // Удаляем фигурные скобки для простых конструкций
        'braces' => [
            'allow_single_line_closure' => false,
            'position_after_functions_and_oop_constructs' => 'same',
            'position_after_control_structures' => 'same',
            'position_after_anonymous_constructs' => 'same',
        ],

        // Явно указываем использовать альтернативный синтаксис
        'control_structure_braces' => false,

        // Один оператор на строку
        'single_line_throw' => true,
        'no_multiple_statements_per_line' => true,

        // Дополнительные правила для чистоты кода
        'no_trailing_whitespace' => true,
        'no_whitespace_in_blank_line' => true,
        'blank_line_after_opening_tag' => false,
        'line_ending' => true,
    ]);