<?php

namespace App\Enum;

enum TargetEnum: string
{
    case Main = 'main';
    case Cart = 'cart';

    // new
    case GreetingChain = 'greeting.chain';
    case StartChain = 'start.chain';
    case ProductCategoryChain = 'product.category.chain';
    case ProductsByCategoryChain = 'products.by.category.chain';
    case VariantsProductChain = 'variants.product.chain';
    case VariantProductChain = 'variant.product.chain';
    case FinishChain = 'finish.chain';
}
