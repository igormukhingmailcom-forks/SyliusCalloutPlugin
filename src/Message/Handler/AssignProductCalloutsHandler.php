<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Message\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Setono\DoctrineORMBatcher\Query\QueryRebuilder;
use Setono\SyliusCalloutPlugin\Message\Command\AssignProductCallouts;
use Setono\SyliusCalloutPlugin\Model\ProductInterface;
use Setono\SyliusCalloutPlugin\Provider\CalloutProviderInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class AssignProductCalloutsHandler implements MessageHandlerInterface
{
    /** @var CalloutProviderInterface */
    private $calloutProvider;

    /** @var EntityManagerInterface */
    private $productManager;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    public function __construct(
        CalloutProviderInterface $calloutProvider,
        EntityManagerInterface $productManager,
        ProductRepositoryInterface $productRepository
    ) {
        $this->calloutProvider = $calloutProvider;
        $this->productManager = $productManager;
        $this->productRepository = $productRepository;
    }

    public function __invoke(AssignProductCallouts $message): void
    {
        if (!$this->productRepository instanceof EntityRepository) {
            return;
        }

        $queryRebuilder = new QueryRebuilder();
        $query = $queryRebuilder->rebuild($this->productManager, $message->getBatch());

        /** @var ProductInterface[] $products */
        $products = $query->getResult();

        if (count($products) === 0) {
            return;
        }

        foreach ($products as $product) {
            $callouts = $this->calloutProvider->getCallouts($product);

            $product->setCallouts($callouts);
        }

        $this->productManager->flush();
    }
}
