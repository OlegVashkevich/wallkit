# Компонент Input (обновлённый)

Компонент `Input` предоставляет чистое поле ввода формы с поддержкой различных типов, валидации и кастомизации, без дополнительных обёрток.

## Базовое использование

```php
use OlegV\WallKit\Form\Input\Input;

// Простое поле
echo new Input(name: 'username');
```

```html
<input type="text" name="username" class="wallkit-input__field" data-name="username">
```

## Параметры конструктора

### Обязательные параметры

| Параметр | Тип | Описание |
|----------|-----|----------|
| `name` | `string` | Название поля (атрибут `name`) |

### Основные параметры

| Параметр | Тип | По умолчанию | Описание |
|----------|-----|--------------|----------|
| `placeholder` | `?string` | `null` | Текст плейсхолдера |
| `value` | `?string` | `null` | Текущее значение поля |
| `type` | `string` | `'text'` | Тип поля ввода |
| `required` | `bool` | `false` | Обязательное ли поле |
| `disabled` | `bool` | `false` | Заблокировано ли поле |
| `readonly` | `bool` | `false` | Только для чтения |
| `id` | `?string` | `null` | ID поля (автогенерируется если не указан) |

### Параметры валидации

| Параметр | Тип | По умолчанию | Описание |
|----------|-----|--------------|----------|
| `pattern` | `?string` | `null` | Регулярное выражение для валидации |
| `min` | `?string` | `null` | Минимальное значение (для number/date) |
| `max` | `?string` | `null` | Максимальное значение (для number/date) |
| `maxLength` | `?int` | `null` | Максимальная длина |
| `minLength` | `?int` | `null` | Минимальная длина |
| `step` | `?string` | `null` | Шаг для числовых полей |

### Параметры кастомизации

| Параметр | Тип | По умолчанию | Описание |
|----------|-----|--------------|----------|
| `classes` | `array<string>` | `[]` | Дополнительные CSS классы |
| `attributes` | `array<string, string\|int\|bool\|null>` | `[]` | Дополнительные HTML атрибуты |
| `autoFocus` | `bool` | `false` | Автофокус при загрузке |
| `autocomplete` | `?string` | `null` | Значение атрибута autocomplete |
| `spellcheck` | `?bool` | `null` | Включить/выключить проверку орфографии |

## Поддерживаемые типы полей

Компонент поддерживает следующие типы полей HTML5:

- `text` (по умолчанию)
- `email`
- `password`
- `number`
- `tel`
- `url`
- `search`
- `date`
- `datetime-local`
- `time`
- `month`
- `week`
- `color`
- `range`
- `file`
- `hidden`

## Примеры использования

### Чистое поле ввода

```php
$searchInput = new Input(
    name: 'search',
    placeholder: 'Поиск...',
    type: 'search',
    autoFocus: true
);
echo $searchInput;
```

### Поле с валидацией

```php
$emailInput = new Input(
    name: 'email',
    type: 'email',
    placeholder: 'example@domain.com',
    required: true,
    pattern: '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$'
);
echo $emailInput;
```

### Поле пароля

```php
$passwordInput = new Input(
    name: 'password',
    type: 'password',
    minLength: 8,
    required: true,
    autocomplete: 'current-password'
);
echo $passwordInput;
```

## CSS классы

Компонент генерирует следующие CSS классы:

| Класс | Назначение |
|-------|------------|
| `.wallkit-input__field` | Основной класс поля ввода |
| `.wallkit-input__field--error` | Поле с ошибкой |
| `.wallkit-input__field--disabled` | Отключенное поле |
| `.wallkit-input__field--sm` | Маленький размер |
| `.wallkit-input__field--lg` | Большой размер |
| `.wallkit-input__field--outline` | Вариант с рамкой |
| `.wallkit-input__field--filled` | Заполненный вариант |

## Методы

### `getInputClasses(): array`
Возвращает массив CSS классов для элемента `<input>`.

### `getInputAttributes(): array`
Возвращает атрибуты для элемента `<input>`.

### `isValidType(string $type): bool`
Проверяет, поддерживается ли указанный тип поля.

## Особенности

### Безопасность
- Автоматическое экранирование HTML-специальных символов
- Фильтрация опасных атрибутов (onclick, onerror и т.д.)
- Защита от XSS-атак в значениях и атрибутах

### Производительность
- Кэширование на уровне компонента
- Оптимизированный рендеринг
- Минификация CSS/JS через BrickManager

### Иммутабельность
Компонент является `readonly` классом, что обеспечивает:
- Предсказуемый рендеринг
- Безопасность в многопоточной среде
- Простое тестирование

## Наследование

Компонент наследует:
- `OlegV\WallKit\Base\Base` (который наследует `OlegV\Brick`)
- Трейты: `WithHelpers`, `WithStrictHelpers`, `WithInheritance`

## Когда использовать чистый Input

Используйте чистый `Input` когда:
1. Нужно встроить поле ввода в кастомную разметку
2. Требуется максимальная производительность
3. Обёртка (label, help, error) не нужна
4. Создаётся кастомный компонент формы

## Когда использовать с компонентом Field

Для большинства случаев используйте компонент `Field`, который оборачивает `Input` и добавляет:
- Метку (label)
- Подсказки (help text)
- Ошибки (error messages)
- Кнопку переключения пароля
- Обёртку для стилизации
