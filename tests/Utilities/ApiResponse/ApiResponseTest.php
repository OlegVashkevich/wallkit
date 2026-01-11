<?php

declare(strict_types=1);

namespace OlegV\WallKit\Tests\Utilities\ApiResponse;

use InvalidArgumentException;
use OlegV\Exceptions\RenderException;
use OlegV\WallKit\Base\Base;
use OlegV\WallKit\Utilities\ApiResponse\ApiResponse;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use RuntimeException;
use stdClass;

/**
 * Тесты компонента ApiResponse
 */
class ApiResponseTest extends TestCase
{
    public function testApiResponseClassExists(): void
    {
        $this->assertTrue(class_exists(ApiResponse::class));
    }

    public function testApiResponseIsReadonly(): void
    {
        $reflection = new ReflectionClass(ApiResponse::class);
        $this->assertTrue($reflection->isReadOnly());
    }

    public function testApiResponseExtendsBase(): void
    {
        $this->assertInstanceOf(Base::class, new ApiResponse(true, []));
    }

    public function testSuccessResponseCreation(): void
    {
        $response = ApiResponse::success(['test' => 'value']);

        $this->assertTrue($response->success);
        $this->assertEquals(['test' => 'value'], $response->data);
        $this->assertNull($response->error);
        $this->assertEquals([], $response->meta);
        $this->assertEquals([], $response->exclude);
    }

    public function testSuccessResponseWithMeta(): void
    {
        $meta = ['version' => '1.0', 'count' => 5];
        $response = ApiResponse::success(['data' => 'test'], $meta);

        $this->assertTrue($response->success);
        $this->assertEquals(['data' => 'test'], $response->data);
        $this->assertEquals($meta, $response->meta);
    }

    public function testErrorResponseCreation(): void
    {
        $response = ApiResponse::error('Something went wrong');

        $this->assertFalse($response->success);
        $this->assertNull($response->data);
        $this->assertEquals('Something went wrong', $response->error);
        $this->assertEquals([], $response->meta);
    }

    public function testErrorResponseWithMeta(): void
    {
        $meta = ['code' => 404, 'details' => 'Resource not found'];
        $response = ApiResponse::error('Not found', $meta);

        $this->assertFalse($response->success);
        $this->assertEquals('Not found', $response->error);
        $this->assertEquals($meta, $response->meta);
    }

    public function testSuccessResponseValidation(): void
    {
        $response = new ApiResponse(
            success: true,
            data: ['test' => 'value'],
            error: null, // Должно быть null при success = true
            meta: [],
            exclude: [],
        );

        // Не должно вызывать исключение
        $this->assertInstanceOf(ApiResponse::class, $response);
    }

    public function testErrorResponseValidation(): void
    {
        $response = new ApiResponse(
            success: false,
            data: null,
            error: 'Error message', // Должно быть не null при success = false
            meta: [],
            exclude: [],
        );

        // Не должно вызывать исключение
        $this->assertInstanceOf(ApiResponse::class, $response);
    }

    public function testInvalidSuccessWithError(): void
    {
        $this->expectException(RenderException::class);

        try {
            $response = new ApiResponse(
                success: true,
                data: ['test' => 'value'],
                error: 'This should not be here', // Ошибка при success = true
                meta: [],
                exclude: [],
            );

            // Вызываем рендеринг, который запустит prepare() и вызовет исключение
            $response->renderOriginal();
        } catch (RenderException $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e->getPrevious());
            $this->assertStringContainsString('success = true', $e->getPrevious()->getMessage());
            throw $e;
        }
    }

    public function testInvalidErrorWithoutMessage(): void
    {
        $this->expectException(RenderException::class);

        try {
            $response = new ApiResponse(
                success: false,
                data: null,
                error: null, // Ошибка не указана при success = false
                meta: [],
                exclude: [],
            );

            $response->renderOriginal();
        } catch (RenderException $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e->getPrevious());
            $this->assertStringContainsString('success = false', $e->getPrevious()->getMessage());
            throw $e;
        }
    }

    public function testToJsonSuccessResponse(): void
    {
        $response = ApiResponse::success(
            ['user' => ['id' => 1, 'name' => 'John']],
            ['version' => '1.0'],
        );

        $json = $response->toJson();
        $data = json_decode($json, true);

        $this->assertIsArray($data);
        $this->assertTrue($data['success']);
        $this->assertEquals(['id' => 1, 'name' => 'John'], $data['data']['user']);
        $this->assertEquals('1.0', $data['meta']['version']);
        $this->assertArrayHasKey('timestamp', $data);
        $this->assertIsInt($data['timestamp']);
        $this->assertArrayNotHasKey('error', $data);
    }

    public function testToJsonErrorResponse(): void
    {
        $response = ApiResponse::error(
            'User not found',
            ['code' => 404],
        );

        $json = $response->toJson();
        $data = json_decode($json, true);

        $this->assertIsArray($data);
        $this->assertFalse($data['success']);
        $this->assertEquals('User not found', $data['error']);
        $this->assertEquals(404, $data['meta']['code']);
        $this->assertArrayHasKey('timestamp', $data);
        $this->assertIsInt($data['timestamp']);
        $this->assertArrayHasKey('data', $data);
        $this->assertNull($data['data']);
    }

    public function testExcludeFilterForArrays(): void
    {
        $response = new ApiResponse(
            success: true,
            data: [
                'user' => [
                    'id' => 1,
                    'name' => 'John',
                    'password' => 'secret',
                    'token' => 'abc123',
                ],
            ],
            exclude: ['password', 'token'],
        );

        $json = $response->toJson();
        $data = json_decode($json, true);

        $this->assertEquals(['id' => 1, 'name' => 'John'], $data['data']['user']);
        $this->assertArrayNotHasKey('password', $data['data']['user']);
        $this->assertArrayNotHasKey('token', $data['data']['user']);
    }

    public function testExcludeFilterForObjects(): void
    {
        $user = new class () {
            public int $id = 1;
            public string $name = 'John';
            public string $password = 'secret';
            public string $token = 'abc123';
        };

        $response = new ApiResponse(
            success: true,
            data: $user,
            exclude: ['password', 'token'],
        );

        $json = $response->toJson();
        $data = json_decode($json, true);

        $this->assertEquals(['id' => 1, 'name' => 'John'], $data['data']);
        $this->assertArrayNotHasKey('password', $data['data']);
        $this->assertArrayNotHasKey('token', $data['data']);
    }

    public function testNestedArrayFiltering(): void
    {
        $response = new ApiResponse(
            success: true,
            data: [
                'users' => [
                    ['id' => 1, 'name' => 'John', 'password' => 'secret1'],
                    ['id' => 2, 'name' => 'Jane', 'password' => 'secret2'],
                ],
                'config' => [
                    'api_key' => 'key123',
                    'public' => 'visible',
                ],
            ],
            exclude: ['password', 'api_key'],
        );

        $json = $response->toJson();
        $data = json_decode($json, true);

        $this->assertEquals(['id' => 1, 'name' => 'John'], $data['data']['users'][0]);
        $this->assertEquals(['id' => 2, 'name' => 'Jane'], $data['data']['users'][1]);
        $this->assertEquals(['public' => 'visible'], $data['data']['config']);
    }

    public function testNestedObjectFiltering(): void
    {
        $nested = new class () {
            public string $visible = 'test';
            public string $secret = 'hidden';
        };

        $main = new class ($nested) {
            public object $nested;
            public string $public = 'data';
            public string $private = 'confidential';

            public function __construct(object $nested)
            {
                $this->nested = $nested;
            }
        };

        $response = new ApiResponse(
            success: true,
            data: $main,
            exclude: ['secret', 'private'],
        );

        $json = $response->toJson();
        $data = json_decode($json, true);

        $this->assertEquals(['visible' => 'test'], $data['data']['nested']);
        $this->assertEquals('data', $data['data']['public']);
        $this->assertArrayNotHasKey('private', $data['data']);
        $this->assertArrayNotHasKey('secret', $data['data']['nested']);
    }

    public function testEmptyExcludeList(): void
    {
        $response = new ApiResponse(
            success: true,
            data: ['id' => 1, 'password' => 'secret'],
            exclude: [], // Пустой список - ничего не исключаем
        );

        $json = $response->toJson();
        $data = json_decode($json, true);

        $this->assertEquals(['id' => 1, 'password' => 'secret'], $data['data']);
    }

    public function testNullDataWithExclude(): void
    {
        $response = new ApiResponse(
            success: true,
            data: null,
            exclude: ['password'], // Должно игнорироваться при null данных
        );

        $json = $response->toJson();
        $data = json_decode($json, true);

        $this->assertNull($data['data']);
    }

    public function testJsonOptions(): void
    {
        // Без форматирования и с экранированием Unicode
        $response = new ApiResponse(
            success: true,
            data: ['test' => 'тест'],
            jsonOptions: JSON_UNESCAPED_UNICODE, // Только без экранирования Unicode
        );

        $json = $response->toJson();

        // Проверяем что русские символы не экранированы
        $this->assertStringContainsString('"тест"', $json);

        // Проверяем что JSON корректный
        $data = json_decode($json, true);
        $this->assertEquals(['test' => 'тест'], $data['data']);
    }

    public function testJsonDepth(): void
    {
        // Создаем глубоко вложенную структуру
        $deepData = [];
        $current = &$deepData;
        for ($i = 0; $i < 100; $i++) {
            $current['level'] = $i;
            $current['next'] = [];
            $current = &$current['next'];
        }

        $response = new ApiResponse(
            success: true,
            data: $deepData,
            jsonDepth: 256, // Увеличиваем глубину
        );

        // Не должно вызывать исключение
        $json = $response->toJson();
        $this->assertJson($json);
    }

    public function testJsonEncodeError(): void
    {
        $this->expectException(RuntimeException::class);

        // Создаем циклическую ссылку, которая вызовет ошибку JSON
        $a = new stdClass();
        $b = new stdClass();
        $a->b = $b;
        $b->a = $a;

        $response = new ApiResponse(
            success: true,
            data: $a,
            jsonDepth: 1, // Маленькая глубина для гарантированной ошибки
        );

        $response->toJson();
    }

    public function testResponseCastingToString(): void
    {
        $response = ApiResponse::success(['test' => 'value']);

        // При приведении к строке должен вызываться рендеринг
        $output = (string) $response;
        $data = json_decode($output, true);

        $this->assertIsArray($data);
        $this->assertTrue($data['success']);
        $this->assertEquals(['test' => 'value'], $data['data']);
    }

    public function testTemplateRendering(): void
    {
        $response = ApiResponse::success(['test' => 'value']);

        // Имитируем рендеринг через шаблон
        ob_start();
        echo $response;
        $output = ob_get_clean();

        $this->assertJson($output);
        $data = json_decode($output, true);
        $this->assertTrue($data['success']);
    }
}
