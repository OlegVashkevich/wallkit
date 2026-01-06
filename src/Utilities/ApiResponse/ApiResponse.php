<?php

declare(strict_types=1);

namespace OlegV\WallKit\Utilities\ApiResponse;

use InvalidArgumentException;
use OlegV\WallKit\Base\Base;
use ReflectionClass;
use RuntimeException;

/**
 * Компонент для быстрого формирования JSON-ответов API
 *
 * @property bool $success Успешность операции
 * @property mixed $data Основные данные ответа
 * @property string|null $error Сообщение об ошибке (если success = false)
 * @property array $meta Метаданные (пагинация, фильтры и т.д.)
 * @property array $exclude Список свойств для исключения из сериализации
 * @property int $jsonOptions Опции для json_encode
 * @property int $jsonDepth Максимальная глубина вложенности
 */
readonly class ApiResponse extends Base
{
    public function __construct(
        public bool $success,
        public mixed $data,
        public ?string $error = null,
        public array $meta = [],
        public array $exclude = [],
        public int $jsonOptions = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE,
        public int $jsonDepth = 512,
    ) {
        // Валидация: если ошибка есть, но success = true
        if ($success && $error !== null) {
            throw new InvalidArgumentException('При success = true ошибка должна быть null');
        }

        // Валидация: если ошибки нет, но success = false
        if (!$success && $error === null) {
            throw new InvalidArgumentException('При success = false должна быть указана ошибка');
        }

        parent::__construct();
    }

    /**
     * Преобразует ответ в JSON-строку
     */
    public function toJson(): string
    {
        $result = [
            'success' => $this->success,
            'data' => $this->filterData($this->data),
            'meta' => $this->meta,
            'timestamp' => time(),
        ];

        if (!$this->success) {
            $result['error'] = $this->error;
        }

        $json = json_encode($result, $this->jsonOptions, $this->jsonDepth);

        if ($json === false) {
            throw new RuntimeException('Ошибка кодирования JSON: '.json_last_error_msg());
        }

        return $json;
    }

    /**
     * Автоматическое преобразование в JSON при выводе
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * Создает успешный ответ
     */
    public static function success(mixed $data = null, array $meta = []): self
    {
        return new self(
            success: true,
            data: $data,
            meta: $meta,
        );
    }

    /**
     * Создает ответ с ошибкой
     */
    public static function error(string $error, array $meta = []): self
    {
        return new self(
            success: false,
            data: null,
            error: $error,
            meta: $meta,
        );
    }

    /**
     * Фильтрует данные, исключая конфиденциальные поля
     */
    private function filterData(mixed $data): mixed
    {
        if (empty($this->exclude)) {
            return $data;
        }

        if (is_array($data)) {
            return $this->filterArray($data);
        }

        if (is_object($data) && method_exists($data, 'toArray')) {
            $array = $data->toArray();
            return $this->filterArray($array);
        }

        if (is_object($data)) {
            return $this->filterObject($data);
        }

        return $data;
    }

    private function filterArray(array $array): array
    {
        $filtered = [];

        foreach ($array as $key => $value) {
            if (in_array($key, $this->exclude, true)) {
                continue;
            }

            if (is_array($value) || is_object($value)) {
                $filtered[$key] = $this->filterData($value);
            } else {
                $filtered[$key] = $value;
            }
        }

        return $filtered;
    }

    private function filterObject(object $object): array
    {
        $result = [];
        $reflection = new ReflectionClass($object);

        foreach ($reflection->getProperties() as $property) {
            $propertyName = $property->getName();

            if (in_array($propertyName, $this->exclude, true)) {
                continue;
            }

            $value = $property->getValue($object);

            if (is_array($value) || is_object($value)) {
                $result[$propertyName] = $this->filterData($value);
            } else {
                $result[$propertyName] = $value;
            }
        }

        return $result;
    }
}