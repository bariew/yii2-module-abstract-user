<?php

use yii\db\Schema;
use yii\db\Migration;
use bariew\userAbstractModule\models\Auth;

class m150407_062517_user_auth extends Migration
{
    public function up()
    {
        $this->createTable(Auth::tableName(), [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER,
            'name' => Schema::TYPE_SMALLINT,
            'service_id' => Schema::TYPE_STRING,
            'created_at' => Schema::TYPE_INTEGER,
            'data' => Schema::TYPE_TEXT
        ]);
    }

    public function down()
    {
        $this->dropTable(Auth::tableName());
    }
}
