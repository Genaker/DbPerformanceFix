<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Genaker\DbOrderFix\Cron;

use Magento\CatalogRule\Model\Indexer\Rule\UpdatedAtListProvider;
use Magento\CatalogRule\Model\Indexer\Product\ProductRuleIndexer;
use Magento\Catalog\Model\Indexer\Product\Price;


class PriceRuleIndexerFull
{

    protected $logger;
    protected $indexerRule;
    protected $indexerProduct;
    protected $indexerPrice;


    /**
     * Constructor
     *
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(\Psr\Log\LoggerInterface $logger,
                               UpdatedAtListProvider $indexerRule,
                               ProductRuleIndexer $indexerProduct,
                               Price $indexerPrice)
    {
        $this->logger = $logger;
        $this->indexerRule = $indexerRule;
        $this->indexerProduct = $indexerProduct;
        $this->indexerPrice = $indexerPrice;
    }

    /**
     * Execute the cron full reindex once at night 
     *
     * @return void
     */
    public function execute()
    {   
        $this->logger->addInfo("Cronjob Genaker PriceRuleIndexerFull is executed.");
      
        $this->logger->addInfo("Cronjob Genaker PriceRuleIndexerFull catalogrule_rule started.");
        $this->indexerRule->executeFull();
        $this->logger->addInfo("Cronjob Genaker PriceRuleIndexerFull catalogrule_rule finished.");
      
        $this->logger->addInfo("Cronjob Genaker PriceRuleIndexerFull catalogrule_product started.");
        $this->indexerProduct->executeFull();
        $this->logger->addInfo("Cronjob Genaker PriceRuleIndexerFull catalogrule_product finished.");
      
        $this->logger->addInfo("Cronjob Genaker PriceRuleIndexerFull catalog_product_price started.");
        $this->indexerPrice->executeFull();
        $this->logger->addInfo("Cronjob Genaker PriceRuleIndexerFull catalog_product_price finished.");
      
        $this->logger->addInfo("Cronjob Genaker PriceRuleIndexerFull finished.");
    }
}
