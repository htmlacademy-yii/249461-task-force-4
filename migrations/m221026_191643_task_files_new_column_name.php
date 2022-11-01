<?php

use yii\db\Migration;

/**
 * Class m221026_191643_task_files_new_column_name
 */
class m221026_191643_task_files_new_column_name extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('task_files', 'name', $this->char()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221026_191643_task_files_new_column_name cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221026_191643_task_files_new_column_name cannot be reverted.\n";

        return false;
    }
    */
}
