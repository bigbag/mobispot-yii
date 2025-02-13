<?php

/**
 * This is the model class for table "custom_card".
 *
 * The followings are the available columns in table "custom_card":
 * @property integer $id
 * @property string $type
 * @property string $img
 * @property integer $draft
 * @property string $creation_date
 * @property string $photo
 * @property string $logo
 * @property string $name
 * @property string $position
 */
class CustomCard extends CActiveRecord
{
    const TYPE_GUU = 'guu';
    const TYPE_SIMPLE = 'simple';
    const TYPE_TROIKA = 'troika';
    
    const TROIKA_BACK = 'troika.jpg';
    const SIMPLE_BACK = 'card_back_white.jpg';
    
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'custom_card';
    }
    
    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            $this->creation_date = date('Y-m-d H:i:s');
        }

        return parent::beforeValidate();
    }
    
    public function getNumber()
    {
        $counter = $this->id + 100;
        if ($counter >= 1000) 
            return (string)$counter;
        else 
            return '0' . $counter;
        
    }

    public static function getNextNum($type=self::TYPE_SIMPLE) {
        //$guu_cards = self::model()->findAllByAttributes(array('type'=>self::TYPE_GUU));
        $guu_cards = self::model()->findAll();
        $counter = count($guu_cards) + 100 + 1;
        
        if ($counter >= 1000) 
            return (string)$counter;
        else 
            return '0' . $counter;
        
    }
    
    public static function getDefaults($type) {
        $defaults = array(
            self::TYPE_GUU=>
            array(
                'email' => 'admin@guu.ru',
                'shipping_name' => 'Admin',
                'phone' => 'Admin',
                'address' => 'Рязанский проспект, 99',
                'city' => 'Москва',
                'zip' => '109542',
                'type' => self::TYPE_GUU,
                'shirt_img' => 'guu_card_frame.jpg',
                'number' => self::getNextNum(self::TYPE_GUU),
                'token' => Yii::app()->request->csrfToken,
                'name' => '',
                'position' => '', 
                'department' => '',
            ),
            self::TYPE_SIMPLE=>
             array(
                'email' => '',
                'shipping_name' => '',
                'phone' => '',
                'address' => '',
                'city' => '',
                'zip' => '',
                'type' => self::TYPE_SIMPLE,
                'shirt_img' => 'simple_card_frame.jpg',
                'number' => self::getNextNum(self::TYPE_SIMPLE),
                'token' => Yii::app()->request->csrfToken,
                'name' => '',
                'position' => '', 
                'department' => '',
            )
        );
        
        if (empty($defaults[$type]))
            return false;
        
        return $defaults[$type];
    }
    
    public static function newTransportCard($photo_path, $logo_path, $name, $position)
    {
        $card = new CustomCard;
        $card->draft = true;
        $card->type = self::TYPE_TROIKA;
        $card->img = MImg::makeTransportCard($photo_path, $logo_path, $name, $position);
        if (!empty($card->img) and file_exists(Yii::getPathOfAlias('webroot.uploads.custom_card') . '/' . $card->img))
            $card->save();
        
        return $card;
    }
    
    public static function newCustomCard($photo, $photo_croped, $logo, $logo_croped, $custom_name, $position, $departament, $type)
    {
        $card_path = Yii::getPathOfAlias('webroot.uploads.custom_card') . '/';
        $photo_path = false;
        $logo_path = false;
        
        $card = new CustomCard;
        if (!empty($photo) and !empty($photo_croped))
        {
            $card->photo = $photo;
            $photo_path = $card_path . $photo_croped;
        }
        if (!empty($logo) and !empty($logo_croped))
        {
            $card->logo = $logo;
            $logo_path = $card_path. $logo_croped;
        }
        if (!empty($custom_name))
            $card->name = $custom_name;
        if (!empty($position))
            $card->position = $position;
        
        $card->draft = true;
        $card->type = $type;
        
        if (self::TYPE_TROIKA == $type)
            $card->img = MImg::makeTransportCard($photo_path, $logo_path, $custom_name, $position);
        elseif (self::TYPE_SIMPLE == $type)
            $card->img = MImg::makeCustomCard($card_path . $photo_croped, $custom_name, $position, $departament);
        else
            return false;
        
        if (!empty($card->img) and file_exists(Yii::getPathOfAlias('webroot.uploads.custom_card') . '/' . $card->img))
            $card->save();
        
        return $card;
    }
    
    public static function newUserDesignedCard($user_design_file)
    {
        $card = new CustomCard;
        $card->draft = true;
        $card->type = self::TYPE_TROIKA;
        $card->img = $user_design_file;
        
        if (empty($card->img) or !file_exists(Yii::getPathOfAlias('webroot.uploads.custom_card') . '/' . $card->img))
            return false;

        $card->save();
        
        return $card;
    }
    
    public static function backByType($type)
    {
        $back = false;
        
        $types = array(self::TYPE_SIMPLE => self::SIMPLE_BACK, self::TYPE_TROIKA => self::TROIKA_BACK);
        if (isset($types[$type]))
            $back = $types[$type];
        
        return $back;
    }
    
}
