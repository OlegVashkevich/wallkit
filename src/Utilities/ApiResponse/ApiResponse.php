<?php

declare(strict_types=1);

namespace OlegV\WallKit\Utilities\ApiResponse;

use InvalidArgumentException;
use OlegV\WallKit\Base\Base;
use ReflectionClass;
use ReflectionProperty;
use RuntimeException;

/**
 * Компонент для формирования JSON-ответов API
 */
readonly class ApiResponse extends Base
{
    /**
     * @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection
     * @param  bool  $success  Успешность операции
     * @param  array<mixed>|object|null  $data  Основные данные ответа
     * @param  string|null  $error  Сообщение об ошибке (если success = false)
     * @param  array<string, mixed>  $meta  Метаданные
     * @param  array<string>  $exclude  Список свойств для исключения
     * @param  int  $jsonOptions  Опции для json_encode
     * @param  int<1, max>  $jsonDepth  Максимальная глубина вложенности
     */
    public function __construct(
        public bool $success,
        public array|object|null $data,
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
     *
     * @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection
     * @param  array<mixed>|object|null  $data  Основные данные
     * @param  array<string, mixed>  $meta  Метаданные
     */
    public static function success(array|object|null $data = null, array $meta = []): self
    {
        return new self(
            success: true,
            data: $data,
            meta: $meta,
        );
    }

    /**
     * Создает ответ с ошибкой
     *
     * @param  string  $error  Сообщение об ошибке
     * @param  array<string, mixed>  $meta  Метаданные
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
     *
     * @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection
     * @param  array<mixed>|object|null  $data  Данные для фильтрации
     * @return array<mixed>|object|null Отфильтрованные данные
     */
    private function filterData(array|object|null $data): array|object|null
    {
        if ($data === null || $this->exclude === []) {
            return $data;
        }

        if (is_array($data)) {
            return $this->filterArray($data);
        }

        // Для объектов используем рефлексию
        return $this->filterObject($data);
    }

    /**
     * Фильтрует массив, исключая указанные ключи
     *
     * @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection
     * @param  array<mixed>  $array  Массив для фильтрации
     * @return array<mixed> Отфильтрованный массив
     */
    private function filterArray(array $array): array
    {
        $filtered = [];

        foreach ($array as $key => $value) {
            if (is_string($key) && in_array($key, $this->exclude, true)) {
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

    /**
     * Фильтрует объект, исключая указанные свойства
     *
     * @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection
     * @param  object  $object  Объект для фильтрации
     * @return array<mixed> Массив свойств объекта
     */
    private function filterObject(object $object): array
    {
        $result = [];
        $reflection = new ReflectionClass($object);

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $name = $property->getName();

            if (in_array($name, $this->exclude, true)) {
                continue;
            }

            $value = $property->getValue($object);

            if (is_array($value) || is_object($value)) {
                $result[$name] = $this->filterData($value);
            } else {
                $result[$name] = $value;
            }
        }

        return $result;
    }
}