<?php

class Product extends CI_Model
{
    // THIS MODEL IS RESPONSIBLE FOR THE CRUD OPERATION IN PRODUCTS CONTROLLER
    // INCLUDING THE, ADDING PRODUCTS, EDITING PRODUCTS, NAVIGATION THROUGH CATEGORY
    private $cloud;
    public function __construct(){
        $this->cloud = new Cloud();
    }
    public function get_all_products()
    {
        return $this->db->query("SELECT * FROM products")->result_array();
    }
    public function category_men($category_id)
    {
        return $this->db->query('SELECT COUNT(category_id) AS count FROM products WHERE category_id = ?',array($category_id))->row_array();
    }
    public function category_women($category_id)
    {
        return $this->db->query('SELECT COUNT(category_id) AS count FROM products WHERE category_id = ?',array($category_id))->row_array();
    }
    public function category_kids($category_id)
    {
        return $this->db->query('SELECT COUNT(category_id) AS count FROM products WHERE category_id = ?',array($category_id))->row_array();
    }
    public function cart_count($id)
    {
        return $this->session->set_userdata('cart_count',$this->db->query('SELECT COUNT(cart_id) as count from cart WHERE user_id = ?',array($id))->row_array());
    }
    public function filter_category($category_id)
    {
        if($category_id['id'] == null)
        {
            $this->session->unset_userdata('selected_category');
            return $this->get_all_products();
        }
        switch($category_id['id'])
        {
             case '1':
                $this->session->set_userdata('selected_category', 1);
                 break;
             case '2':
                $this->session->set_userdata('selected_category', 2);
                 break;
             case '3':
                $this->session->set_userdata('selected_category', 3);
                 break;
        }
        return $this->db->query("SELECT * FROM products WHERE category_id = ?",array($category_id['id']))->result_array(); 
    }
    public function get_next_page($page)
    {
        $page = (int)$page;
        $display = array();
        $next_page = array();
        if($this->session->has_userdata('selected_category'))
        {
            $display = $this->db->query("SELECT * FROM products WHERE category_id = ?",$this->session->userdata('selected_category'))->result_array();
        }
        else
        {
            $display = $this->db->query("SELECT * FROM products")->result_array();
        }
        if($page > 1)
        {
            $page *=  10;
            $page -= 10;
            for($itr = $page; $itr < count($display); $itr++)
            {
                $next_page[$itr] = $display[$itr];
            }
        }
        else
        {
            for($itr = 0; $itr < count($display); $itr++)
            {
                $next_page[$itr] = $display[$itr];
            }
        }
        $this->session->set_userdata('serve_as_products_count', $display);
        return $this->session->set_userdata('next_page',$next_page);
    }
    public function searching($keyword)
    {
        if($this->session->has_userdata('selected_category'))
        {
            $query = "SELECT * FROM products WHERE name LIKE ? and category_id = ?";
            $value = array($keyword['searchbar'] . '%',$this->session->userdata('selected_category'));
        }
        else
        {
            $query = "SELECT * FROM products WHERE name LIKE ?";
            $value = array($keyword['searchbar'] . '%');
        }
        $this->session->set_userdata('serve_as_products_count', $this->db->query($query,$value)->result_array());
        return $this->db->query($query,$value)->result_array();
    }
    public function product_details($id,$category_id)
    {
        $this->session->set_userdata('similar', $this->db->query("SELECT * FROM products WHERE category_id = ? LIMIT 7",array($category_id))->result_array());
        return $this->db->query("SELECT * FROM products WHERE id = ?",array($id))->row_array();
    }
    public function admin_get_all()
    {
        $query = 'SELECT products.*,categories.category FROM products LEFT JOIN categories ON products.category_id = categories.id';
        return $this->db->query($query)->result_array();
    }
    public function admin_search_product($search)
    {
        $query = 'SELECT products.*,categories.category FROM products LEFT JOIN categories ON products.category_id = categories.id WHERE products.id = ? or name LIKE ? or price = ? or category = ? or stocks = ?';
        return $this->db->query($query,array($search['searchbar'],$search['searchbar'] . '%',$search['searchbar'],$search['searchbar'],$search['searchbar']))->result_array();
    }
    public function get_sold($id)
    {
        $count = array();
        foreach($id as $check)
        {
            $count[] = $this->db->query('SELECT COUNT(checkout.id) AS sold FROM checkout WHERE checkout.product_id = ?',array($check['id']))->row_array();
        }
        return $count;
    }
    public function admin_filter_category($category_id)
    {
        if($category_id['id'] == null)
        {
            $this->session->unset_userdata('selected_category');
            return $this->admin_get_all();
            
        }
        switch($category_id['id'])
        {
             case '1':
                $this->session->set_userdata('selected_category', 1);
                 break;
             case '2':
                $this->session->set_userdata('selected_category', 2);
                 break;
             case '3':
                $this->session->set_userdata('selected_category', 3);
                 break;
        }
        $query = 'SELECT products.*,categories.category FROM products LEFT JOIN categories ON products.category_id = categories.id WHERE products.category_id = ?';
        $values = array($category_id['id']);
        return $this->db->query($query,$values)->result_array(); 
    }
    public function insert($data)
    {
        $category = '';
        switch($data['categories'])
        {
            case 'Men':
                $category = '1';
                $data['categories'] = 'men';
            break;
            case 'Women':
                $category = '2';
                $data['categories'] = 'women';
            break;
            case 'Kids':
                $category = '3';
                $data['categories'] = 'kids';
            break;
        }
        $path = $this->image_handler($data);
        $json = json_encode($path);
        $query = 'INSERT INTO products(category_id,name,description,price,shipping,image_path,stocks,created_at,updated_at)VALUES(?,?,?,?,?,?,?,NOW(),NOW())';
        $values = array($category,$data['product_name'],$data['description'],$data['price'],'1',$json,$data['stock']);
        return $this->db->query($query,$values);
        
    }
    public function image_handler($data)
    {
        $path = array();
        $count = 0;
        foreach($_FILES as $files)
        {   
            if($files['name'] !== '')
            {
                ++$count;
                $file_parts = pathinfo(basename($files['name']));
                $new_file_name = $file_parts['filename'].'_'.time();
                $this->cloud->upload($files['tmp_name'],$new_file_name);
                if(isset($data['main'.$count]) && $data['main'.$count] === 'on')
                {
                    $path['main'] = $this->cloud->url() . $new_file_name;
                }
                else
                {
                    $path['image'.$count] = $this->cloud->url() . $new_file_name;
                }
            }
        }
        return $path;
    }
    public function update($data)
    {
        $category = '';
        switch($data['categories'])
        {
            case 'Men':
                $category = '1';
                $data['categories'] = 'men';
            break;
            case 'Women':
                $category = '2';
                $data['categories'] = 'women';
            break;
            case 'Kids':
                $category = '3';
                $data['categories'] = 'kids';
            break;
        }
        foreach($data as $key => $info)
        {
            if($key !== 'id')
            {
                if($key !== 'product_name')
                {
                    if($key === 'description' && $info !== '')
                    {
                        $this->db->query('UPDATE products SET description = ? WHERE id = ?',array($info,$data['id']));
                    }
                    else if($key === 'categories' && $info !== '')
                    {
                        $this->db->query('UPDATE products SET category_id = ? WHERE id = ?',array($category,$data['id']));
                    }
                    else if($key === 'price' && $info !== '')
                    {
                        $this->db->query('UPDATE products SET price = ? WHERE id = ?',array($info,$data['id']));
                    }
                    else if($key === 'stock' && $info !== '')
                    {
                        $this->db->query('UPDATE products SET stocks = ? WHERE id = ?',array($info,$data['id']));
                    }
                }
                else
                {
                    if($info !== '')
                    {
                        $this->db->query('UPDATE products SET name = ? WHERE id = ?',array($info,$data['id']));
                    }
                }
            }
        }
        $path = $this->update_image($data);
        $json = json_encode($path);
        $this->db->query('UPDATE products SET image_path = ? WHERE id = ?',array($json,$data['id']));
    }
    public function update_image($data)
    {
        $path = array();
        $image = $this->db->query('SELECT image_path FROM products WHERE id = ?',array($data['id']))->row_array();
        $decoder = json_decode($image['image_path']);
        $count = 0;
        foreach($_FILES as $key => $files)
        {
            if($files['name'] !== '')
            {
                ++$count;
                if(isset($data['main'.$count]) && $data['main'.$count] === 'on')
                {
                    $reverse = strrev($decoder->main);
                    $public_id = explode( '/',$reverse,2);
                    $file_name = strrev($public_id[0]);
                    $this->cloud->delete($file_name);
                    $file_parts = pathinfo(basename($files['name']));
                    $new_file_name = $file_parts['filename'].'_'.time();
                    $this->cloud->upload($files['tmp_name'],$new_file_name);
                    $path['main'] = $this->cloud->url() . $new_file_name;
                }
                else
                {
                    if(isset($decoder->$key))
                    {
                        $file_parts = pathinfo(basename($files['name']));
                        $new_file_name = $file_parts['filename'].'_'.time();
                        $this->cloud->upload($files['tmp_name'],$new_file_name);
                        $path['image'.$count] = $this->cloud->url() . $new_file_name;
                    }
                    else
                    {
                        $file_parts = pathinfo(basename($files['name']));
                        $new_file_name = $file_parts['filename'].'_'.time();
                        $this->cloud->upload($files['tmp_name'],$new_file_name);
                        $path['image'.$count] = $this->cloud->url() . $new_file_name;
                    }
                }
            }
        }
        $image = new ArrayObject($decoder);
        $this->delete_extra_image($decoder,$image->count());
        return $path;
    }
    public function delete_extra_image($decoder,$object_count){
        for($count = 1; $count <= $object_count; $count++){
            $image = 'image'.$count;
            $reverse = strrev($decoder->$image);
            $public_id = explode( '/',$reverse,2);
            $file_name = strrev($public_id[0]);
            $this->cloud->delete($file_name);
        }
    }
}