<?php

declare(strict_types=1);

namespace OlegV\WallKit\Demo\DemoComponentCard;

use OlegV\Brick;
use OlegV\Traits\WithHelpers;
use OlegV\Traits\WithInheritance;
use OlegV\Traits\WithStrictHelpers;
use OlegV\WallKit\Base\Base;
use ReflectionClass;

readonly class DemoComponentCard extends Base
{
    use WithHelpers;
    use WithStrictHelpers;
    use WithInheritance;

    public function __construct(
        public string $title,
        public Brick|Base|array $component,
        public string $description,
        public string $badgeText,
        public string $badgeType = 'default',
        public ?string $note = null,
    ) {}

    protected function getHtml(): string
    {
        if (is_array($this->component)) {
            return implode('', $this->component);
        }
        return (string)$this->component;
    }

    public function getBadgeClasses(): array
    {
        $classes = ['wallkit-demo-component-card__badge'];
        $classes[] = "wallkit-demo-component-card__badge--$this->badgeType";
        return $classes;
    }

    /**
     * Рекурсивное воссоздание конструктора через рефлексию с защитой от циклических ссылок
     *
     * @param  object  $obj
     * @param  int  $depth
     * @param  array  $processed
     *
     * @return string
     */
    protected function objectToConstructorStringNonDefaults(
        object $obj,
        int $depth = 1,
        array &$processed = [],
    ): string {
        // Защита от циклических ссылок
        $objId = spl_object_id($obj);
        if (isset($processed[$objId])) {
            return '/* circular reference */';
        }
        $processed[$objId] = true;

        $reflection = new ReflectionClass($obj);
        $className = str_replace($reflection->getNamespaceName().'\\', '', $reflection->getName());
        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            return "new $className()";
        }

        $args = [];
        $indent = str_repeat('    ', $depth);

        foreach ($constructor->getParameters() as $param) {
            $paramName = $param->getName();

            if (!$reflection->hasProperty($paramName)) {
                continue;
            }

            $property = $reflection->getProperty($paramName);
            $currentValue = $property->getValue($obj);

            // Проверяем, отличается ли от значения по умолчанию
            $isDefault = false;
            if ($param->isDefaultValueAvailable()) {
                $defaultValue = $param->getDefaultValue();
                $isDefault = $currentValue === $defaultValue;
            }

            // Добавляем только если не равно значению по умолчанию
            if (!$param->isDefaultValueAvailable() || !$isDefault) {
                $formattedValue = $this->formatValue($currentValue, $depth, $processed);
                $args[] = "$indent$paramName: $formattedValue";
            }
        }

        if (empty($args)) {
            return "new $className()";
        }

        $outerIndent = str_repeat('    ', $depth - 1);
        return "new $className(\n".implode(",\n", $args)."\n$outerIndent)";
    }

    private function formatValue($value, int $depth = 1, array &$processed = []): string
    {
        if (is_string($value)) {
            return "'".addslashes($value)."'";
        }
        if (is_int($value) || is_float($value)) {
            return (string)$value;
        }
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        if (is_null($value)) {
            return 'null';
        }
        if (is_array($value)) {
            return $this->formatArray($value, $depth, $processed);
        }
        if (is_object($value)) {
            // Рекурсивно обрабатываем вложенные объекты
            return $this->objectToConstructorStringNonDefaults($value, $depth + 1, $processed);
        }
        return var_export($value, true);
    }

    private function formatArray(array $arr, int $depth = 1, array &$processed = []): string
    {
        if (empty($arr)) {
            return '[]';
        }

        $items = [];
        $indent = str_repeat('    ', $depth + 1);

        foreach ($arr as $key => $value) {
            $formattedKey = is_string($key) ? "'$key' => " : '';
            $formattedValue = $this->formatValue($value, $depth + 1, $processed);
            $items[] = "$indent$formattedKey$formattedValue";
        }

        $outerIndent = str_repeat('    ', $depth);
        return "[\n".implode(",\n", $items)."\n$outerIndent]";
    }
}