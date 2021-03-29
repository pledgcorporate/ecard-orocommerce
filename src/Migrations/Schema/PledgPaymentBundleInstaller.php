<?php

namespace Pledg\Bundle\PaymentBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
class PledgPaymentBundleInstaller implements Installation
{
    /**
     * {@inheritdoc}
     */
    public function getMigrationVersion()
    {
        return 'v1_0';
    }

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        /** Tables generation **/
        $this->createOroIntegrationTransportTable($schema);
        $this->createOroPledgTransLabelTable($schema);
        $this->createOroPledgTransShortLabelTable($schema);

        /** Foreign keys generation **/
        $this->addOroPledgTransLabelForeignKeys($schema);
        $this->addOroPledgTransShortLabelForeignKeys($schema);
    }

    /**
     * Create oro_integration_transport table
     *
     * @param Schema $schema
     */
    protected function createOroIntegrationTransportTable(Schema $schema)
    {
        $table = $schema->getTable('oro_integration_transport');
        $table->addColumn('pledg_client_identifier', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('pledg_client_secret', 'string', ['notnull' => false, 'length' => 255]);
    }

    /**
     * Create oro_pledg_trans_label table
     *
     * @param Schema $schema
     */
    protected function createOroPledgTransLabelTable(Schema $schema)
    {
        $table = $schema->createTable('oro_pledg_trans_label');
        $table->addColumn('transport_id', 'integer', []);
        $table->addColumn('localized_value_id', 'integer', []);
        $table->addUniqueIndex(['localized_value_id'], 'uniq_d8e57d1ceb576e89');
        $table->addIndex(['transport_id'], 'idx_d8e57d1c9909c13f', []);
        $table->setPrimaryKey(['transport_id', 'localized_value_id']);
    }

    /**
     * Create oro_pledg_trans_short_label table
     *
     * @param Schema $schema
     */
    protected function createOroPledgTransShortLabelTable(Schema $schema)
    {
        $table = $schema->createTable('oro_pledg_trans_short_label');
        $table->addColumn('transport_id', 'integer', []);
        $table->addColumn('localized_value_id', 'integer', []);
        $table->addUniqueIndex(['localized_value_id'], 'uniq_81e99963eb576e89');
        $table->addIndex(['transport_id'], 'idx_81e999639909c13f', []);
        $table->setPrimaryKey(['transport_id', 'localized_value_id']);
    }



    /**
     * Add oro_pledg_trans_label foreign keys.
     *
     * @param Schema $schema
     */
    protected function addOroPledgTransLabelForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('oro_pledg_trans_label');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_fallback_localization_val'),
            ['localized_value_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => 'CASCADE']
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_integration_transport'),
            ['transport_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => 'CASCADE']
        );
    }

    /**
     * Add oro_pledg_trans_short_label foreign keys.
     *
     * @param Schema $schema
     */
    protected function addOroPledgTransShortLabelForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('oro_pledg_trans_short_label');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_fallback_localization_val'),
            ['localized_value_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => 'CASCADE']
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_integration_transport'),
            ['transport_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => 'CASCADE']
        );
    }
}
