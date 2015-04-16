<?php

/**
 * This is the model class for table "store_order".
 *
 * The followings are the available columns in table 'store_order':
 * @property integer $id
 * @property integer $id_customer
 * @property string $delivery
 * @property string $delivery_data
 * @property string $payment
 * @property string $payment_data
 * @property integer $status
 * @property integer $promo_id
 * @property string $buy_date
 */
class StoreOrder extends CActiveRecord
{

    const STATUS_CANCELED = -1;
    const STATUS_CART = 0;
    const STATUS_PAYMENT_WAIT = 1;      //передано на оплату
    const STATUS_SUCCESS_REDIRECT = 2;  //пользователь пререведен магазином на shopSuccessURL
    const STATUS_PAID = 3;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getDbConnection()
    {
        return Yii::app()->dbStore;
    }

    public function tableName()
    {
        return 'store_order';
    }

    public function beforeSave()
    {
        $this->delivery_data = serialize($this->delivery_data);
        $this->payment_data = serialize($this->payment_data);
        return parent::beforeSave();
    }

    protected function afterFind()
    {
        $this->delivery_data = unserialize($this->delivery_data);
        $this->payment_data = unserialize($this->payment_data);
        return parent::afterFind();
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_customer, status', 'required'),
            array('id_customer, delivery, status, promo_id', 'numerical', 'integerOnly' => true),
        );
    }
    
    public function itemsList() 
    {
        $items = StoreOrderList::model()->findAllByAttributes(array('id_order' => $this->id));
        
        $list = array();
        
        foreach ($items as $item) {
            $productList = StoreProduct::getList();

            $list[] = array(
                'name' => $productList[$item->id_product]->name,
                'code' => $productList[$item->id_product]->code,
                'type' => $productList[$item->id_product]->type,
                'id_order' => $item->id_order,
                'quantity' => $item->quantity,
                'color' => $item->color,
                'price' => $item->price,
                'front_card_img' => $item->front_card_img,
            );
        }
        
        return $list;
    }
    
    public function subtotal($items=false)
    {
        $items = StoreOrderList::model()->findAllByAttributes(array('id_order' => $this->id));
        $subtotal = 0;
        
        foreach ($items as $item) {
            $subtotal += $item->price * $item->quantity;
        }
        
        return $subtotal;
    }
    
    public function tax()
    {
        return 0;
    }
    
    public function discount()
    {
        return 0;
    }

    public function mailOrder()
    {
        if (!$this->id)
            return false;
        
        $mailOrder = array('id'=>$this->id);
        
        $customer = StoreCustomer::model()->findByPk($this->id_customer);
        $delivery = StoreDelivery::model()->findByPk($this->delivery);
        $items = $this->itemsList();
        
        if (!$customer or !$delivery or empty($items))
            return false;
        
        $mailOrder['order'] = $this;
        $mailOrder['delivery'] = $delivery;
        $mailOrder['customer'] = $customer;
        $mailOrder['items'] = $items;
        $mailOrder['subtotal'] = $this->subtotal($items);
        $mailOrder['shipping'] = $delivery->price;
        $mailOrder['tax'] = $this->tax();
        $mailOrder['total'] = $mailOrder['subtotal'] + $mailOrder['shipping'] - $this->discount();
        
        return $mailOrder;
    }
    
    public function customCardsMailOrders($baseOrder=false)
    {
        $mailOrders = array();
        
        if (!$baseOrder)
            $baseOrder = $this->mailOrder();
        
        foreach($baseOrder['items'] as $item) {
            if (empty($item['front_card_img']))
                continue;
            
            $mailOrders[] = array(
                'shipping_name' => $baseOrder['customer']->target_first_name,
                'phone' => $baseOrder['customer']->phone,
                'address' => $baseOrder['customer']->address,
                'city' => $baseOrder['customer']->city,
                'zip' => $baseOrder['customer']->zip,
                'front_img' => $item['front_card_img'],
                'back_img' => CustomCard::backByType($item['type']),
            );
        }

        return $mailOrders;
    }
    

}
