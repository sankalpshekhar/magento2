<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\ConfigurableProduct\Ui\DataProvider;

/**
 * Class Attributes
 */
class Attributes extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection
     */
    protected $collection;

    /**
     * @var \Magento\ConfigurableProduct\Model\ConfigurableAttributeHandler
     */
    protected $configurableAttributeHandler;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param \Magento\ConfigurableProduct\Model\ConfigurableAttributeHandler $configurableAttributeHandler
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Magento\ConfigurableProduct\Model\ConfigurableAttributeHandler $configurableAttributeHandler,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->configurableAttributeHandler = $configurableAttributeHandler;
        $this->collection = $configurableAttributeHandler->getApplicableAttributes();
    }

    /**
     * Get Collection
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        $items = [];
        $skippedItems = 0;
        foreach ($this->getCollection()->getItems() as $attribute) {
            if ($this->configurableAttributeHandler->isAttributeApplicable($attribute)) {
                $items[] = $attribute->toArray();
            } else {
                $skippedItems++;
            }
        }
        return [
            'totalRecords' => $this->collection->getSize() - $skippedItems,
            'items' => $items
        ];
    }
}
