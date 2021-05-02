<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%task_status}}`.
 */
class m210323_134019_add_translation_column_to_task_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%task_status}}', 'translation', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%task_status}}', 'translation');
    }
}
