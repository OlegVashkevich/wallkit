
# Компонент Field

Компонент `Field` предоставляет полную обёртку для поля ввода формы, включая метку, подсказки, ошибки и дополнительные элементы.

## Базовое использование

```php
use OlegV\WallKit\Form\Input\Input;
use OlegV\WallKit\Form\Field\Field;

$field = new Field(
    input: new Input(name: 'username'),
    label: 'Имя пользователя'
);
echo $field;
```

```html
<div class="wallkit-field">
    <label for="..." class="wallkit-field__label">Имя пользователя</label>
    <div class="wallkit-field__wrapper">
        <input type="text" name="username" class="wallkit-input__field">
    </div>
</div>
```

## Параметры конструктора

### Обязательные параметры

| Параметр | Тип | Описание |
|----------|-----|----------|
| `input` | `Input` | Объект Input для рендеринга |

### Основные параметры

| Параметр | Тип | По умолчанию | Описание |
|----------|-----|--------------|----------|
| `label` | `?string` | `null` | Текст метки поля |
| `helpText` | `?string` | `null` | Подсказка под полем |
| `error` | `?string` | `null` | Сообщение об ошибке |
| `withPasswordToggle` | `bool` | `true` | Показывать кнопку toggle для паролей |
| `wrapperClasses` | `array<string>` | `[]` | Дополнительные CSS классы для обёртки |

## Примеры использования

### Базовая форма

```php
$usernameField = new Field(
    input: new Input(
        name: 'username',
        placeholder: 'Введите имя пользователя',
        required: true,
        id: 'username-field'
    ),
    label: 'Имя пользователя',
    helpText: 'Обязательное поле'
);
```

### Поле с ошибкой

```php
$emailField = new Field(
    input: new Input(
        name: 'email',
        type: 'email',
        value: 'неправильный email',
        id: 'email-input'
    ),
    label: 'Email',
    error: 'Введите корректный email'
);
```

### Поле пароля с toggle

```php
$passwordField = new Field(
    input: new Input(
        name: 'password',
        type: 'password',
        id: 'password-input'
    ),
    label: 'Пароль',
    helpText: 'Минимум 8 символов',
    withPasswordToggle: true
);
```

### Поле пароля без toggle

```php
$passwordFieldNoToggle = new Field(
    input: new Input(
        name: 'password2',
        type: 'password',
        id: 'password-input2'
    ),
    label: 'Повторите пароль',
    withPasswordToggle: false
);
```

## Комплексные примеры

### Форма входа

```php
$loginForm = [
    new Field(
        input: new Input(
            name: 'email',
            type: 'email',
            placeholder: 'example@domain.com',
            required: true,
            id: 'email-field',
            autocomplete: 'username'
        ),
        label: 'Email'
    ),
    new Field(
        input: new Input(
            name: 'password',
            type: 'password',
            required: true,
            id: 'password-field',
            autocomplete: 'current-password'
        ),
        label: 'Пароль',
        helpText: 'Минимум 8 символов'
    )
];

foreach ($loginForm as $field) {
    echo $field;
}
```

### Форма с телефоном и датой

```php
$profileForm = [
    new Field(
        input: new Input(
            name: 'phone',
            type: 'tel',
            placeholder: '+7 (___) ___-__-__',
            id: 'phone-input'
        ),
        label: 'Телефон'
    ),
    new Field(
        input: new Input(
            name: 'birthday',
            type: 'date',
            id: 'date-input'
        ),
        label: 'Дата рождения'
    )
];
```

## CSS классы

Компонент генерирует следующие CSS классы:

| Класс | Назначение |
|-------|------------|
| `.wallkit-field` | Основной контейнер |
| `.wallkit-field--error` | Контейнер с ошибкой |
| `.wallkit-field--disabled` | Контейнер отключенного поля |
| `.wallkit-field--sm` | Маленький размер |
| `.wallkit-field--lg` | Большой размер |
| `.wallkit-field--label-left` | Метка слева от поля |
| `.wallkit-field--inline` | Inline-расположение |
| `.wallkit-field--outline` | Вариант с рамкой |
| `.wallkit-field--filled` | Заполненный вариант |
| `.wallkit-field__label` | Метка поля |
| `.wallkit-field__required` | Звездочка обязательного поля |
| `.wallkit-field__wrapper` | Обертка поля ввода |
| `.wallkit-field__toggle-password` | Кнопка показа/скрытия пароля |
| `.wallkit-field__help` | Блок с подсказкой |
| `.wallkit-field__error` | Блок с ошибкой |

## Методы

### `getWrapperClasses(): array`
Возвращает массив CSS классов для обёртки поля.

### `getLabelId(): ?string`
Возвращает ID для связи label с input.

### `shouldShowPasswordToggle(): bool`
Проверяет, нужно ли показывать toggle для пароля.

## Особенности

### Доступность
- Автоматическая связь label с полем через `for`/`id`
- Поддержка ARIA атрибутов
- Корректная семантика обязательных полей
- Кнопка переключения видимости пароля с aria-label

### JavaScript функциональность
Компонент включает JavaScript для:
- Переключения видимости пароля
- Real-time валидации
- Интерактивной обратной связи

### Кастомизация
- Модификаторы размеров (sm, lg)
- Варианты отображения (outline, filled)
- Позиционирование метки (label-left)
- Inline-режим

## Наследование

Компонент наследует:
- `OlegV\WallKit\Base\Base` (который наследует `OlegV\Brick`)
- Трейты: `WithHelpers`, `WithStrictHelpers`, `WithInheritance`

## Когда использовать Field

Используйте `Field` когда:
1. Нужна стандартная форма с метками
2. Требуются подсказки и сообщения об ошибках
3. Нужна кнопка переключения пароля
4. Требуется готовая стилизация обёртки

## Тестирование

Компоненты тестируются через существующие тесты Input, так как Field использует Input внутри. Дополнительные тесты включают:
- Тесты рендеринга обёртки
- Тесты взаимодействия JavaScript
- Тесты доступности
