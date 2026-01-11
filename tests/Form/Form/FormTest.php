<?php

declare(strict_types=1);

namespace OlegV\WallKit\Tests\Form\Form;

use OlegV\Exceptions\RenderException;
use OlegV\WallKit\Base\Base;
use OlegV\WallKit\Form\Button\Button;
use OlegV\WallKit\Form\Form\Form;
use OlegV\WallKit\Form\Input\Input;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use stdClass;

/**
 * Тест компонента Form
 */
class FormTest extends TestCase
{
    public function testFormClassExists(): void
    {
        $this->assertTrue(class_exists(Form::class));
    }

    public function testFormIsReadonly(): void
    {
        $reflection = new ReflectionClass(Form::class);
        $this->assertTrue($reflection->isReadOnly());
    }

    public function testFormExtendsBase(): void
    {
        $form = new Form(fields: []);
        $this->assertInstanceOf(Base::class, $form);
    }

    public function testFormConstructorRequiresFields(): void
    {
        $form = new Form(fields: []);
        $this->assertInstanceOf(Form::class, $form);
        $this->assertEmpty($form->fields);
    }

    public function testFormRendersEmptyFormWithNoFields(): void
    {
        $form = new Form(fields: []);
        $output = $form->renderOriginal();

        $this->assertStringContainsString('<form', $output);
        $this->assertStringContainsString('method="POST"', $output);
        $this->assertStringContainsString('class="wallkit-form"', $output);
    }

    public function testFormRendersWithStringableFields(): void
    {
        $input = new Input(name: 'test');
        $button = new Button('Submit');

        $form = new Form(fields: [$input, $button]);
        $output = $form->renderOriginal();

        $this->assertStringContainsString('<input', $output);
        $this->assertStringContainsString('<button', $output);
        $this->assertStringContainsString('name="test"', $output);
        $this->assertStringContainsString('Submit', $output);
    }

    public function testFormRendersCsrfTokenForPostMethod(): void
    {
        $form = new Form(
            fields: [new Button('Submit')],
            method: 'POST',
            csrfToken: 'test_token_123',
        );
        $output = $form->renderOriginal();

        $this->assertStringContainsString('name="_token"', $output);
        $this->assertStringContainsString('value="test_token_123"', $output);
    }

    public function testFormDoesNotRenderCsrfTokenForGetMethod(): void
    {
        $form = new Form(
            fields: [new Button('Submit')],
            method: 'GET',
            csrfToken: 'test_token_123',
        );
        $output = $form->renderOriginal();

        $this->assertStringNotContainsString('name="_token"', $output);
        $this->assertStringNotContainsString('test_token_123', $output);
    }

    public function testFormRendersAllHttpMethodsCorrectly(): void
    {
        $methods = ['POST', 'GET', 'PUT', 'PATCH', 'DELETE'];

        foreach ($methods as $method) {
            $form = new Form(
                fields: [new Button('Submit')],
                method: $method,
            );
            $output = $form->renderOriginal();

            $this->assertStringContainsString('method="' . strtoupper($method) . '"', $output);
        }
    }

    public function testFormRendersAttributesCorrectly(): void
    {
        $form = new Form(
            fields: [new Button('Submit')],
            action: '/submit',
            id: 'test-form',
            name: 'testForm',
            enctype: 'multipart/form-data',
            target: '_blank',
            classes: ['custom-class', 'another-class'],
            attributes: [
                'data-test' => 'value',
                'aria-label' => 'Test form',
            ],
        );

        $output = $form->renderOriginal();

        $this->assertStringContainsString('id="test-form"', $output);
        $this->assertStringContainsString('name="testForm"', $output);
        $this->assertStringContainsString('action="/submit"', $output);
        $this->assertStringContainsString('target="_blank"', $output);
        $this->assertStringContainsString('enctype="multipart/form-data"', $output);
        $this->assertStringContainsString('class="wallkit-form custom-class another-class"', $output);
        $this->assertStringContainsString('data-test="value"', $output);
        $this->assertStringContainsString('aria-label="Test form"', $output);
    }

    public function testFormHandlesBooleanAttributes(): void
    {
        // Test with novalidate
        $form = new Form(
            fields: [new Button('Submit')],
            novalidate: true,
        );
        $output = $form->renderOriginal();
        $this->assertStringContainsString('novalidate', $output);

        // Test with autocomplete off
        $form = new Form(
            fields: [new Button('Submit')],
            autoComplete: false,
        );
        $output = $form->renderOriginal();
        $this->assertStringContainsString('autocomplete="off"', $output);
    }

    public function testGetFormClassesMethod(): void
    {
        $form = new Form(
            fields: [],
            classes: ['test-class', 'another-class'],
        );

        $classes = $form->getFormClasses();

        $this->assertContains('wallkit-form', $classes);
        $this->assertContains('test-class', $classes);
        $this->assertContains('another-class', $classes);
        $this->assertCount(3, $classes);
    }

    public function testGetFormAttributesMethod(): void
    {
        $form = new Form(
            fields: [],
            action: '/test',
            method: 'POST',
            id: 'form-id',
            name: 'formName',
            target: '_self',
        );

        $attributes = $form->getFormAttributes();

        $this->assertEquals('form-id', $attributes['id']);
        $this->assertEquals('formName', $attributes['name']);
        $this->assertEquals('/test', $attributes['action']);
        $this->assertEquals('POST', $attributes['method']);
        $this->assertEquals('_self', $attributes['target']);
        $this->assertStringContainsString('wallkit-form', (string) $attributes['class']);
    }

    public function testFormAttributesFilterNullValues(): void
    {
        $form = new Form(
            fields: [],
            id: null,
            name: null,
            enctype: null,
            target: null,
        );

        $attributes = $form->getFormAttributes();

        $this->assertArrayNotHasKey('id', $attributes);
        $this->assertArrayNotHasKey('name', $attributes);
        $this->assertArrayNotHasKey('target', $attributes);
        $this->assertArrayNotHasKey('enctype', $attributes);
    }

    public function testFormWithCustomEnctype(): void
    {
        $form = new Form(
            fields: [new Input(name: 'file', type: 'file')],
            enctype: 'multipart/form-data',
        );
        $output = $form->renderOriginal();

        $this->assertStringContainsString('enctype="multipart/form-data"', $output);
    }

    public function testFormCastsToString(): void
    {
        $form = new Form(fields: [new Button('Test')]);
        $stringForm = (string) $form;

        $this->assertStringContainsString('<form', $stringForm);
        $this->assertStringContainsString('</form>', $stringForm);
        $this->assertStringContainsString('<button', $stringForm);
    }

    public function testFormWithNonStringableFieldThrowsException(): void
    {
        $this->expectException(RenderException::class);

        /** @noinspection PhpParamsInspection */
        $form = new Form(fields: [new stdClass()]);
        $form->renderOriginal();
    }

    public function testFormMessagesContainerExistsInTemplate(): void
    {
        $form = new Form(fields: [new Button('Submit')]);
        $output = $form->renderOriginal();

        $this->assertStringContainsString('wallkit-form__messages', $output);
        $this->assertStringContainsString('<div class="wallkit-form__messages"></div>', $output);
    }

    public function testFormRendersInputFieldsCorrectly(): void
    {
        $input1 = new Input(name: 'username', value: 'testuser');
        $input2 = new Input(name: 'email', value: 'test@example.com', type: 'email');
        $button = new Button('Submit');

        $form = new Form(fields: [$input1, $input2, $button]);
        $output = $form->renderOriginal();

        $this->assertStringContainsString('name="username"', $output);
        $this->assertStringContainsString('value="testuser"', $output);
        $this->assertStringContainsString('name="email"', $output);
        $this->assertStringContainsString('value="test@example.com"', $output);
        $this->assertStringContainsString('type="email"', $output);
    }

    public function testFormMethodIsUppercased(): void
    {
        $form = new Form(
            fields: [new Button('Submit')],
            method: 'post',
        );

        $attributes = $form->getFormAttributes();
        $this->assertEquals('POST', $attributes['method']);

        $output = $form->renderOriginal();
        $this->assertStringContainsString('method="POST"', $output);
    }

    public function testFormWithComplexFieldsStructure(): void
    {
        $fields = [
            new Input(name: 'title', placeholder: 'Enter title'),
            new Input(name: 'description', placeholder: 'Enter description'),
            new Button('Save', type: 'submit'),
            new Button('Cancel', type: 'button'),
        ];

        $form = new Form(
            fields: $fields,
            action: '/save',
            method: 'POST',
            csrfToken: 'abc123',
            classes: ['form-inline'],
            attributes: ['data-validate' => 'true'],
        );

        $output = $form->renderOriginal();

        // Проверяем все основные элементы
        $this->assertStringContainsString('action="/save"', $output);
        $this->assertStringContainsString('method="POST"', $output);
        $this->assertStringContainsString('name="_token"', $output);
        $this->assertStringContainsString('value="abc123"', $output);
        $this->assertStringContainsString('class="wallkit-form form-inline"', $output);
        $this->assertStringContainsString('data-validate="true"', $output);
        $this->assertStringContainsString('placeholder="Enter title"', $output);
        $this->assertStringContainsString('placeholder="Enter description"', $output);
        $this->assertStringContainsString('Save', $output);
        $this->assertStringContainsString('Cancel', $output);
    }
}
