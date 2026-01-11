<?php

/**
 * Шаблон для компонента ApiResponse
 *
 * Этот шаблон рендерит JSON-ответ API, используя метод `$this->toJson()` компонента ApiResponse.
 * Выводит очищенный JSON с правильными заголовками и структурой данных.
 *
 * @var ApiResponse $this Экземпляр компонента ApiResponse
 * @see ApiResponse::toJson() Для преобразования данных в JSON
 *
 * @package OlegV\WallKit\Utilities\ApiResponse
 * @author OlegV
 * @version 1.0.0
 *
 * @example
 * Успешный ответ:
 * {
 *     "success": true,
 *     "data": {"user": {"id": 1, "name": "John"}},
 *     "meta": {"version": "1.0"},
 *     "timestamp": 1678901234
 * }
 *
 * @example
 * Ошибочный ответ:
 * {
 *     "success": false,
 *     "error": "Пользователь не найден",
 *     "meta": {"code": 404},
 *     "timestamp": 1678901234
 * }
 */

use OlegV\WallKit\Utilities\ApiResponse\ApiResponse;

// Устанавливаем Content-Type для JSON ответа
if (!headers_sent()) {
    header('Content-Type: application/json; charset=utf-8');
}

echo $this->toJson();
