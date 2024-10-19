<?php
     defined("BASEPATH") OR exit("No direct script access allowed");
class Order extends CI_Model
{
    // THIS MODEL IS RESPONSIBLE FOR THE CRUD OPERATION OF ORDERS
    public function get_all()
    {   
        return $this->db->query('SELECT checkout.*,products.image_path FROM checkout LEFT JOIN products ON checkout.product_id = products.id')->result_array();
    }
    public function order_user_info()
    {
        $query = 'SELECT user_information.id,user_information.firstname,user_information.lastname, address.city,address.city,address.street 
                  FROM user_information LEFT JOIN user_account ON user_information.id = user_account.user_information_id
                  LEFT JOIN address ON user_information.address_id = address.id';
        return $this->db->query($query)->result_array();
    }
    public function filter_order($status)
    {
        if($status === '0')
        {
            return $this->get_all();
        }
        else
        {
            $status = (int)$status;
            return $this->db->query('SELECT checkout.*,products.image_path FROM checkout LEFT JOIN products ON checkout.product_id = products.id WHERE checkout.status = ?',array($status))->result_array();
        }
    } 
    public function pending_count()
    {
        return $this->db->query('SELECT COUNT(status) as pending FROM checkout WHERE status = ?',array('1'))->row_array();
    }
    public function process_count()
    {
        return $this->db->query('SELECT COUNT(status) as process FROM checkout WHERE status = ?',array('2'))->row_array();
    }
    public function shipped_count()
    {
        return $this->db->query('SELECT COUNT(status) as shipped FROM checkout WHERE status = ?',array('3'))->row_array();
    }
    public function delivered_count()
    {
        return $this->db->query('SELECT COUNT(status) as delivered FROM checkout WHERE status = ?',array('4'))->row_array();
    }
    public function update_status($status)
    {
        $new_status = '';
        switch($status['status'])
        {
            case 'process':
                $new_status = '2';
                break;
            case 'shipped':
                $new_status = '3';
                break;
            case 'delivered':
                $new_status = '4';
                break;
            default:
                $new_status = '1';
                break;
        }
        return $this->db->query('UPDATE checkout SET status = ? WHERE id = ?',array($new_status,$status['id']));
    }
    public function search($data)
    {
        $values = array($data['searchbar'],$data['searchbar'] . '%',$data['searchbar'] . '%',$data['searchbar'] . '%');
        return $this->db->query('SELECT checkout.*,products.image_path FROM checkout LEFT JOIN products ON checkout.product_id = products.id WHERE checkout.id = ? or checkout.recipient LIKE ? or checkout.address LIKE ? or checkout.zip LIKE ? ',$values,$values,$values,$values)->result_array();
    } 
}