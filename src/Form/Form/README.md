# Компонент Form

Компонент-контейнер для группировки полей формы с обработкой отправки и CSRF-защитой. Реализует стандартный HTML-элемент `<form>` с полной типобезопасностью на PHP.

## Характеристики

| Параметр | Значение |
|----------|----------|
| **Тип компонента** | Контейнер для формы |
| **Иммутабельность** | Да (readonly) |
| **Рендеринг** | SSR (серверный) |
| **Типизация** | Strict PHP 8.2+ |
| **CSS префикс** | `wallkit-` |
| **Зависимости** | Base, WithHelpers, WithStrictHelpers, WithInheritance |

## Поддерживаемые HTTP методы

| Метод | Назначение | CSRF защита |
|-------|------------|-------------|
| `GET` | Получение данных | Нет |
| `POST` | Отправка данных | Да (рекомендуется) |
| `PUT` | Обновление данных | Да |
| `PATCH` | Частичное обновление | Да |
| `DELETE` | Удаление данных | Да |

## Свойства компонента

| Свойство | Тип | По умолчанию | Описание |
|----------|-----|--------------|----------|
| `fields` | `array` | — | Массив полей формы (обязательно) |
| `action` | `string` | `''` | URL обработки формы |
| `method` | `string` | `'POST'` | HTTP метод отправки |
| `csrfToken` | `?string` | `null` | CSRF-токен для защиты |
| `id` | `?string` | `null` | HTML ID формы |
| `name` | `?string` | `null` | Имя формы |
| `novalidate` | `bool` | `false` | Отключить браузерную валидацию |
| `autoComplete` | `bool` | `true` | Включить автозаполнение |
| `enctype` | `?string` | `null` | Тип кодировки данных |
| `target` | `?string` | `null` | Цель отправки формы |
| `classes` | `array<string>` | `[]` | Дополнительные CSS-классы |
| `attributes` | `array<string, string\|int\|bool\|null>` | `[]` | Дополнительные HTML-атрибуты |

## Использование

### Базовая форма с полями
```php
use OlegV\WallKit\Form\Form\Form;
use OlegV\WallKit\Form\Input\Input;
use OlegV\WallKit\Form\Button\Button;

$form = new Form(
    fields: [
        new Input(name: 'username', placeholder: 'Имя пользователя'),
        new Input(name: 'email', type: 'email', placeholder: 'Email'),
        new Button('Отправить', type: 'submit')
    ],
    action: '/submit',
    method: 'POST'
);
echo $form;
```

### Форма с CSRF защитой
```php
$form = new Form(
    fields: [
        new Input(name: 'password', type: 'password', placeholder: 'Пароль'),
        new Button('Войти', type: 'submit')
    ],
    action: '/login',
    method: 'POST',
    csrfToken: 'abc123token456'
);
```

### Форма с загрузкой файлов
```php
use OlegV\WallKit\Form\Input\Input;

$form = new Form(
    fields: [
        new Input(name: 'file', type: 'file'),
        new Button('Загрузить', type: 'submit')
    ],
    action: '/upload',
    method: 'POST',
    enctype: 'multipart/form-data'
);
```

### Форма GET запроса (поиск)
```php
$form = new Form(
    fields: [
        new Input(name: 'query', placeholder: 'Поиск...'),
        new Button('Найти', type: 'submit')
    ],
    action: '/search',
    method: 'GET'
);
```

## Стилизация

### CSS-классы
| Класс | Назначение |
|-------|------------|
| `.wallkit-form` | Базовый стиль формы |
| `.wallkit-form--submitting` | Состояние отправки формы |
| `.wallkit-form__message` | Сообщение формы (успех/ошибка) |
| `.wallkit-form__message--success` | Сообщение об успехе |
| `.wallkit-form__message--error` | Сообщение об ошибке |

### CSS-переменные
Компонент использует переменные из базовой темы:
- `--wk-color-*` — цвета
- `--wk-spacing-*` — отступы
- `--wk-font-*` — типографика
- `--wk-border-radius-*` — радиусы скругления
- `--wk-shadow-*` — тени
- `--wk-transition-*` — анимации

## JavaScript взаимодействие

### AJAX отправка
Добавьте атрибут `data-ajax` для активации AJAX отправки:
```php
$form = new Form(
    fields: [/* ... */],
    action: '/api/submit',
    attributes: ['data-ajax' => true]
);
```

### Состояния формы
- **Отправка**: Добавляется класс `.wallkit-form--submitting`
- **Успех**: Отображается сообщение `.wallkit-form__message--success`
- **Ошибка**: Отображается сообщение `.wallkit-form__message--error`

## Особенности работы

### CSRF защита
CSRF-токен добавляется в форму как скрытое поле `_token` для методов:
- POST, PUT, PATCH, DELETE

**Токен должен быть сгенерирован и передан в параметр `csrfToken` конструктора.**

### Браузерная валидация
По умолчанию включена. Для отключения используйте:
```php
$form = new Form(
    fields: [/* ... */],
    novalidate: true
);
```

### Автозаполнение
По умолчанию включено. Для отключения используйте:
```php
$form = new Form(
    fields: [/* ... */],
    autoComplete: false
);
```

## Совместимость

| Система | Статус | Примечания |
|---------|--------|------------|
| **PHP** | 8.2+ | Strict типизация, readonly классы |
| **HTML** | 5+ | Семантическая разметка |
| **CSS** | 3+ | CSS-переменные, Flexbox |
| **JavaScript** | ES6+ | Прогрессивное улучшение |

## Ссылки
- [Компонент Input](../Input/README.md)
- [Компонент Field](../Field/README.md)
- [Компонент Button](../Button/README.md)
- [Руководство по стилизации](../../../styling.md)

---

**Версия:** 1.0.0  
**Автор:** OlegV  
**Лицензия:** MIT