<?php
use bariew\userAbstractModule\models\User;

class m140417_081329_user_user extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable(User::tableName(), array(
            'id'            => 'pk',
            'owner_id'      => $this->integer(),
            'email'         => 'string',
            'password'      => 'string',
            'auth_key'      => 'string',
            'api_key'       => 'string',
            'username'      => 'string',
            'status'        => 'integer',
            'created_at'    => 'integer',
            'updated_at'    => 'integer',
            'password_reset_token'=>'string',
        ));
        (new User([
            'email'         => 'admin@admin.admin',
            'password'      => 'admin',
            'username'      => 'admin',
            'status'        => User::STATUS_ACTIVE,
        ]))->save(false); // for calling app events
    }

    public function down()
    {
        $this->dropTable(User::tableName());
    }
}
