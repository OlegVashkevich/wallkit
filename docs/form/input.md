# Компонент Input

Компонент `Input` предоставляет поле ввода формы с поддержкой различных типов, валидации, доступности и кастомизации.

## Базовое использование

```php
use OlegV\WallKit\Form\Input\Input;

// Простое поле
echo new Input(name: 'username');
```

```html
<div class="wallkit-input">
    <div class="wallkit-input__wrapper">
        <input type="text" name="username" class="wallkit-input__field" data-name="username">
    </div>
</div>
```

## Параметры конструктора

### Обязательные параметры

| Параметр | Тип | Описание |
|----------|-----|----------|
| `name` | `string` | Название поля (атрибут `name`) |

### Основные параметры

| Параметр | Тип | По умолчанию | Описание |
|----------|-----|--------------|----------|
| `label` | `?string` | `null` | Текст метки поля |
| `placeholder` | `?string` | `null` | Текст плейсхолдера |
| `value` | `?string` | `null` | Текущее значение поля |
| `type` | `string` | `'text'` | Тип поля ввода |
| `required` | `bool` | `false` | Обязательное ли поле |
| `disabled` | `bool` | `false` | Заблокировано ли поле |
| `readonly` | `bool` | `false` | Только для чтения |
| `id` | `?string` | `null` | ID поля (автогенерируется если не указан) |
| `helpText` | `?string` | `null` | Подсказка под полем |
| `error` | `?string` | `null` | Сообщение об ошибке |

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
| `attributes` | `array` | `[]` | Дополнительные HTML атрибуты |
| `autoFocus` | `bool` | `false` | Автофокус при загрузке |
| `withPasswordToggle` | `bool` | `true` | Показывать кнопку toggle для паролей |
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

### Форма входа

```php
$loginForm = [
    new Input(
        name: 'username',
        label: 'Имя пользователя',
        placeholder: 'Введите ваш логин',
        required: true,
        autoFocus: true,
        autocomplete: 'username'
    ),
    new Input(
        name: 'password',
        label: 'Пароль',
        type: 'password',
        required: true,
        helpText: 'Минимум 8 символов',
        autocomplete: 'current-password'
    )
];

foreach ($loginForm as $input) {
    echo $input;
}
```

### Форма регистрации

```php
$registrationForm = [
    new Input(
        name: 'email',
        label: 'Email',
        placeholder: 'example@domain.com',
        type: 'email',
        required: true,
        helpText: 'На этот email придет письмо для подтверждения'
    ),
    new Input(
        name: 'phone',
        label: 'Телефон',
        type: 'tel',
        helpText: 'В формате +7 (XXX) XXX-XX-XX',
        pattern: '^\+7\s?\(?\d{3}\)?\s?\d{3}-\d{2}-\d{2}$'
    ),
    new Input(
        name: 'birth_date',
        label: 'Дата рождения',
        type: 'date',
        helpText: 'Вы должны быть старше 18 лет',
        max: date('Y-m-d')
    )
];
```

### Поиск с доступностью

```php
$searchInput = new Input(
    name: 'q',
    placeholder: 'Поиск...',
    type: 'search',
    attributes: [
        'aria-label' => 'Поиск по сайту',
        'role' => 'searchbox'
    ],
    autoFocus: true,
    spellcheck: true
);
```

### Обработка ошибок

```php
// При валидации формы
$emailInput = new Input(
    name: 'email',
    label: 'Email',
    value: $_POST['email'] ?? null,
    type: 'email',
    required: true,
    error: $validationErrors['email'] ?? null
);

$passwordInput = new Input(
    name: 'password',
    label: 'Пароль',
    type: 'password',
    required: true,
    minLength: 8,
    error: $validationErrors['password'] ?? null
);
```

## CSS классы

Компонент генерирует следующие CSS классы:

| Класс | Назначение |
|-------|------------|
| `.wallkit-input` | Основной контейнер |
| `.wallkit-input--error` | Контейнер с ошибкой |
| `.wallkit-input--disabled` | Контейнер отключенного поля |
| `.wallkit-input__label` | Метка поля |
| `.wallkit-input__required` | Звездочка обязательного поля |
| `.wallkit-input__wrapper` | Обертка поля ввода |
| `.wallkit-input__field` | Само поле ввода |
| `.wallkit-input__toggle-password` | Кнопка показа/скрытия пароля |
| `.wallkit-input__help` | Блок с подсказкой |
| `.wallkit-input__error` | Блок с ошибкой |

## Особенности

### Безопасность
- Автоматическое экранирование HTML-специальных символов
- Фильтрация опасных атрибутов (onclick, onerror и т.д.)
- Защита от XSS-атак в значениях и атрибутах

### Доступность
- Автоматическая связь label с полем через `for`/`id`
- Поддержка ARIA атрибутов
- Корректная семантика обязательных полей
- Кнопка переключения видимости пароля с aria-label

### Производительность
- Кэширование на уровне компонента
- Оптимизированный рендеринг
- Минификация CSS/JS через BrickManager

### Иммутабельность
Компонент является `readonly` классом, что обеспечивает:
- Предсказуемый рендеринг
- Безопасность в многопоточной среде
- Простое тестирование

## Методы

### `getBaseClasses(): array`
Возвращает массив CSS классов для контейнера.

### `getInputAttributes(): array`
Возвращает атрибуты для элемента `<input>`.

### `isValidType(string $type): bool`
Проверяет, поддерживается ли указанный тип поля.

## Наследование

Компонент наследует:
- `OlegV\WallKit\Base\Base` (который наследует `OlegV\Brick`)
- Трейты: `WithHelpers`, `WithStrictHelpers`, `WithInheritance`

## Тестирование

Компонент включает полный набор тестов:
- Юнит-тесты (`InputTest.php`)
- Тесты рендеринга (`InputRenderingTest.php`)
- Интеграционные тесты (`InputIntegrationTest.php`)
- Тесты реальных сценариев (`InputRealWorldTest.php`)

## Зависимости

- PHP 8.1+
- Brick (базовая библиотека компонентов)
- Дополнительные трейты для вспомогательных методов