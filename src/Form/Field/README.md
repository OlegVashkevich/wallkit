# Компонент Field

Компонент-обёртка для полей ввода (Input, Textarea, Select и др.) с меткой, подсказкой, сообщением об ошибке и опциональным переключателем видимости пароля. Предоставляет структурированный и доступный способ организации полей формы.

## Характеристики

| Параметр | Значение |
|----------|----------|
| **Тип компонента** | Композитный элемент формы |
| **Иммутабельность** | Да (readonly) |
| **Рендеринг** | SSR (серверный) |
| **Типизация** | Strict PHP 8.2+ |
| **CSS префикс** | `wallkit-` |
| **Зависимости** | Base, WithHelpers, WithStrictHelpers, WithInheritance, Input/Textarea |

## Поддерживаемые типы полей

Компонент Field работает с любыми компонентами ввода, которые наследуются от Base, но оптимизирован для:

| Тип поля | Компонент | Особенности |
|----------|-----------|-------------|
| **Текстовый ввод** | `Input` | Все типы HTML5 input |
| **Многострочный текст** | `Textarea` | С подсказкой и авторазмером |
| **Выпадающий список** | `Select` | Одиночный и множественный выбор |
| **Переключатели** | `Checkbox`, `Radio` | Группы и одиночные элементы |

## Свойства компонента

| Свойство | Тип | По умолчанию | Описание |
|----------|-----|--------------|----------|
| `input` | `Input|Textarea` | — | Объект поля ввода (обязательно) |
| `label` | `?string` | `null` | Текст метки над полем |
| `helpText` | `?string` | `null` | Подсказка под полем |
| `error` | `?string` | `null` | Сообщение об ошибке |
| `withPasswordToggle` | `bool` | `true` | Показывать переключатель пароля |
| `wrapperClasses` | `array<string>` | `[]` | Дополнительные CSS-классы |

## Использование

### Базовое использование с Input
```php
use OlegV\WallKit\Form\Field\Field;
use OlegV\WallKit\Form\Input\Input;

$field = new Field(
    input: new Input(name: 'username'),
    label: 'Имя пользователя',
    helpText: 'Введите ваше имя пользователя'
);
echo $field;
```

### Поле с ошибкой
```php
$field = new Field(
    input: new Input(name: 'email', type: 'email'),
    label: 'Email адрес',
    error: 'Некорректный формат email'
);
```

### Пароль с переключателем видимости
```php
$field = new Field(
    input: new Input(name: 'password', type: 'password'),
    label: 'Пароль',
    withPasswordToggle: true
);
```

### Textarea с меткой
```php
use OlegV\WallKit\Form\Textarea\Textarea;

$field = new Field(
    input: new Textarea(name: 'bio', rows: 4),
    label: 'Биография',
    helpText: 'Расскажите о себе'
);
```

### Кастомизация классов
```php
$field = new Field(
    input: new Input(name: 'search'),
    label: 'Поиск',
    wrapperClasses: ['custom-search-field', 'mb-6']
);
```

## Стилизация

### CSS-классы

| Класс | Назначение |
|-------|------------|
| `.wallkit-field` | Базовый контейнер поля |
| `.wallkit-field__label` | Метка поля |
| `.wallkit-field__required` | Индикатор обязательного поля (*) |
| `.wallkit-field__wrapper` | Контейнер для поля и кнопки |
| `.wallkit-field__toggle-password` | Кнопка переключения пароля |
| `.wallkit-field__help` | Текст подсказки |
| `.wallkit-field__error` | Сообщение об ошибке |

### Модификаторы состояния

| Класс | Назначение |
|-------|------------|
| `.wallkit-field--error` | Поле с ошибкой |
| `.wallkit-field--disabled` | Отключенное поле |
| `.wallkit-field--sm` | Маленький размер |
| `.wallkit-field--lg` | Большой размер |
| `.wallkit-field--outline` | Контурный стиль |
| `.wallkit-field--filled` | Заполненный стиль |
| `.wallkit-field--label-left` | Метка слева от поля |
| `.wallkit-field--inline` | Inline-расположение |

### CSS-переменные
Компонент использует переменные из базовой темы WallKit:

- `--wk-color-*` — цветовая палитра
- `--wk-spacing-*` — система отступов
- `--wk-font-*` — типографика
- `--wk-border-radius-*` — радиусы скругления
- `--wk-shadow-*` — система теней
- `--wk-transition-*` — анимации

## Доступность (A11y)

Компонент обеспечивает:
- Правильную связь `label` с `input` через атрибут `for`
- `aria-label` для кнопки переключения пароля
- Семантическую разметку для подсказок и ошибок
- Соответствие WCAG 2.1 AA для контраста цветов
- Клавиатурную навигацию по всем элементам

## Совместимость

| Система | Статус | Примечания |
|---------|--------|------------|
| **PHP** | 8.2+ | Readonly классы, union-типы |
| **HTML** | 5+ | Семантическая разметка |
| **CSS** | 3+ | CSS Grid, Flexbox, переменные |
| **JavaScript** | ES6+ | Прогрессивное улучшение |
| **Браузеры** | Chrome 60+, Firefox 55+, Safari 12+, Edge 79+ | Modern browsers |

## Ссылки
- [Компонент Input](../Input/README.md)
- [Компонент Textarea](../Textarea/README.md)
- [Документация по формам](../../Form/README.md)
- [Руководство по стилизации](../../../styling.md)

---

**Версия:** 1.0.0  
**Автор:** OlegV  
**Лицензия:** MIT