<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Factory;

use Setono\SyliusCalloutPlugin\Checker\Rule\HasTaxonCalloutRuleChecker;
use Setono\SyliusCalloutPlugin\Model\CalloutRuleInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

class CalloutRuleFactory implements CalloutRuleFactoryInterface
{
    /** @var FactoryInterface */
    private $decoratedFactory;

    public function __construct(FactoryInterface $factory)
    {
        $this->decoratedFactory = $factory;
    }

    public function createHasTaxon(array $taxons): CalloutRuleInterface
    {
        return $this->createCalloutRule(HasTaxonCalloutRuleChecker::TYPE, ['taxons' => array_map(function (TaxonInterface $taxon) {
            return $taxon->getCode();
        }, $taxons)]);
    }

    public function createNew(): CalloutRuleInterface
    {
        /** @var CalloutRuleInterface $rule */
        $rule = $this->decoratedFactory->createNew();

        return $rule;
    }

    private function createCalloutRule(string $type, array $configuration): CalloutRuleInterface
    {
        $rule = $this->createNew();

        $rule->setType($type);
        $rule->setConfiguration($configuration);

        return $rule;
    }
}
