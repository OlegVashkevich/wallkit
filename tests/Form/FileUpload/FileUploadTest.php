<?php

declare(strict_types=1);

namespace OlegV\WallKit\Tests\Form\FileUpload;

use OlegV\Exceptions\RenderException;
use OlegV\WallKit\Base\Base;
use OlegV\WallKit\Form\FileUpload\FileUpload;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Тест компонента FileUpload
 */
class FileUploadTest extends TestCase
{
    public function testFileUploadClassExists(): void
    {
        $this->assertTrue(class_exists(FileUpload::class));
    }

    public function testFileUploadIsReadonly(): void
    {
        $reflection = new ReflectionClass(FileUpload::class);
        $this->assertTrue($reflection->isReadOnly());
    }

    public function testFileUploadExtendsBase(): void
    {
        $upload = new FileUpload(
            name: 'test',
            label: 'Test Label',
        );

        $this->assertInstanceOf(Base::class, $upload);
    }

    public function testFileUploadRendersHtml(): void
    {
        $upload = new FileUpload(
            name: 'avatar',
            label: 'Загрузите файл',
            placeholder: 'Выберите файл...',
            helpText: 'Максимальный размер 5MB',
            error: 'Ошибка загрузки',
        );

        $html = (string) $upload;

        $this->assertStringContainsString('wallkit-fileupload', $html);
        $this->assertStringContainsString('Загрузите файл', $html);
        $this->assertStringContainsString('Выберите файл...', $html);
        $this->assertStringContainsString('Максимальный размер 5MB', $html);
        $this->assertStringContainsString('Ошибка загрузки', $html);
        $this->assertStringContainsString('type="file"', $html);
    }

    public function testRequiredParametersValidation(): void
    {
        // Тест на пустое имя
        try {
            $upload = new FileUpload(
                name: '',
                label: 'Test Label',
            );
            $upload->renderOriginal();
            $this->fail('Должно быть выброшено исключение для пустого имени');
        } catch (RenderException $e) {
            $this->assertStringContainsString('Имя поля обязательно', $e->getMessage());
        }

        // Тест на пустую подпись
        try {
            $upload = new FileUpload(
                name: 'test',
                label: '',
            );
            $upload->renderOriginal();
            $this->fail('Должно быть выброшено исключение для пустой подписи');
        } catch (RenderException $e) {
            $this->assertStringContainsString('Подпись поля обязательна', $e->getMessage());
        }
    }

    public function testNumericParametersValidation(): void
    {
        // Тест на отрицательный maxSize
        try {
            $upload = new FileUpload(
                name: 'test',
                label: 'Test Label',
                maxSize: -1,
            );
            $upload->renderOriginal();
            $this->fail('Должно быть выброшено исключение для отрицательного maxSize');
        } catch (RenderException $e) {
            $this->assertStringContainsString('Максимальный размер должен быть положительным числом', $e->getMessage());
        }

        // Тест на нулевой maxSize
        try {
            $upload = new FileUpload(
                name: 'test',
                label: 'Test Label',
                maxSize: 0,
            );
            $upload->renderOriginal();
            $this->fail('Должно быть выброшено исключение для нулевого maxSize');
        } catch (RenderException $e) {
            $this->assertStringContainsString('Максимальный размер должен быть положительным числом', $e->getMessage());
        }

        // Тест на отрицательный maxFiles
        try {
            $upload = new FileUpload(
                name: 'test',
                label: 'Test Label',
                maxFiles: -1,
            );
            $upload->renderOriginal();
            $this->fail('Должно быть выброшено исключение для отрицательного maxFiles');
        } catch (RenderException $e) {
            $this->assertStringContainsString(
                'Максимальное количество файлов должно быть положительным числом',
                $e->getMessage(),
            );
        }

        // Тест на отрицательный maxWidth
        try {
            $upload = new FileUpload(
                name: 'test',
                label: 'Test Label',
                maxWidth: -100,
            );
            $upload->renderOriginal();
            $this->fail('Должно быть выброшено исключение для отрицательного maxWidth');
        } catch (RenderException $e) {
            $this->assertStringContainsString('Максимальная ширина должна быть положительным числом', $e->getMessage());
        }

        // Тест на отрицательный maxHeight
        try {
            $upload = new FileUpload(
                name: 'test',
                label: 'Test Label',
                maxHeight: -100,
            );
            $upload->renderOriginal();
            $this->fail('Должно быть выброшено исключение для отрицательного maxHeight');
        } catch (RenderException $e) {
            $this->assertStringContainsString('Максимальная высота должна быть положительным числом', $e->getMessage());
        }
    }

    public function testValidParameters(): void
    {
        // Все параметры валидны - исключений быть не должно
        $upload = new FileUpload(
            name: 'test',
            label: 'Test Label',
            maxSize: 1024,
            maxFiles: 5,
            maxWidth: 1920,
            maxHeight: 1080,
        );

        $html = (string) $upload;

        $this->assertStringContainsString('data-max-size="1024"', $html);
        $this->assertStringContainsString('data-max-files="5"', $html);
        $this->assertStringContainsString('data-max-width="1920"', $html);
        $this->assertStringContainsString('data-max-height="1080"', $html);
    }

    public function testGetIdAutoGeneration(): void
    {
        $upload = new FileUpload(
            name: 'test',
            label: 'Test Label',
        );

        $id = $upload->getId();
        print_r($id);
        $this->assertStringStartsWith('fileupload-', $id);
        $this->assertEquals(24, strlen($id)); // 'fileupload-' + 13 символов uniqid
    }

    public function testGetIdCustom(): void
    {
        $upload = new FileUpload(
            name: 'test',
            label: 'Test Label',
            id: 'custom-file-upload',
        );

        $this->assertEquals('custom-file-upload', $upload->getId());
    }

    public function testGetInputAttributes(): void
    {
        $upload = new FileUpload(
            name: 'documents',
            label: 'Загрузите документы',
            required: true,
            disabled: false,
            multiple: true,
            accept: '.pdf,.doc',
            maxSize: 10485760, // 10MB
            maxFiles: 5,
            classes: ['custom-class'],
            attributes: ['data-custom' => 'value'],
        );

        $attributes = $upload->getInputAttributes();

        // Обязательные атрибуты
        $this->assertStringStartsWith('fileupload-', $attributes['id']);
        $this->assertEquals('documents[]', $attributes['name']);
        $this->assertEquals('file', $attributes['type']);
        $this->assertStringContainsString('wallkit-fileupload__field', $attributes['class']);
        $this->assertStringContainsString('custom-class', $attributes['class']);

        // Булевые атрибуты
        $this->assertTrue($attributes['required']);
        $this->assertTrue($attributes['multiple']);
        $this->assertArrayNotHasKey('disabled', $attributes); // false = атрибут не добавляется

        // Data атрибуты
        $this->assertEquals(10485760, $attributes['data-max-size']);
        $this->assertEquals(5, $attributes['data-max-files']);

        // Кастомные атрибуты
        $this->assertEquals('value', $attributes['data-custom']);

        // Accept
        $this->assertEquals('.pdf,.doc', $attributes['accept']);
    }

    public function testGetInputAttributesSingleFile(): void
    {
        $upload = new FileUpload(
            name: 'avatar',
            label: 'Загрузите аватар',
            multiple: false,
        );

        $attributes = $upload->getInputAttributes();

        $this->assertEquals('avatar', $attributes['name']);
        $this->assertArrayNotHasKey('multiple', $attributes);
    }

    public function testGetInputAttributesDisabled(): void
    {
        $upload = new FileUpload(
            name: 'test',
            label: 'Test Label',
            disabled: true,
        );

        $attributes = $upload->getInputAttributes();

        $this->assertTrue($attributes['disabled']);
    }

    public function testGetInputAttributesNullValues(): void
    {
        $upload = new FileUpload(
            name: 'test',
            label: 'Test Label',
            maxSize: null,
            maxFiles: null,
            maxWidth: null,
            maxHeight: null,
        );

        $attributes = $upload->getInputAttributes();

        $this->assertArrayNotHasKey('data-max-size', $attributes);
        $this->assertArrayNotHasKey('data-max-files', $attributes);
        $this->assertArrayNotHasKey('data-max-width', $attributes);
        $this->assertArrayNotHasKey('data-max-height', $attributes);
    }

    public function testLabelRequiredIndicator(): void
    {
        $upload = new FileUpload(
            name: 'test',
            label: 'Обязательное поле',
            required: true,
        );

        $html = (string) $upload;

        $this->assertStringContainsString('wallkit-fileupload__required', $html);
        $this->assertStringContainsString('*', $html);

        // Проверяем что для необязательного поля нет индикатора
        $upload2 = new FileUpload(
            name: 'test2',
            label: 'Необязательное поле',
            required: false,
        );

        $html2 = (string) $upload2;
        $this->assertStringNotContainsString('wallkit-fileupload__required', $html2);
    }

    public function testMultipleFilesWithArrayName(): void
    {
        $upload = new FileUpload(
            name: 'attachments',
            label: 'Множественная загрузка',
            multiple: true,
        );

        $html = (string) $upload;

        $this->assertStringContainsString('name="attachments[]"', $html);
    }

    public function testAcceptAttribute(): void
    {
        $upload = new FileUpload(
            name: 'images',
            label: 'Загрузите изображения',
            accept: 'image/*',
        );

        $html = (string) $upload;

        $this->assertStringContainsString('accept="image/*"', $html);
    }

    public function testErrorAndHelpTextRendering(): void
    {
        $upload = new FileUpload(
            name: 'test',
            label: 'Test Label',
            helpText: 'Это подсказка',
            error: 'Это ошибка',
        );

        $html = (string) $upload;

        $this->assertStringContainsString('wallkit-fileupload__help', $html);
        $this->assertStringContainsString('Это подсказка', $html);
        $this->assertStringContainsString('wallkit-fileupload__error', $html);
        $this->assertStringContainsString('Это ошибка', $html);

        // Проверяем что без ошибки и подсказки элементы не рендерятся
        $upload2 = new FileUpload(
            name: 'test2',
            label: 'Test Label 2',
        );

        $html2 = (string) $upload2;
        $this->assertStringNotContainsString('wallkit-fileupload__help', $html2);
        $this->assertStringNotContainsString('wallkit-fileupload__error', $html2);
    }

    public function testPlaceholderRendering(): void
    {
        $upload = new FileUpload(
            name: 'test',
            label: 'Test Label',
            placeholder: 'Перетащите файлы сюда или нажмите для выбора',
        );

        $html = (string) $upload;

        $this->assertStringContainsString('wallkit-fileupload__placeholder', $html);
        $this->assertStringContainsString('Перетащите файлы сюда или нажмите для выбора', $html);

        // Без плейсхолдера
        $upload2 = new FileUpload(
            name: 'test2',
            label: 'Test Label 2',
        );

        $html2 = (string) $upload2;
        $this->assertStringNotContainsString('wallkit-fileupload__placeholder', $html2);
    }

    public function testTrimmingInValidation(): void
    {
        // Проверяем что пробелы в начале/конце обрезаются при валидации
        $upload = new FileUpload(
            name: '  valid_name  ',
            label: '  Valid Label  ',
        );

        // Не должно быть исключения
        $html = (string) $upload;
        $this->assertStringContainsString('valid_name', $html);
        $this->assertStringContainsString('Valid Label', $html);
    }
}
