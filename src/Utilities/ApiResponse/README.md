# Компонент ApiResponse

Компонент для формирования стандартизированных JSON-ответов API. Реализует единый формат ответов с поддержкой успешных и ошибочных сценариев, метаданных и фильтрации конфиденциальных данных.

## Характеристики

| Параметр | Значение |
|----------|----------|
| **Тип компонента** | Утилитарный компонент API |
| **Иммутабельность** | Да (readonly) |
| **Рендеринг** | SSR (серверный) |
| **Типизация** | Strict PHP 8.2+ |
| **Формат вывода** | JSON |
| **Зависимости** | Base |

## Формат ответа

### Успешный ответ
```json
{
    "success": true,
    "data": {...},
    "meta": {...},
    "timestamp": 1678901234
}
```

### Ошибочный ответ
```json
{
    "success": false,
    "error": "Сообщение об ошибке",
    "meta": {...},
    "timestamp": 1678901234
}
```

## Свойства компонента

| Свойство | Тип | По умолчанию | Описание |
|----------|-----|--------------|----------|
| `success` | `bool` | — | Успешность операции (обязательно) |
| `data` | `array\|object\|null` | — | Основные данные ответа |
| `error` | `?string` | `null` | Сообщение об ошибке (если `success = false`) |
| `meta` | `array<string, mixed>` | `[]` | Метаданные ответа |
| `exclude` | `array<string>` | `[]` | Список свойств для исключения из данных |
| `jsonOptions` | `int` | `JSON_PRETTY_PRINT \| JSON_UNESCAPED_UNICODE` | Опции для json_encode |
| `jsonDepth` | `int<1, max>` | `512` | Максимальная глубина вложенности |

## Валидация

Компонент автоматически проверяет корректность параметров:
- При `success = true` параметр `error` должен быть `null`
- При `success = false` параметр `error` должен быть указан

## Использование

### Успешный ответ с данными
```php
use OlegV\WallKit\Utilities\ApiResponse\ApiResponse;

$response = ApiResponse::success(
    data: ['user' => ['id' => 1, 'name' => 'John']],
    meta: ['version' => '1.0']
);
echo $response;
```

### Ответ с ошибкой
```php
$response = ApiResponse::error(
    error: 'Пользователь не найден',
    meta: ['code' => 404, 'details' => 'user_id=5']
);
echo $response;
```

### Прямое создание с фильтрацией данных
```php
$response = new ApiResponse(
    success: true,
    data: [
        'user' => [
            'id' => 1,
            'name' => 'John',
            'password' => 'secret',
            'token' => 'xyz123'
        ]
    ],
    exclude: ['password', 'token']
);
// В ответе будут только id и name
echo $response;
```

### Статические фабричные методы

| Метод | Описание |
|-------|----------|
| `ApiResponse::success()` | Создает успешный ответ |
| `ApiResponse::error()` | Создает ответ с ошибкой |

## Фильтрация данных

Компонент поддерживает автоматическую фильтрацию конфиденциальных данных через параметр `exclude`:

### Массивы
```php
$response = new ApiResponse(
    success: true,
    data: ['user' => ['id' => 1, 'password' => 'secret']],
    exclude: ['password']
);
// Результат: {"user": {"id": 1}}
```

### Объекты
```php
class User {
    public int $id = 1;
    public string $name = 'John';
    public string $password = 'secret';
}

$response = new ApiResponse(
    success: true,
    data: new User(),
    exclude: ['password']
);
// Результат: {"id": 1, "name": "John"}
```

## Настройка JSON

### Опции кодирования
```php
$response = new ApiResponse(
    success: true,
    data: ['test' => 'тест'],
    jsonOptions: JSON_UNESCAPED_UNICODE // Без экранирования Unicode
);
```

### Глубина вложенности
```php
$response = new ApiResponse(
    success: true,
    data: $deeplyNestedData,
    jsonDepth: 1024 // Увеличенная глубина
);
```

## Совместимость

| Система | Статус | Примечания |
|---------|--------|------------|
| **PHP** | 8.2+ | Strict типизация, readonly классы, union types |
| **JSON** | RFC 8259 | Совместим со стандартом JSON |
| **API** | RESTful | Стандартный формат ответов |

## Ссылки
- [Базовый класс Base](../Base/README.md)
- [Документация по утилитам](../../Utilities/README.md)

---

**Версия:** 1.0.0  
**Автор:** OlegV  
**Лицензия:** MIT