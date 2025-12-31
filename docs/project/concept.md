# WallKit: Концепция технической реализации

## 1. Технологии

**Минимальный стек технологий:**
- PHP 8.2+ (readonly-классы, строгая типизация, union-типы)
- Composer (менеджер зависимостей)
- PHPUnit (для юнит-тестов)
- PHPStan (статический анализ, уровень 6+)
- Чистый HTML5 + CSS3 с кастомными свойствами (CSS-переменные)
- Минимальный JavaScript (ES6, без фреймворков) только для интерактивности
- GitHub Actions (CI/CD)

**Стили:**  
Собственные минимальные стили на основе CSS-переменных, без внешних CSS-фреймворков.  
Каждый компонент содержит свой файл `style.css`.  
Базовые CSS-переменные задаются в `src/Base/style.css`.

---

## 2. Принцип разработки

**KISS-принципы для проекта:**
- Создаём все компоненты из списка `idea.md` сразу, в строгом порядке по группам
- Каждый компонент — иммутабельный readonly-класс
- SSR как дефолтный режим рендеринга
- Документация в коде и в отдельных MD-файлах
- Минимум зависимостей, максимум простоты
- Релиз только после реализации всех компонентов

---

## 3. Структура проекта

```
wallkit/
├── src/
│   ├── Base/                     # Базовый компонент всей библиотеки
│   │   ├── Base.php
│   │   ├── template.php
│   │   └── style.css            # Корневые CSS-переменные
│   │
│   ├── Forms/                    # Ввод данных
│   │   ├── Input/
│   │   ├── Textarea/
│   │   ├── Select/
│   │   ├── Checkbox/
│   │   ├── Radio/
│   │   ├── Button/
│   │   ├── Field/               # Композиция
│   │   ├── Form/
│   │   ├── DatePicker/
│   │   └── FileUpload/
│   │
│   ├── Navigation/
│   │   ├── Menu/
│   │   ├── Sidebar/
│   │   ├── Breadcrumb/
│   │   ├── Pagination/
│   │   ├── Tabs/
│   │   ├── Stepper/
│   │   └── Dropdown/
│   │
│   ├── Data/
│   │   ├── Table/
│   │   ├── DataGrid/
│   │   ├── Card/
│   │   ├── List/
│   │   ├── Tree/
│   │   ├── Accordion/
│   │   └── Carousel/
│   │
│   ├── Feedback/
│   │   ├── Modal/
│   │   ├── Dialog/
│   │   ├── Alert/
│   │   ├── Notification/
│   │   ├── Tooltip/
│   │   ├── Popover/
│   │   ├── ProgressBar/
│   │   └── Spinner/
│   │
│   ├── Content/
│   │   ├── Figure/
│   │   ├── Code/
│   │   ├── Headings/
│   │   ├── Text/
│   │   ├── Link/
│   │   ├── Divider/
│   │   ├── Panel/
│   │   └── Badge/
│   │
│   ├── Layout/
│   │   ├── Grid/
│   │   ├── Flex/
│   │   ├── Stack/
│   │   ├── SplitPanel/
│   │   ├── Dashboard/
│   │   └── AppLayout/
│   │
│   └── Utility/
│       ├── Portal/
│       ├── Overlay/
│       ├── Backdrop/
│       ├── Skeleton/
│       └── EmptyState/
│
├── tests/                        # Юнит- и интеграционные тесты
├── docs/                         # Документация
├── composer.json                 # Зависимость от olegv/brick-ui
├── README.md
├── LICENSE
└── CHANGELOG.md
```

**Примечание:**  
Каждый компонент имеет свою папку с файлами:
- `ComponentName.php`
- `template.php`
- `style.css`
- `script.js` (если нужен)

---

## 4. Архитектура проекта

### Базовая схема архитектуры:

```
┌─────────────────────────────────────────────────────────┐
│                    Пользовательский код                 │
│   new Button('Click') → echo $button;                   │
└─────────────────────────────────────────────────────────┘
                               │
┌─────────────────────────────────────────────────────────┐
│                    Базовый класс Base                   │
│   ┌─────────────────────────────────────────────┐       │
│   │  render(): string                           │       │
│   │  toArray(): array                           │       │
│   │  __toString(): string                       │       │
│   │  validate(): void                           │       │
│   └─────────────────────────────────────────────┘       │
└─────────────────────────────────────────────────────────┘
                               │
┌─────────────────────────────────────────────────────────┐
│                 Компоненты (наследники Base)            │
│   ┌─────────────────────────────────────────────┐       │
│   │  Конструктор с readonly-свойствами          │       │
│   │  Валидация в конструкторе                   │       │
│   │  Шаблон (template.php)                      │       │
│   │  Стили (style.css)                          │       │
│   │  JS (script.js, если нужно)                 │       │
│   └─────────────────────────────────────────────┘       │
└─────────────────────────────────────────────────────────┘
                               │
┌─────────────────────────────────────────────────────────┐
│                      Рендеринг                          │
│   Чистый HTML + CSS + минимальный JS                    │
│   SSR по умолчанию                                      │
│   Data-атрибуты для JS-взаимодействия                   │
└─────────────────────────────────────────────────────────┘
```

**Кэширование:**  
На начальном этапе не используем. Рендерим каждый раз.  
При необходимости добавим `BrickManager` с кэшем на втором этапе.

---

## 5. Модель данных

### Типы данных компонентов:
1. **Скаляры** — `string`, `int`, `float`, `bool`
2. **Union-типы** — `string|null` для опциональных значений
3. **Массивы** — `array` с phpdoc-типизацией (`@var string[]`)
4. **Другие компоненты** — композиция через свойства

### Пример компонента с валидацией:
```php
/**
 * Компонент текстового поля ввода
 * 
 * @property string $name Имя поля
 * @property string $label Подпись поля
 * @property string|null $placeholder Плейсхолдер
 * @property string $type Тип поля (text, email, password)
 * @property bool $required Обязательное ли поле
 */
readonly class Input extends Base
{
    public function __construct(
        public string $name,
        public string $label,
        public ?string $placeholder = null,
        public string $type = 'text',
        public bool $required = false
    ) {
        // Только критичная валидация
        if (!in_array($type, ['text', 'email', 'password', 'number'])) {
            throw new InvalidArgumentException("Неподдерживаемый тип поля: {$type}");
        }
        
        parent::__construct();
    }
}
```

### Документация:
- Обязательное использование phpdoc `@property`
- Описание каждого свойства
- Примеры использования в комментариях
- README.md в папке компонента (опционально)

---

## 6. Сценарии работы

### Базовый сценарий:
```php
use WallKit\Forms\Button;
echo new Button('Submit', variant: 'primary');
```

### Сценарий с формой:
```php
use WallKit\Forms\Form;
use WallKit\Forms\Input;
use WallKit\Forms\Button;

$form = new Form([
    new Input(name: 'email', label: 'Email', type: 'email'),
    new Input(name: 'password', label: 'Пароль', type: 'password'),
    new Button('Войти', type: 'submit')
]);
echo $form;
```

### Композиция компонентов:
```php
use WallKit\Layout\Card;
use WallKit\Content\Headings;
use WallKit\Content\Text;

$card = new Card([
    new Headings('Заголовок карточки', level: 2),
    new Text('Содержимое карточки'),
    new Button('Действие')
]);
echo $card;
```

### JS-взаимодействие:
- Рендерим data-атрибуты: `data-action="submit"`, `data-modal="open"`
- JS-логика пишется в `script.js` компонента
- Подключается отдельно в проекте

---

## 7. Деплой

**Формат распространения:**
- Composer-пакет `olegv/wallkit`
- Исходный код без минификации и транспиляции
- CSS и JS файлы в исходном виде для кастомизации
- Совместимость с любым PHP-фреймворком

**Установка:**
```bash
composer require olegv/wallkit
```

**Публикация:**  
Через Packagist, автоматически при пуше тега через GitHub Actions.

---

## 8. Подход к конфигурированию

**Только через:**
1. Параметры конструктора компонентов
2. CSS-переменные в `:root`

**Пример темы:**
```css
/* src/Base/style.css */
:root {
    --wk-color-primary: #3b82f6;
    --wk-color-secondary: #6b7280;
    --wk-color-success: #10b981;
    --wk-color-danger: #ef4444;
    --wk-color-warning: #f59e0b;
    
    --wk-spacing-xs: 0.25rem;
    --wk-spacing-sm: 0.5rem;
    --wk-spacing-md: 1rem;
    --wk-spacing-lg: 1.5rem;
    --wk-spacing-xl: 2rem;
    
    --wk-font-family: system-ui, -apple-system, sans-serif;
    --wk-font-size-sm: 0.875rem;
    --wk-font-size-md: 1rem;
    --wk-font-size-lg: 1.125rem;
    
    --wk-border-radius: 0.375rem;
    --wk-border-width: 1px;
    --wk-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
```

**Глобальный конфиг на PHP не используем.**

---

## 9. Подход к логгированию

**Без логгирования на уровне библиотеки.**  
Логи — ответственность приложения.  
При необходимости добавим трейт `WithDebug` для разработки позже.

---

## План разработки

1. **Подготовка**:
  - Создать репозиторий с базовой структурой
  - Настроить Composer, PHPUnit, PHPStan, CI/CD
  - Реализовать `Base` класс и базовый рендеринг

2. **Разработка всех компонентов группами** (порядок разработки):
  1. **Base** + **Typography** + **Container** (основа)
  2. **Forms**: Input, Textarea, Select, Checkbox, Radio, Button, Field, Form, DatePicker, FileUpload
  3. **Navigation**: Menu, Sidebar, Breadcrumb, Pagination, Tabs, Stepper, Dropdown
  4. **Data**: Table, DataGrid, Card, List, Tree, Accordion, Carousel
  5. **Feedback**: Modal, Dialog, Alert, Notification, Tooltip, Popover, ProgressBar, Spinner
  6. **Content**: Figure, Code, Headings, Text, Link, Divider, Panel, Badge
  7. **Layout**: Grid, Flex, Stack, SplitPanel, Dashboard, AppLayout
  8. **Utility**: Portal, Overlay, Backdrop, Skeleton, EmptyState

3. **Тестирование и документация**:
  - Написать юнит-тесты для каждого компонента
  - Статический анализ (PHPStan уровень 6+)
  - Интеграционные тесты
  - Документация в коде + README для каждого компонента

4. **Подготовка к релизу**:
  - Пример приложения
  - Интерактивная документация
  - Подготовка CHANGELOG
  - Версия 1.0.0

5. **Релиз**:
  - Публикация в Packagist
  - Официальная документация
  - Поддержка PHP 8.2+

---

**Философия:**  
Простота, типизация, SSR. Создание UI без тяжёлых JS-фреймворков, с упором на производительность, безопасность и разработку на PHP.

---

*Документ утверждён как техническая спецификация проекта WallKit*