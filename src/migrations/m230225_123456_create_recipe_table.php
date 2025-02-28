<?php
use yii\db\Migration;

class m230225_123456_create_recipe_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'ENGINE=InnoDB CHARSET=utf8mb4 COMMENT="食谱表"';
        }

        $this->createTable('{{%recipe}}', [
            'id' => $this->primaryKey()->unsigned()->comment('主键ID'),
            'title' => $this->string(200)->notNull()->defaultValue('')->comment('食谱标题'),
            'cover_img' => $this->string(100)->notNull()->defaultValue('')->comment('封面图片'),
            'type' => $this->smallInteger(3)->defaultValue(null)->comment('类型'),
            'recommend' => $this->tinyInteger(1)->notNull()->defaultValue(1)->comment('1：不推荐 2：推荐'),
            'detail' => $this->text()->notNull()->comment('详细内容'),
            'user_id' => $this->integer(11)->notNull()->defaultValue(0)->comment('用户ID'),
            'created_at' => $this->timestamp()->defaultValue(null)->comment('创建时间'),
            'updated_at' => $this->timestamp()->defaultValue(null)->comment('更新时间'),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%recipe}}');
    }
}
