<?php

use yii\db\Migration;

/**
 * Class m221027_171719_task_files_column_name_fix_length
 */
class m221027_171719_task_files_column_name_fix_length extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('task_files', 'name', $this->char(255)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221027_171719_task_files_column_name_fix_length cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221027_171719_task_files_column_name_fix_length cannot be reverted.\n";

        return false;
    }
    */
}
