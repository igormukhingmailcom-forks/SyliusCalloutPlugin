<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Factory;

use Setono\SyliusCalloutPlugin\Model\CalloutRuleInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

interface CalloutRuleFactoryInterface extends FactoryInterface
{
    // todo remove this
    public function createHasTaxon(array $taxons): CalloutRuleInterface;
}
