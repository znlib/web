<?php

/**
 * @var View $this
 * @var AttributeEntity[] $attributes
 * @var object $filterModel
 */

use Symfony\Component\PropertyAccess\PropertyAccess;
use ZnCore\Base\Legacy\Yii\Helpers\Inflector;
use ZnLib\Web\View\View;
use ZnLib\Web\Widgets\Filter\FilterGenerator;
use ZnLib\Web\Widgets\Format\Entities\AttributeEntity;

$propertyAccessor = PropertyAccess::createPropertyAccessor();

?>

<form id="collection-filter-form" action="" method="get">
    <tr>
        <?php foreach ($attributes as $attributeEntity): ?>
            <th>
                <?php
                $attributeName = $attributeEntity->getAttributeName();
                $attributeNameCamelCase = Inflector::variablize($attributeName);
                if (property_exists($filterModel, $attributeNameCamelCase)) {
                    $value = $propertyAccessor->getValue($filterModel, $attributeNameCamelCase);
                    $filterDefinition = $attributeEntity->getFilter();
                    $isDefinition = is_array($filterDefinition) && isset($filterDefinition['class']) || is_string($filterDefinition);
                    if ($isDefinition) {
                        echo FilterGenerator::generateByDefinition($filterDefinition, $attributeName, $value);
                    } else {
                        if (!isset($filterDefinition['type'])) {
                            $filterDefinition['type'] = 'text';
                        }
                        echo FilterGenerator::generateByType($filterDefinition, $attributeName, $value);
                    }
                }
                ?>
            </th>
        <?php endforeach; ?>
    </tr>
</form>

<script>
    function submitForm(self, event) {
        if(event.keyCode === 13) {
            console.log(event.keyCode);
            self.form.submit();
        }
    }
</script>
