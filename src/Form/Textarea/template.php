<?php
/** @var Textarea $this */

use OlegV\WallKit\Form\Textarea\Textarea; ?>
<textarea <?= $this->attr($this->getTextareaAttributes()) ?>><?= $this->e($this->value) ?></textarea>