# Компонент Input

Базовый компонент поля ввода текстовых данных. Реализует стандартный HTML-элемент `<input>` с полной типобезопасностью на PHP и кастомизацией через CSS-переменные.

## Характеристики

| Параметр | Значение |
|----------|----------|
| **Тип компонента** | Базовый элемент формы |
| **Иммутабельность** | Да (readonly) |
| **Рендеринг** | SSR (серверный) |
| **Типизация** | Strict PHP 8.2+ |
| **CSS префикс** | `wallkit-` |
| **Зависимости** | Base, WithHelpers, WithStrictHelpers, WithInheritance |

## Поддерживаемые типы полей

| Тип | Назначение | Валидация |
|-----|------------|-----------|
| `text` | Текстовое поле | Базовый |
| `email` | Адрес электронной почты | Формат email |
| `password` | Пароль | Маскировка символов |
| `number` | Числовое значение | Min/max/step |
| `tel` | Телефонный номер | Паттерны |
| `url` | Веб-адрес | Формат URL |
| `date` | Дата | Дата |
| `time` | Время | Время |
| `datetime-local` | Дата и время | Локальное время |
| `month` | Месяц | MM-YYYY |
| `week` | Неделя | Неделя года |
| `range` | Диапазон | Ползунок |
| `color` | Цвет | Цветовой пикер |
| `search` | Поиск | Поисковое поле |
| `file` | Файл | Загрузка файлов |
| `hidden` | Скрытое поле | Скрытый ввод |
| `radio` | Радио-кнопка (выбор одного из группы) | Группировка по name |
| `checkbox` | Флажок (множественный выбор) | Группировка по name |

## Свойства компонента

| Свойство | Тип | По умолчанию | Описание |
|----------|-----|--------------|----------|
| `name` | `string` | — | Имя поля (обязательно) |
| `type` | `string` | `text` | Тип поля ввода |
| `value` | `?string` | `null` | Текущее значение |
| `placeholder` | `?string` | `null` | Текст-подсказка |
| `required` | `bool` | `false` | Обязательность заполнения |
| `disabled` | `bool` | `false` | Отключенное состояние |
| `readonly` | `bool` | `false` | Только чтение |
| `id` | `?string` | `null` | HTML ID (автогенерация) |
| `autoFocus` | `bool` | `false` | Автофокус при загрузке |
| `pattern` | `?string` | `null` | Регулярное выражение |
| `min`/`max` | `?string` | `null` | Минимальное/максимальное значение |
| `minLength`/`maxLength` | `?int` | `null` | Длина строки |
| `step` | `?string` | `null` | Шаг для числовых полей |
| `autocomplete` | `?string` | `null` | Автозаполнение |
| `spellcheck` | `?bool` | `null` | Проверка орфографии |
| `checked` | `bool` | `false` | Отмечен ли элемент (для radio/checkbox) |

## Использование

### Базовое использование
```php
use OlegV\WallKit\Form\Input\Input;

$input = new Input(
    name: 'username',
    placeholder: 'Введите имя пользователя'
);
echo $input;
```

### С валидацией
```php
$email = new Input(
    name: 'email',
    type: 'email',
    required: true,
    autocomplete: 'email',
    pattern: '^[^@\s]+@[^@\s]+\.[^@\s]+$'
);
```

### Числовое поле с ограничениями
```php
$price = new Input(
    name: 'price',
    type: 'number',
    min: '0',
    max: '1000',
    step: '0.01',
    placeholder: '0.00 - 1000.00'
);
```
### Радио-кнопка
```php
$radio = new Input(
    name: 'theme',
    type: 'radio',
    value: 'dark',
    checked: true,
    id: 'theme-dark'
);
```
### Чекбокс
```php
$radio = new Input(
    name: 'agree',
    type: 'checkbox',
    value: 'yes',
    checked: true,
    required: true
);
```

## Стилизация

### CSS-классы
| Класс | Назначение |
|-------|------------|
| `.wallkit-input__field` | Базовый стиль поля |
| `.wallkit-input__field--error` | Состояние ошибки |
| `.wallkit-input__field--disabled` | Отключенное состояние |
| `.wallkit-input__field--sm` | Малый размер |
| `.wallkit-input__field--lg` | Большой размер |
| `.wallkit-input__field--outline` | Контурный вариант |
| `.wallkit-input__field--filled` | Заполненный вариант |

### CSS-переменные
Компонент использует переменные из базовой темы:
- `--wk-color-*` — цвета
- `--wk-spacing-*` — отступы
- `--wk-font-*` — типографика
- `--wk-border-radius-*` — радиусы скругления
- `--wk-shadow-*` — тени
- `--wk-transition-*` — анимации

### HTML5-валидация
Компонент генерирует соответствующие атрибуты:
- `required` для обязательных полей
- `pattern` для регулярных выражений
- `min`/`max` для диапазонов
- `minlength`/`maxlength` для длины

## Совместимость

| Система | Статус | Примечания |
|---------|--------|------------|
| **PHP** | 8.2+ | Strict типизация, readonly классы |
| **HTML** | 5+ | Семантическая разметка |
| **CSS** | 3+ | CSS-переменные, Flexbox |
| **JavaScript** | ES6+ | Прогрессивное улучшение |

## Ссылки
- [Базовый класс Base](../Base/README.md)
- [Документация по формам](../../Form/Form/README.md)
- [Руководство по стилизации](../../../styling.md)

---

**Версия:** 1.0.0  
**Автор:** OlegV  
**Лицензия:** MIT