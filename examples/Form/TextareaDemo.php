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
use OlegV\WallKit\Form\Button\Button;
use OlegV\WallKit\Form\Field\Field;
use OlegV\WallKit\Form\FileUpload\FileUpload;
use OlegV\WallKit\Form\Form\Form;
use OlegV\WallKit\Form\Input\Input;
use OlegV\WallKit\Form\Select\Select;
use OlegV\WallKit\Form\Textarea\Textarea;
use OlegV\WallKit\Navigation\Item\Item;
use OlegV\WallKit\Navigation\Menu\Menu;

BrickManager::enableDebug();
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
        component: new Textarea(name: 'minimal'),
        description: 'Самый простой вариант без дополнительных параметров. Подходит для быстрого прототипирования.',
        badgeText: 'Textarea',
        badgeType: 'textarea',
    ),
    new DemoComponentCard(
        title: 'С плейсхолдером',
        component: new Textarea(
            name: 'description',
            placeholder: 'Опишите вашу идею...',
        ),
        description: 'Текстовое поле с подсказкой внутри. Помогает пользователю понять, что нужно вводить.',
        badgeText: 'Textarea',
        badgeType: 'textarea',
    ),
];

$basicSection = new DemoSection(
    id: 'basic',
    title: 'Базовые примеры',
    description: 'Простые варианты использования компонента Textarea',
    icon: '📝',
    componentCards: $basicCards,
);

// 4. Секция с размерами
$sizesCards = [
    new DemoComponentCard(
        title: 'Маленькое поле (2 строки)',
        component: new Textarea(name: 'short', placeholder: 'Короткий ответ...', rows: 2),
        description: 'Компактное поле для кратких ответов',
        badgeText: 'rows=2',
        badgeType: 'textarea',
    ),
    new DemoComponentCard(
        title: 'Большое поле (10 строк)',
        component: new Textarea(name: 'large', placeholder: 'Подробное описание...', rows: 10),
        description: 'Для длинных текстов, статей, описаний',
        badgeText: 'rows=10',
        badgeType: 'textarea',
    ),
    new DemoComponentCard(
        title: 'test',
        component: new Field(
            input: new Input(
                name: 'username',
                value: 'john_doe',
                id: 'user-field',
            ),
            label: 'Имя пользователя',
        ),
        description: 'test',
        badgeText: 'test',
        badgeType: 'test',
    ),
    new DemoComponentCard(
        title: 'Чекбокс согласия',
        component: [
            new Field(
                input: new Input(
                    name: 'terms',
                    value: 'yes',
                    type: 'checkbox',
                    required: true,
                    checked: true,
                ),
                label: 'Я согласен с условиями использования',
                helpText: 'Обязательно для регистрации',
            ),
            new Field(
                input: new Input(
                    name: 'terms',
                    value: 'no',
                    type: 'checkbox',
                    required: true,
                /*checked: true,*/
                ),
                label: 'Я согласен с условиями использования',
                helpText: 'Обязательно для регистрации',
            ),
        ],
        description: 'Обязательный чекбокс с предварительным выбором',
        badgeText: 'required',
        badgeType: 'danger',
    ),
    new DemoComponentCard(
        title: 'Чекбокс согласия',
        component: [
            new Field(
                input: new Input(
                    name: 'terms',
                    value: 'yes',
                    type: 'checkbox',
                    required: true,
                    checked: true,
                ),
                label: 'Я согласен с условиями использования',
                helpText: 'Обязательно для регистрации',
            ),
            new Input(
                name: 'terms',
                value: 'no',
                type: 'checkbox',
                required: true,
            /*checked: true,*/
            ),
        ],
        description: 'Обязательный чекбокс с предварительным выбором',
        badgeText: 'required',
        badgeType: 'danger',
    ),
    new DemoComponentCard(
        title: 'Выбор темы оформления',
        component: [
            new Field(
                input: new Input(
                    name: 'theme',
                    value: 'light',
                    type: 'radio',
                    checked: true,
                    id: 'theme-light',
                ),
                label: 'Светлая тема',
            ),
            new Field(
                input: new Input(
                    name: 'theme',
                    value: 'dark',
                    type: 'radio',
                    id: 'theme-dark',
                ),
                label: 'Тёмная тема',
            ),
            new Field(
                input: new Input(
                    name: 'theme',
                    value: 'auto',
                    type: 'radio',
                    id: 'theme-auto',
                ),
                label: 'Автоматически',
            ),
        ],
        description: 'Группа радио-кнопок для выбора одной опции',
        badgeText: 'selection',
        badgeType: 'info',
    ),
    new DemoComponentCard(
        title: 'Выбор страны',
        component: new Field(
            input: new Select(
                name: 'country',
                options: [
                    'ru' => 'Россия',
                    'us' => 'США',
                    'de' => 'Германия',
                    'fr' => 'Франция',
                    'jp' => 'Япония',
                ],
                selected: 'ru',
                required: true,
                id: 'country-select',
                placeholder: 'Выберите страну',
            ),
            label: 'Страна проживания',
            helpText: 'Выберите вашу страну из списка',
        ),
        description: 'Простой выпадающий список с обязательным выбором',
        badgeText: 'required',
        badgeType: 'danger',
    ),
    new DemoComponentCard(
        title: 'Выбор автомобиля',
        component: new Field(
            input: new Select(
                name: 'car',
                options: [
                    'Немецкие автомобили' => [
                        'bmw' => 'BMW',
                        'mercedes' => 'Mercedes-Benz',
                        'audi' => 'Audi',
                        'volkswagen' => 'Volkswagen',
                    ],
                    'Японские автомобили' => [
                        'toyota' => 'Toyota',
                        'honda' => 'Honda',
                        'nissan' => 'Nissan',
                        'mazda' => 'Mazda',
                    ],
                    'Американские автомобили' => [
                        'ford' => 'Ford',
                        'chevrolet' => 'Chevrolet',
                        'tesla' => 'Tesla',
                    ],
                ],
                selected: 'toyota',
                id: 'car-select',
                placeholder: 'Выберите марку автомобиля',
            ),
            label: 'Предпочитаемая марка автомобиля',
            helpText: 'Автомобили сгруппированы по стране производства',
        ),
        description: 'Select с группами опций (optgroup)',
        badgeText: 'grouped',
        badgeType: 'info',
    ),
    new DemoComponentCard(
        title: 'Выбор навыков',
        component: new Field(
            input: new Select(
                name: 'skills[]',
                options: [
                    'php' => 'PHP',
                    'javascript' => 'JavaScript',
                    'python' => 'Python',
                    'java' => 'Java',
                    'csharp' => 'C#',
                    'ruby' => 'Ruby',
                    'go' => 'Go',
                    'rust' => 'Rust',
                ],
                selected: ['php', 'javascript'],
                multiple: true,
                id: 'skills-select',
                classes: ['wallkit-select__field--multiple'],
                size: 4,
            ),
            label: 'Профессиональные навыки',
            helpText: 'Выберите один или несколько навыков (удерживайте Ctrl для выбора нескольких)',
        ),
        description: 'Select с множественным выбором и указанием количества видимых строк',
        badgeText: 'multiple',
        badgeType: 'warning',
    ),
    new DemoComponentCard(
        title: 'Выбор валюты (заблокировано)',
        component: new Field(
            input: new Select(
                name: 'currency',
                options: [
                    'rub' => 'Рубли (RUB)',
                    'usd' => 'Доллары (USD)',
                    'eur' => 'Евро (EUR)',
                    'cny' => 'Юани (CNY)',
                ],
                selected: 'rub',
                disabled: true,
                id: 'currency-select',
            ),
            label: 'Валюта платежа',
            helpText: 'Валюта не может быть изменена после создания заказа',
        ),
        description: 'Заблокированный Select с пояснением',
        badgeText: 'disabled',
        badgeType: 'secondary',
    ),
    new DemoComponentCard(
        title: 'Маленький Select',
        component: new Field(
            input: new Select(
                name: 'priority',
                options: [
                    'low' => 'Низкий',
                    'medium' => 'Средний',
                    'high' => 'Высокий',
                    'critical' => 'Критический',
                ],
                selected: 'medium',
                id: 'priority-select',
                classes: ['wallkit-select__field--sm'],
            ),
            label: 'Приоритет задачи',
            wrapperClasses: ['mb-2'],
        ),
        description: 'Select с малым размером (sm)',
        badgeText: 'sm',
        badgeType: 'info',
    ),
    new DemoComponentCard(
        title: 'Большой Select',
        component: new Field(
            input: new Select(
                name: 'department',
                options: [
                    'sales' => 'Отдел продаж',
                    'marketing' => 'Маркетинг',
                    'development' => 'Разработка',
                    'support' => 'Техническая поддержка',
                    'hr' => 'HR',
                ],
                required: true,
                id: 'department-select',
                classes: ['wallkit-select__field--lg'],
                placeholder: 'Выберите отдел',
            ),
            label: 'Отдел компании',
            helpText: 'Выберите отдел, к которому относится запрос',
        ),
        description: 'Select с большим размером (lg)',
        badgeText: 'lg',
        badgeType: 'info',
    ),
    new DemoComponentCard(
        title: 'Outline Select',
        component: new Field(
            input: new Select(
                name: 'theme',
                options: [
                    'light' => 'Светлая тема',
                    'dark' => 'Тёмная тема',
                    'auto' => 'Автоматически',
                ],
                selected: 'auto',
                id: 'theme-select',
                classes: ['wallkit-select__field--outline'],
            ),
            label: 'Тема оформления',
        ),
        description: 'Select с контурным стилем (outline)',
        badgeText: 'outline',
        badgeType: 'secondary',
    ),

    new DemoComponentCard(
        title: 'Filled Select',
        component: new Field(
            input: new Select(
                name: 'language',
                options: [
                    'ru' => 'Русский',
                    'en' => 'Английский',
                    'de' => 'Немецкий',
                    'fr' => 'Французский',
                ],
                selected: 'ru',
                id: 'language-select',
                classes: ['wallkit-select__field--filled'],
            ),
            label: 'Язык интерфейса',
        ),
        description: 'Select с заполненным фоном (filled)',
        badgeText: 'filled',
        badgeType: 'secondary',
    ),
    new DemoComponentCard(
        title: 'Select с ошибкой',
        component: new Field(
            input: new Select(
                name: 'payment_method',
                options: [
                    'card' => 'Банковская карта',
                    'cash' => 'Наличные',
                    'transfer' => 'Банковский перевод',
                    'paypal' => 'PayPal',
                ],
                required: true,
                id: 'payment-select',
                placeholder: 'Выберите способ оплаты',
            ),
            label: 'Способ оплаты',
            error: 'Необходимо выбрать способ оплаты',
            wrapperClasses: ['wallkit-field--error'],
        ),
        description: 'Select с сообщением об ошибке валидации',
        badgeText: 'error',
        badgeType: 'danger',
    ),
    new DemoComponentCard(
        title: 'Форма фильтров',
        component: [
            new Field(
                input: new Select(
                    name: 'category',
                    options: [
                        'electronics' => 'Электроника',
                        'clothing' => 'Одежда',
                        'books' => 'Книги',
                        'home' => 'Товары для дома',
                    ],
                    id: 'category-filter',
                    placeholder: 'Все категории',
                ),
                label: 'Категория товаров',
            ),
            new Field(
                input: new Select(
                    name: 'sort_by',
                    options: [
                        'price_asc' => 'Цена по возрастанию',
                        'price_desc' => 'Цена по убыванию',
                        'popularity' => 'По популярности',
                        'newest' => 'Сначала новые',
                    ],
                    selected: 'popularity',
                    id: 'sort-filter',
                ),
                label: 'Сортировка',
            ),
            new Field(
                input: new Select(
                    name: 'limit',
                    options: [
                        10 => '10 товаров',
                        25 => '25 товаров',
                        50 => '50 товаров',
                        100 => '100 товаров',
                    ],
                    selected: 25,
                    id: 'limit-filter',
                    classes: ['wallkit-select__field--sm'],
                ),
                label: 'Товаров на странице',
            ),
        ],
        description: 'Несколько Select-полей для фильтрации товаров',
        badgeText: 'filters',
        badgeType: 'primary',
    ),
    new DemoComponentCard(
        title: 'Простая контактная форма',
        component: new Form(
            fields: [
                new Field(new Input(name: 'name', required: true), label: 'Ваше имя'),
                new Field(new Input(name: 'email', type: 'email', required: true), label: 'Email'),
                new Field(new Textarea(name: 'message', rows: 4, required: true), label: 'Сообщение'),
                new Button('Отправить сообщение', type: 'submit', variant: 'primary'),
            ],
            action: '/contact',
            method: 'POST',
            csrfToken: 'qwerty',
        ),
        description: 'Простая контактная форма',
        badgeText: 'form',
        badgeType: 'form',
    ),
    new DemoComponentCard(
        title: 'Форма входа',
        component: new Form(
            fields: [
                new Field(
                    input: new Input(name: 'email', type: 'email'),
                    label: 'Email',
                    helpText: 'Введите ваш email',
                ),
                new Field(
                    input: new Input(name: 'password', type: 'password'),
                    label: 'Пароль',
                    withPasswordToggle: true,
                ),
                new Button('Войти', type: 'submit'),
            ],
            action: '/login',
            method: 'POST',
        ),
        description: 'Форма входа',
        badgeText: 'form',
        badgeType: 'form',
    ),
    new DemoComponentCard(
        title: 'Загрузка изображений с ограничениями',
        component: new Form(
            fields: [
                new FileUpload(
                    name: 'avatar',
                    label: 'Аватар профиля',
                    accept: 'image/*',
                    maxSize: 5 * 1024 * 1024, // 5MB
                    maxWidth: 800,
                    maxHeight: 600,
                    helpText: 'Максимальный размер: 5MB, размер: до 800×600px',
                ),
                new Field(input: new Input(name: 'email', type: 'email'), label: 'Email'),
                new Field(input: new Input(name: 'url', type: 'url'), label: 'Url'),
                new Button('Войти', type: 'submit'),
            ],
            action: '/login',
            method: 'POST',
        ),
        description: 'Загрузка изображений с ограничениями',
        badgeText: 'form',
        badgeType: 'form',
    ),
    new DemoComponentCard(
        title: 'Множественная загрузка',
        component: new Form(
            fields: [
                new FileUpload(
                    name: 'gallery',
                    label: 'Галерея изображений',
                    placeholder: 'Перетащите файлы сюда или нажмите для выбора',
                    multiple: true,
                    maxSize: 10 * 1024 * 1024,
                    maxFiles: 10,
                ),
                new Button('Войти', type: 'submit'),
            ],
            action: '/login',
            method: 'POST',
        ),
        description: 'Множественная загрузка',
        badgeText: 'form',
        badgeType: 'form',
    ),
    new DemoComponentCard(
        title: 'Горизонтальное меню (Navbar)',
        component: new Menu(
            items: [
                Item::link('Главная', '/', '🏠', active: true),
                Item::link('О компании', '/about'),
                Item::parent('Услуги', [
                    Item::link('Веб-разработка', '/services/web'),
                    Item::link('Мобильные приложения', '/services/mobile'),
                    Item::link('UI/UX дизайн', '/services/design'),
                ], '🎯'),
                Item::link('Контакты', '/contact'),
                Item::action('Войти', 'login', '🔑'),
            ],
            orientation: 'horizontal',
            variant: 'navbar',
            position: 'top',
            brand: 'WallKit Demo',
        ),
        description: 'Горизонтальное меню (Navbar)',
        badgeText: 'Navbar',
        badgeType: 'Navbar',
    ),
    new DemoComponentCard(
        title: 'Вертикальное меню (Sidebar)',
        component: new Menu(
            items: [
                Item::link('Дашборд', '/dashboard', '📊', active: true),
                Item::parent('Контент', [
                    Item::link('Статьи', '/articles'),
                    Item::link('Категории', '/categories'),
                    Item::link('Медиа', '/media'),
                ], '📝'),
                Item::parent('Пользователи', [
                    Item::link('Все пользователи', '/users'),
                    Item::link('Роли', '/roles'),
                    Item::link('Разрешения', '/permissions'),
                ], '👥'),
                Item::divider(),
                Item::header('Настройки'),
                Item::link('Общие настройки', '/settings', '⚙️'),
                Item::action('Выйти', 'logout', '🚪', danger: true),
            ],
            orientation: 'vertical',
            variant: 'sidebar',
            position: 'left',
            collapsible: false,
        ),
        description: 'Вертикальное меню (Sidebar)',
        badgeText: 'Sidebar',
        badgeType: 'Sidebar',
    ),
    new DemoComponentCard(
        title: 'Выпадающее меню',
        component: new Menu(
            items: [
                Item::link('Профиль', '/profile', '👤'),
                Item::link('Настройки', '/settings', '⚙️'),
                Item::divider(),
                Item::action('Выйти', 'logout', '🚪', danger: true),
            ],
            orientation: 'vertical',
            variant: 'dropdown',
            position: 'bottom',
            trigger: 'click',
        ),
        description: 'Выпадающее меню',
        badgeText: 'dropdown',
        badgeType: 'dropdown',
    ),
    new DemoComponentCard(
        title: 'Context меню',
        component: new Menu(
            items: [
                Item::link('Профиль', '/profile', '👤'),
                Item::link('Настройки', '/settings', '⚙️'),
                Item::divider(),
                Item::action('Выйти', 'logout', '🚪', danger: true),
            ],
            orientation: 'vertical',
            variant: 'context',
            position: 'bottom',
            trigger: 'click',
        ),
        description: 'Выпадающее меню',
        badgeText: 'Context',
        badgeType: 'Context',
    ),
];

$sizesSection = new DemoSection(
    id: 'sizes',
    title: 'Размеры и строки',
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
    title: 'Реальные примеры',
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
          behavior: 'smooth',
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