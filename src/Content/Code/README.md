# Компонент Code

Компонент для отображения блоков исходного кода с поддержкой подсветки синтаксиса, нумерации строк и копирования в буфер обмена. Реализует отображение кода и кастомизацией через CSS-переменные.

## Характеристики

| Параметр | Значение |
|----------|----------|
| **Тип компонента** | Контентный элемент |
| **Иммутабельность** | Да (readonly) |
| **Рендеринг** | SSR (серверный) |
| **Типизация** | Strict PHP 8.2+ |
| **CSS префикс** | `wallkit-` |
| **Зависимости** | Base, WithInheritance (и опционально scrivo/highlight.php для подсветки) |

## Поддерживаемые языки

Компонент поддерживает все языки программирования, доступные в библиотеке [scrivo/highlight.php](https://github.com/scrivo/highlight.php). Включает более 190 популярных языков:

- **Веб-разработка**: HTML, CSS, JavaScript, TypeScript, PHP, Python
- **Базы данных**: SQL, MongoDB, GraphQL
- **Мобильная разработка**: Swift, Kotlin, Java
- **Системное программирование**: C, C++, Rust, Go
- **Другие**: JSON, YAML, Markdown, Bash, Dockerfile

## Свойства компонента

| Свойство | Тип | По умолчанию | Описание |
|----------|-----|--------------|----------|
| `content` | `string` | — | Исходный код для отображения (обязательно) |
| `language` | `string` | `plaintext` | Язык программирования |
| `highlight` | `bool` | `false` | Включить подсветку синтаксиса |
| `lineNumbers` | `bool` | `false` | Показать номера строк |
| `copyButton` | `bool` | `false` | Показать кнопку копирования |
| `showLanguage` | `bool` | `false` | Показать метку языка |

## Использование

### Базовое использование
```php
use OlegV\WallKit\Content\Code\Code;

$code = new Code(
    content: '<?php echo "Hello, World!"; ?>',
    language: 'php'
);
echo $code;
```

### С подсветкой синтаксиса
```php
$code = new Code(
    content: 'function sum(a, b) { return a + b; }',
    language: 'javascript',
    highlight: true
);
```

### Полный функционал
```php
$code = new Code(
    content: 'SELECT * FROM users WHERE active = true;',
    language: 'sql',
    highlight: true,
    lineNumbers: true,
    copyButton: true,
    showLanguage: true
);
```

## Зависимости

### Обязательные
- PHP 8.2+
- WallKit Base

### Опциональные (для подсветки синтаксиса)
```bash
composer require scrivo/highlight.php
```

Если библиотека не установлена, компонент будет работать в режиме простого текста с автоматическим экранированием HTML.

## Стилизация

### CSS-классы
| Класс | Назначение |
|-------|------------|
| `.wallkit-code` | Основной контейнер |
| `.wallkit-code__header` | Заголовок блока кода |
| `.wallkit-code__language` | Метка языка программирования |
| `.wallkit-code__copy-button` | Кнопка копирования |
| `.wallkit-code__content` | Контейнер содержимого |
| `.wallkit-code__lines` | Блок с номерами строк |
| `.wallkit-code__line-number` | Отдельный номер строки |

### CSS-переменные
Компонент использует переменные из базовой темы:
- `--wk-color-*` — цвета (особенно `gray-*` для темной темы)
- `--wk-spacing-*` — отступы
- `--wk-font-*` — типографика
- `--wk-border-radius-*` — радиусы скругления
- `--wk-shadow-*` — тени
- `--wk-transition-*` — анимации

### Темы
Компонент поддерживает автоматическое переключение тем через атрибут `data-theme`:
```html
<!-- Темная тема (по умолчанию) -->
<div class="wallkit-code" data-language="php">

<!-- Светлая тема -->
<body data-theme="light">
    <div class="wallkit-code" data-language="php">
```

## JavaScript функциональность

### Копирование в буфер обмена
При включении опции `copyButton: true` добавляется функциональность копирования кода:
- Использует современный Clipboard API
- Обеспечивает визуальную обратную связь (смена текста кнопки)
- Работает в большинстве современных браузеров
- Автоматически экранирует HTML для безопасного копирования

## Примеры вывода

### Простой блок
```html
<div class="wallkit-code" data-language="php">
    <pre><code>&lt;?php echo "Hello"; ?&gt;</code></pre>
</div>
```

### С подсветкой
```html
<div class="wallkit-code" data-language="php">
    <pre><code class="hljs language-php">
        <span class="hljs-meta">&lt;?php</span> 
        <span class="hljs-keyword">echo</span> 
        <span class="hljs-string">"Hello"</span>;
    </code></pre>
</div>
```

## Совместимость

| Система | Статус | Примечания |
|---------|--------|------------|
| **PHP** | 8.2+ | Strict типизация, readonly классы |
| **HTML** | 5+ | Семантическая разметка |
| **CSS** | 3+ | CSS-переменные, Flexbox |
| **JavaScript** | ES6+ | Clipboard API, прогрессивное улучшение |

## Ссылки
- [Базовый класс Base](../Base/README.md)
- [Документация по Content компонентам](../README.md)
- [Руководство по стилизации](../../../styling.md)
- [Библиотека highlight.php](https://github.com/scrivo/highlight.php)

---

**Версия:** 1.0.0  
**Автор:** OlegV  
**Лицензия:** MIT