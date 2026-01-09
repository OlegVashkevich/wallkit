<?php

// examples/index.php
require __DIR__.'/../vendor/autoload.php';

use OlegV\BrickManager;
use OlegV\WallKit\Content\TagCloud\TagCloud;

BrickManager::enableDebug();
$tags = [
    // Теги с URL для фильтрации (hash-ссылки)
    'Все' => '#all',
    'PHP' => '#php',
    'JavaScript' => '#js',
    'CSS' => '#css',
    'Базы данных' => '#database',
    'Laravel' => '#laravel',
    'React' => '#react',

    // Пример тега с дополнительными параметрами (вес 5)
    'TypeScript' => [
        'url' => '#typescript',
        'weight' => 5,
    ],

    // Пример тега без ссылки (просто текст)
    'Архив' => null,
    'Персональный блог',
    'Путешествия',
    'Рецепты',

    // Еще один тег с большим весом (будет крупнее)
    'Vue.js' => [
        'url' => '#vue',
        'weight' => 8,
    ],
];
$TagCloud = new TagCloud($tags);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Пример работы TagCloud — WallKit</title>
    <style>
        /* Пример контента для фильтрации */
        .filterable-content {
            margin-top: 2rem;
            padding: 1rem;
            background: var(--wk-color-gray-100);
            border-radius: var(--wk-border-radius);
        }

        .filterable-item {
            padding: 1rem;
            margin: 0.5rem 0;
            background: white;
            border: 1px solid var(--wk-color-gray-300);
            border-radius: var(--wk-border-radius);
        }

        .filterable-item[hidden] {
            display: none;
        }
    </style>
</head>
<body>
<h1>Облако тегов с фильтрацией</h1>

<!-- Облако тегов -->
<?= $TagCloud ?>

<!-- Фильтруемый контент -->
<div class="filterable-content">
    <div data-tag="php laravel" class="filterable-item">
        <h3>PHP и Laravel</h3>
        <p>Статья о разработке на PHP с использованием фреймворка Laravel.</p>
    </div>
    <div data-tag="js react" class="filterable-item">
        <h3>JavaScript и React</h3>
        <p>Введение в React и современный JavaScript.</p>
    </div>
    <div data-tag="css" class="filterable-item">
        <h3>CSS Grid и Flexbox</h3>
        <p>Современные техники вёрстки на CSS.</p>
    </div>
    <div data-tag="php database" class="filterable-item">
        <h3>PHP и базы данных</h3>
        <p>Работа с MySQL и Eloquent в PHP.</p>
    </div>
    <div data-tag="js css" class="filterable-item">
        <h3>JavaScript + CSS анимации</h3>
        <p>Создание интерактивных анимаций с помощью JS и CSS.</p>
    </div>
    <div data-tag="laravel database" class="filterable-item">
        <h3>Laravel и миграции</h3>
        <p>Работа с миграциями баз данных в Laravel.</p>
    </div>
</div>

<!-- Пример обработки событий -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (!window.WallKitEvents) {
            console.warn('WallKitEvents не загружен');
            return;
        }

        // Подписка на клик по тегу
        const sub1 = WallKitEvents.on('wallkit:tagcloud:tag:click', (data) => {
            console.log('Тег кликнут:', data);
        });

        // Подписка на изменение фильтра
        const sub2 = WallKitEvents.on('wallkit:tagcloud:filter', (data) => {
            console.log('Активные фильтры:', data.filters);
            console.log('Показано элементов:',
                document.querySelectorAll('.filterable-item:not([hidden])').length
            );
        });

        // Для тестирования: очистка подписок при закрытии страницы
        window.addEventListener('beforeunload', () => {
            WallKitEvents.off(sub1);
            WallKitEvents.off(sub2);
        });
    });
</script>
<?= BrickManager::getInstance()->renderAssets(); ?>
</body>
</html>