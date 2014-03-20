<?php

/**
 * This is the model class for table "wallet".
 *
 * The followings are the available columns in table 'wallet':
 * @property integer $id
 * @property string $hard_id
 * @property string $payment_id
 * @property string $name
 * @property integer $user_id
 * @property integer $discodes_id
 * @property string $creation_date
 * @property string $balance
 * @property string $status
 */
class PaymentWallet extends CActiveRecord
{

    const STATUS_NOACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_BANNED = -1;

    public function getStatusList()
    {
        return array(
            self::STATUS_NOACTIVE => Yii::t('user', 'Не активирован'),
            self::STATUS_ACTIVE => Yii::t('user', 'Активирован'),
            self::STATUS_BANNED => Yii::t('user', 'Заблокирован'),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return PaymentWallet the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return CDbConnection database connection
     */
    public function getDbConnection()
    {
        return Yii::app()->dbPayment;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'payment.wallet';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('hard_id, name, payment_id, user_id, creation_date, balance', 'required'),
            array('user_id, discodes_id, status', 'numerical', 'integerOnly' => true),
            array('hard_id, payment_id', 'length', 'max' => 20),
            array('name', 'filter', 'filter' => 'trim'),
            array('name', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify')),
            array('balance', 'length', 'max' => 150),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, hard_id, payment_id, name, user_id, discodes_id, status, creation_date, balance', 'safe', 'on' => 'search'),
        );
    }

    public function getFreeByDiscodesId($discodes_id)
    {
        $wallet = Yii::app()->cache->get('wallet_discodes_' . $discodes_id);
        if (!$wallet)
        {
            $wallet = PaymentWallet::model()->findByAttributes(
                    array(
                        'discodes_id' => $discodes_id,
                        'user_id' => 0,
                    )
            );
            Yii::app()->cache->set('wallet_discodes_' . $discodes_id, $wallet, 120);
        }
        return $wallet;
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord)
        {
            $this->creation_date = date('Y-m-d H:i:s');
            if (!$this->status)
                $this->status = self::STATUS_ACTIVE;
            if (!$this->balance)
                $this->balance = 0;
            if (!$this->discodes_id)
                $this->balance = 0;
            if (!$this->name)
                $this->name = 'My Spot';
        }

        return parent::beforeValidate();
    }

    public function beforeSave()
    {
        $this->balance = ($this->balance) * 100;
        return parent::beforeSave();
    }

    protected function afterSave()
    {
        Yii::app()->cache->delete('wallet_discodes_' . $this->discodes_id);
        parent::afterSave();
    }

    public function afterFind()
    {
        $this->balance = ($this->balance) / 100;

        return parent::afterFind();
    }

    public function selectUser($user_id)
    {
        $criteria = new CDbCriteria;
        $criteria->compare('user_id', $user_id);
        $this->getDbCriteria()->mergeWith($criteria);
        return $this;
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'spot' => array(self::BELONGS_TO, 'Spot', 'discodes_id'),
            'loyalties' => array(self::MANY_MANY, 'Loyalty', 'wallet_loyalty(wallet_id, loyalty_id)'),
            'actions' => array(self::HAS_MANY, 'WalletLoyalty', 'wallet_id'),
            'smsInfo' => array(self::HAS_ONE, 'SmsInfo', 'wallet_id'),
        );
    }

    public static function getByWalletId($id, $page = 1, $count = 5)
    {
        $answer = array();

        $criteria = new CDbCriteria;

        $wallet = self::model()->with('loyalties')->findByPk($id);


        return $answer;
    }

    public function scopes()
    {
        return array(
            'blacklist' => array(
                'condition' => 'balance<=0',
            ),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'hard_id' => 'Hard',
            'payment_id' => 'Payment',
            'user_id' => 'User',
            'discodes_id' => 'Spot',
            'creation_date' => 'Creation Date',
            'balance' => 'Balance',
            'name' => 'Name',
            'status' => 'Status',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('hard_id', $this->hard_id, true);
        $criteria->compare('payment_id', $this->payment_id, true);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('discodes_id', $this->discodes_id);
        $criteria->compare('status', $this->status);
        $criteria->compare('creation_date', $this->creation_date, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('balance', $this->balance, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
