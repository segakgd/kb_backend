<?php

namespace App\Service\Admin\Ecommerce\ProductCategory;

class ProductCategoryService implements ProductCategoryServiceInterface
{
    public function getAvailableCategory(): array
    {
        return [
            'магнитолы',
            'динамики',
            'чайники',
        ];
    }
}
