<?php

class Cart extends CI_Model
{
    // THIS MODEL IS RESPONSIBLE FOR THE CRUD OPERATION FOR CARTS CONTROLLER
    public function insert($data,$user_id)
    {
        $product = $this->db->query("SELECT price,stocks FROM products where id = ?",array($data['id']))->row_array();
        $quantity = $product['stocks'] - $data['quantity'];
        if($data['quantity'] > $product['stocks'])
        {
            return false;
        }
        else
        {
            if($quantity > 0)
            {
                $total = (float)$product['price'] * (float)$data['quantity'];
                $this->db->query('UPDATE products SET stocks = ? WHERE id = ?',array($quantity,$data['id']));
                $query = "INSERT INTO cart (product_id,user_id,quantity,total_price,created_at,updated_at) VALUES (?,?,?,?,NOW(),NOW())";
                $value = array($data['id'],$user_id,$data['quantity'],$total);
                return $this->db->query($query,$value);
            }
            else
            {
                $this->db->query('UPDATE products SET stocks = ? WHERE id = ?',array('0',$data['id']));
            }
        }
        return true;
    }
    public function get_all($user_id)
    {
        $query = "SELECT * FROM products LEFT JOIN cart ON cart.product_id = products.id WHERE user_id = ? ";
        $value = array($user_id);
        return $this->db->query($query,$value)->result_array();
    }
    public function delete($data,$user_id)
    { 
        $quantity = $this->db->query('SELECT quantity,product_id FROM cart WHERE cart_id = ?',array($data['cart_id']))->row_array();
        $stocks = $this->db->query('SELECT stocks FROM products WHERE id = ?',array($quantity['product_id']))->row_array();
        $total = $quantity['quantity'] + $stocks['stocks'];
        $this->db->query('UPDATE products SET stocks = ? WHERE id = ?',array($total,$quantity['product_id']));
        return $this->db->query('DELETE FROM cart WHERE cart_id = ? and user_id = ?',array($data['cart_id'],$user_id));
    }
    public function update_quantity($cart)
    {
        $product_id = $this->db->query('SELECT product_id FROM cart WHERE cart_id = ?',array($cart['cart_id']))->row_array();
        $price = $this->db->query('SELECT price FROM products WHERE id = ?',array($product_id['product_id']))->row_array();
        $new_price = (float)$price['price'] * (float)$cart['quantity'];
        return $this->db->query('UPDATE cart SET quantity = ?,total_price = ? WHERE cart_id = ?',array($cart['quantity'],$new_price,$cart['cart_id']));
    }
    public function get_address($user_id)
    {
        $address_id = $this->db->query('SELECT address_id FROM user_information LEFT JOIN user_account ON user_information.id = user_account.id WHERE user_account.id = ?',array($user_id))->row_array();
        return $this->db->query('SELECT * from address LEFT JOIN user_information ON user_information.id = ? WHERE address.id = ?',array($user_id,$address_id))->row_array();
    }
   public function shipping($shipping)
   {
        $this->session->set_userdata('shipping_info',$shipping);
   }
   public function billing($billing)
   {
        $this->session->set_userdata('billing_info',$billing);
   }
   public function verified_checkout($user_id,$products,$info)
   {
        foreach($products as $product)
        {
            $query = "INSERT INTO checkout (user_id,product_id,quantity,total_price,recipient,address,city,zip,status,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,NOW(),NOW())";
            $values = array($user_id,$product['id'],$product['quantity'],$product['total_price'],$info[0]['fname'] . $info[0]['lname'],$info[0]['address'],$info[0]['city'],$info[0]['zip'],'1');
            if($this->db->query($query,$values) === true)
            {
                $delete_query = "DELETE FROM cart WHERE product_id = ? and user_id = ? ";
                $delete_values = array($product['id'],$user_id);
                $this->db->query($delete_query,$delete_values);
            }
        }       
   }
}