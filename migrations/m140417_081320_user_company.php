<?php
use bariew\userAbstractModule\models\Company;

class m140417_081320_user_company extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable(Company::tableName(), array(
            'id'            => 'pk',
            'owner_id'      => $this->integer(),
            'title'         => 'string',
            'description'   => 'string',
        ));

        $this->insert(Company::tableName(), array(
            'id'            => 1,
            'title'         => 'default',
            'description'   => 'default',
        ));
    }

    public function down()
    {
        $this->dropTable(Company::tableName());
    }
}
