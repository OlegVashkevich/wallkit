# Компонент TagCloud

Компонент для отображения облака тегов. Размер каждого тега зависит от его веса, поддерживает различные форматы входных данных и методы сортировки.

## Характеристики

| Параметр | Значение |
|----------|----------|
| **Тип компонента** | Контентный элемент |
| **Иммутабельность** | Да (readonly) |
| **Рендеринг** | SSR (серверный) |
| **Типизация** | Strict PHP 8.2+ |
| **CSS префикс** | `wallkit-` |
| **JavaScript** | Интерактивная фильтрация |
| **Зависимости** | Base, WithInheritance |

## Свойства компонента

| Свойство | Тип | По умолчанию | Описание |
|----------|-----|--------------|----------|
| `tags` | `array` | — | Массив тегов (обязательно) |
| `sortBy` | `string` | `random` | Метод сортировки: `weight`, `alphabet`, `random` |
| `autoSize` | `bool` | `true` | Автоматическое определение размера на основе веса |

## Форматы входных данных

### Простые строки
```php
$tags = ['PHP', 'JavaScript', 'HTML', 'CSS', 'Python'];
```

### Теги с URL
```php
$tags = [
    'PHP' => '/tags/php',
    'JavaScript' => '/tags/js',
    'HTML' => '/tags/html',
];
```

### Теги с весом и URL
```php
$tags = [
    'PHP' => ['url' => '/tags/php', 'weight' => 10],
    'JavaScript' => ['url' => '/tags/js', 'weight' => 8],
    'HTML' => ['weight' => 5], // без URL
];
```

## Использование

### Базовое использование
```php
use OlegV\WallKit\Content\TagCloud\TagCloud;

$tags = ['PHP', 'JavaScript', 'HTML', 'CSS', 'Python'];
$cloud = new TagCloud(tags: $tags);
echo $cloud;
```

### Сортировка по весу
```php
$tags = [
    'PHP' => ['weight' => 10],
    'JavaScript' => ['weight' => 8],
    'HTML' => ['weight' => 5],
];
$cloud = new TagCloud(
    tags: $tags,
    sortBy: 'weight',
    autoSize: true
);
echo $cloud;
```

### Сортировка по алфавиту
```php
$cloud = new TagCloud(
    tags: $tags,
    sortBy: 'alphabet',
    autoSize: true
);
echo $cloud;
```

## Стилизация

### CSS-классы
| Класс | Назначение |
|-------|------------|
| `.wallkit-tagcloud` | Контейнер облака тегов |
| `.wallkit-tagcloud__tag` | Базовый стиль тега |
| `.wallkit-tagcloud__tag--xs` | Очень маленький размер |
| `.wallkit-tagcloud__tag--sm` | Маленький размер |
| `.wallkit-tagcloud__tag--base` | Базовый размер |
| `.wallkit-tagcloud__tag--lg` | Большой размер |
| `.wallkit-tagcloud__tag--xl` | Очень большой размер |
| `.wallkit-tagcloud__tag--active` | Активный тег фильтра |

### CSS-переменные
Компонент использует переменные из базовой темы:
- `--wk-color-*` — цвета
- `--wk-spacing-*` — отступы
- `--wk-font-size-*` — размеры шрифтов
- `--wk-font-weight-*` — начертания шрифтов
- `--wk-border-radius` — радиус скругления
- `--wk-shadow-*` — тени
- `--wk-transition-fast` — быстрые анимации

## JavaScript-функциональность

### Фильтрация по хэш-ссылкам
Теги с хэш-ссылками (`#tag-name`) обеспечивают интерактивную фильтрацию:
- Клик по тегу фильтрует элементы с `data-tag` атрибутом
- Поддерживает множественный выбор (И-логика)
- Отправляет события через `WallKitEvents`

### Система событий
```javascript
// Регистрация событий
WallKitEvents.register('wallkit:tagcloud:tag:click');
WallKitEvents.register('wallkit:tagcloud:filter');

// Подписка на события
WallKitEvents.on('wallkit:tagcloud:tag:click', (data) => {
    console.log('Клик по тегу:', data.label);
});

WallKitEvents.on('wallkit:tagcloud:filter', (data) => {
    console.log('Активные фильтры:', data.filters);
});
```

## Пример фильтрации

```html
<!-- Облако тегов -->
<div class="wallkit-tagcloud">
    <a href="#php" class="wallkit-tagcloud__tag wallkit-tagcloud__tag--lg">PHP</a>
    <a href="#javascript" class="wallkit-tagcloud__tag wallkit-tagcloud__tag--base">JavaScript</a>
</div>

<!-- Фильтруемые элементы -->
<div data-tag="php javascript">Содержимое про PHP и JavaScript</div>
<div data-tag="php">Содержимое только про PHP</div>
<div data-tag="javascript">Содержимое только про JavaScript</div>
```

## Совместимость

| Система | Статус | Примечания |
|---------|--------|------------|
| **PHP** | 8.2+ | Strict типизация, readonly классы |
| **HTML** | 5+ | Семантическая разметка |
| **CSS** | 3+ | CSS-переменные, Flexbox |
| **JavaScript** | ES6+ | Прогрессивное улучшение |

## Ссылки
- [Базовый класс Base](../Base/README.md)
- [Компонент Input](../../Form/Input/README.md)
- [Руководство по стилизации](../../../styling.md)

---

**Версия:** 1.0.0  
**Автор:** OlegV  
**Лицензия:** MIT