<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Genaker\DbOrderFix\Rewrite;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;

/**
 * Fix Provides latest updated entities ids list
 */
class UpdatedIdListProvider extends \Magento\Sales\Model\ResourceModel\Provider\UpdatedIdListProvider
{

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var AdapterInterface
     */
    private $connection;

    /**
     * NotSyncedDataProvider constructor.
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
        parent::__construct($resourceConnection)
    }

    /**
     * @inheritdoc
     */
    public function getIds($mainTableName, $gridTableName)
    {
        $mainTableName = $this->resourceConnection->getTableName($mainTableName);
        $gridTableName = $this->resourceConnection->getTableName($gridTableName);
        $select = $this->getConnection()->select()
            ->from($mainTableName, [$mainTableName . '.entity_id'])
            ->joinLeft(
                [$gridTableName => $gridTableName],
                sprintf(
                    '%s.%s = %s.%s',
                    $mainTableName,
                    'entity_id',
                    $gridTableName,
                    'entity_id'
                ),
                []
            )
            ->where($gridTableName . '.entity_id IS NULL AND ' . $mainTableName . '.updated_at >= (CURDATE() - INTERVAL 1 HOUR)');

        return $this->getConnection()->fetchAll($select, [], \Zend_Db::FETCH_COLUMN);
    }

    /**
     * Returns connection.
     *
     * @return AdapterInterface
     */
    private function getConnection()
    {
        if (!$this->connection) {
            $this->connection = $this->resourceConnection->getConnection('sales');
        }

        return $this->connection;
    }
}
