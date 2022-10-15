<?php

use yii\db\Migration;

/**
 * Class m221013_174820_new_column_is_remote_for_tasks
 */
class m221013_174820_new_column_is_remote_for_tasks extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tasks', 'is_remote', $this->tinyInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221013_174820_new_column_is_remote_for_tasks cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221013_174820_new_column_is_remote_for_tasks cannot be reverted.\n";

        return false;
    }
    */
}
