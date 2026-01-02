<?php

// examples/Form/TextareaDemo.php
require __DIR__.'/../../vendor/autoload.php';

use OlegV\BrickManager;
use OlegV\WallKit\Demo\DemoComponentCard\DemoComponentCard;
use OlegV\WallKit\Demo\DemoFormExample\DemoFormExample;
use OlegV\WallKit\Demo\DemoHeader\DemoHeader;
use OlegV\WallKit\Demo\DemoLayout\DemoLayout;
use OlegV\WallKit\Demo\DemoSection\DemoSection;
use OlegV\WallKit\Demo\DemoSidebar\DemoSidebar;
use OlegV\WallKit\Form\Field\Field;
use OlegV\WallKit\Form\Textarea\Textarea;

// 1. Заголовок
$header = new DemoHeader(
    title: 'WallKit Textarea Component',
    subtitle: 'Полное руководство по использованию компонента Textarea. Многострочные текстовые поля с полной типизацией и валидацией.',
    icon: '📝',
);

// 2. Боковая панель
$sidebar = new DemoSidebar(
    navItems: [
        ['title' => 'Базовые примеры', 'href' => '#basic', 'icon' => '📝', 'active' => true],
        ['title' => 'Размеры и строки', 'href' => '#sizes', 'icon' => '📏', 'active' => false],
        ['title' => 'Валидация', 'href' => '#validation', 'icon' => '✅', 'active' => false],
        ['title' => 'С обёрткой Field', 'href' => '#with-field', 'icon' => '🎁', 'active' => false],
        ['title' => 'Реальные примеры', 'href' => '#real-world', 'icon' => '🌍', 'active' => false],
    ],
    infoCards: [
        [
            'title' => 'О компоненте',
            'content' => 'Textarea — компонент для ввода многострочного текста. Поддерживает все стандартные атрибуты HTML textarea, строгую типизацию и интеграцию с PHPStan.',
            'icon' => 'ℹ️',
        ],
    ],
    title: 'Навигация',
);

// 3. Секция с базовыми примерами
$basicCards = [
    new DemoComponentCard(
        title: 'Минимальный Textarea',
        componentHtml: (string)new Textarea(name: 'minimal'),
        description: 'Самый простой вариант без дополнительных параметров. Подходит для быстрого прототипирования.',
        code: "new Textarea(\n    name: 'minimal'\n)",
        badgeText: 'Textarea',
        badgeType: 'textarea',
    ),
    new DemoComponentCard(
        title: 'С плейсхолдером',
        componentHtml: (string)new Textarea(
            name: 'description',
            placeholder: 'Опишите вашу идею...',
        ),
        description: 'Текстовое поле с подсказкой внутри. Помогает пользователю понять, что нужно вводить.',
        code: "new Textarea(\n    name: 'description',\n    placeholder: 'Опишите вашу идею...'\n)",
        badgeText: 'Textarea',
        badgeType: 'textarea',
    ),
];

$basicSection = new DemoSection(
    id: 'basic',
    title: '📝 Базовые примеры',
    description: 'Простые варианты использования компонента Textarea',
    icon: '📝',
    componentCards: $basicCards,
);

// 4. Секция с размерами
$sizesCards = [
    new DemoComponentCard(
        title: 'Маленькое поле (2 строки)',
        componentHtml: (string)new Textarea(name: 'short', placeholder: 'Короткий ответ...', rows: 2),
        description: 'Компактное поле для кратких ответов',
        code: "new Textarea(\n    name: 'short',\n    rows: 2,\n    placeholder: 'Короткий ответ...'\n)",
        badgeText: 'rows=2',
        badgeType: 'textarea',
    ),
    new DemoComponentCard(
        title: 'Большое поле (10 строк)',
        componentHtml: (string)new Textarea(name: 'large', placeholder: 'Подробное описание...', rows: 10),
        description: 'Для длинных текстов, статей, описаний',
        code: "new Textarea(\n    name: 'large',\n    rows: 10,\n    placeholder: 'Подробное описание...'\n)",
        badgeText: 'rows=10',
        badgeType: 'textarea',
    ),
];

$sizesSection = new DemoSection(
    id: 'sizes',
    title: '📏 Размеры и строки',
    description: 'Управление размерами и количеством строк',
    icon: '📏',
    componentCards: $sizesCards,
);

// 5. Демо-форма
$formExample = new DemoFormExample(
    title: 'Форма обратной связи',
    description: 'Пример реального использования Textarea в форме обратной связи',
    formHtml: (string)new Field(
        input: new Textarea(
            name: 'message',
            placeholder: 'Опишите вашу проблему или вопрос...',
            rows: 6,
            required: true,
            id: 'message-field',
        ),
        label: 'Сообщение',
        helpText: 'Будьте максимально подробны',
    ),
    actions: [
        ['text' => 'Отправить сообщение', 'variant' => 'primary', 'icon' => '📨'],
        ['text' => 'Очистить форму', 'variant' => 'secondary', 'icon' => '🗑️'],
    ],
    notes: [
        'tip' => 'Используйте Textarea для любых многострочных текстовых полей',
        'info' => 'Все поля валидируются на стороне сервера и клиента',
    ],
);

$realWorldSection = new DemoSection(
    id: 'real-world',
    title: '🌍 Реальные примеры',
    description: 'Примеры использования в реальных сценариях',
    icon: '🌍',
    extraContent: (string)$formExample,
);

// 6. Собираем контент
$content = implode('', [
    $basicSection,
    $sizesSection,
    $realWorldSection,
]);

// 7. Создаем layout
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
    <title>WallKit Textarea Component - UI демо</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--wk-font-family), serif;
            color: var(--wk-dark-gray);
            background-color: var(--wk-white);
            line-height: 1.5;
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
    // Навигация по секциям
    document.querySelectorAll('.wallkit-demo-sidebar__nav-item').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);
            targetSection.offsetTop = undefined;

            if (targetSection) {
                window.scrollTo({
                    top: targetSection.offsetTop - 100,
                    behavior: 'smooth'
                });

                // Обновляем активную ссылку
                document.querySelectorAll('.wallkit-demo-sidebar__nav-item')
                    .forEach(a => a.classList.remove('wallkit-demo-sidebar__nav-item--active'));
                this.classList.add('wallkit-demo-sidebar__nav-item--active');
            }
        });
    });

    // Демо отправки формы
    document.querySelector('.wallkit-demo-form-example__action--primary')?.addEventListener('click', function (e) {
        e.preventDefault();
        alert('✅ Форма обратной связи отправлена! (Демо-режим)');
    });

    // Автоматическое отслеживание активной секции
    const sections = document.querySelectorAll('.wallkit-demo-section');
    const navLinks = document.querySelectorAll('.wallkit-demo-sidebar__nav-item');

    window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            if (scrollY >= (sectionTop - 150)) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            const href = link.getAttribute('href').substring(1);
            link.classList.remove('wallkit-demo-sidebar__nav-item--active');
            if (href === current) {
                link.classList.add('wallkit-demo-sidebar__nav-item--active');
            }
        });
    });
</script>
</body>
</html>