<?php

// examples/index.php
require __DIR__.'/../vendor/autoload.php';

use OlegV\BrickManager;
use OlegV\WallKit\Demo\DemoComponentGrid\DemoComponentGrid;
use OlegV\WallKit\Demo\DemoHeader\DemoHeader;
use OlegV\WallKit\Demo\DemoLayout\DemoLayout;
use OlegV\WallKit\Demo\DemoSidebar\DemoSidebar;
use OlegV\WallKit\Demo\DemoStats\DemoStats;

// Загружаем метаданные компонентов
$componentsData = json_decode(file_get_contents(__DIR__.'/components.json'), true);

// 1. Статистика
$stats = new DemoStats(
    totalComponents: count($componentsData['components']),
    stableComponents: count(array_filter($componentsData['components'], fn($c) => $c['status'] === 'stable')),
    plannedComponents: count(array_filter($componentsData['components'], fn($c) => $c['status'] === 'planned')),
    demoPages: count(array_filter($componentsData['components'], fn($c) => !empty($c['demoFile']))),
    latestVersion: '1.0.0',
);

// 2. Заголовок
$header = new DemoHeader(
    title: 'WallKit UI Components',
    subtitle: 'Библиотека готовых UI компонентов на PHP с SSR-подходом, строгой типизацией и иммутабельностью.',
    icon: '🧩',
);
// 3. Боковая панель
$sidebar = new DemoSidebar(
    navItems: array_merge([
        ['title' => 'Все компоненты', 'href' => '#components', 'icon' => '🧩', 'active' => true],
        ...array_map(function ($item) {
            return [
                'title' => $item['title'],
                'href' => '#'.strtolower($item['name']),
                'icon' => $item['icon'],
                'active' => false,
            ];
        }, $componentsData['groups']),
        [
            'title' => 'Документация',
            'href' => 'https://github.com/OlegVashkevich/wallkit/tree/master/docs',
            'icon' => '📚',
            'active' => false,
        ],
        [
            'title' => 'GitHub',
            'href' => 'https://github.com/OlegVashkevich/wallkit',
            'icon' => '🐙',
            'active' => false,
        ],
    ]),
    infoCards: [
        [
            'title' => 'Установка',
            'content' => 'composer require olegv/wallkit',
            'icon' => '📦',
        ],
        [
            'title' => 'Философия',
            'content' => 'Простота, типизация, SSR. UI без тяжёлых JS-фреймворков.',
            'icon' => '🎯',
        ],
    ],
    title: 'Быстрые ссылки',
);

// 4. Сетка компонентов
$componentGrid = new DemoComponentGrid(
    components: $componentsData['components'],
    groups: $componentsData['groups'],
    showGroups: true,
    showStatus: true,
);

// 5. Собираем контент
$content = implode('', [
    $stats,
    $componentGrid,
]);

// 6. Layout
$layout = new DemoLayout(
    sidebar: (string)$sidebar,
    content: $content,
);

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WallKit UI Components - Демо и примеры</title>
    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--wk-font-family), serif;
            color: var(--wk-dark-gray);
            line-height: 1.5;
            min-height: 100vh;
            background: var(--wk-white) linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .demo-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 var(--wk-spacing-6);
        }
    </style>
</head>
<body>
<div class="demo-container">
    <?= $header ?>
    <?= $layout ?>
</div>
<!-- Подключение стилей и скриптов компонента -->
<?php
echo BrickManager::getInstance()->renderAssets(); ?>
<script>


    // Подсветка текущей группы при скролле
    const groups = document.querySelectorAll('.wallkit-demo-component-grid__group');
    const navItems = document.querySelectorAll('.wallkit-demo-sidebar__nav-item');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const groupName = entry.target.querySelector('.wallkit-demo-component-grid__group-title')?.textContent;
                if (groupName) {
                    navItems.forEach(item => {
                        item.classList.remove('wallkit-demo-sidebar__nav-item--active');
                        if (item.textContent.includes(groupName.trim())) {
                            item.classList.add('wallkit-demo-sidebar__nav-item--active');
                        }
                    });
                }
            }
        });
    }, {threshold: 0.5});

    groups.forEach(group => observer.observe(group));


</script>
</body>
</html>