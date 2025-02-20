<?php

use yii\db\Migration;

class m250218_155705_alter_table_user_add_column_likes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%article}}', 'likes', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250218_155705_alter_table_user_add_column_likes cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250218_155705_alter_table_user_add_column_likes cannot be reverted.\n";

        return false;
    }
    */
}
