<?php

class Carts extends CI_Controller
{
    // THIS CONTROLLER IS RESPONSIBLE FOR THE ADD TO CARTS IN CUSTOMER SIDE
    // INCLUDING THE NECCESARY DATA TO BE DISPLAY IN CARTS PAGE AND THE DELETION OF ORDER
    //  AND FOR THE PAYMENT OF THE ORDER
    private $status;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('cart');
        $this->status = $this->session->userdata('is_logged_in');
        $this->output->enable_profiler(FALSE);
    }
    // THIS FUNCTION WILL REDIRECT THE USER TO LOGIN PAGE IF THE USER IS NOT YET LOGIN
    public function index()
    {
        if($this->status === true)
        {
            $this->load->view('partials/order_cart');
        }
        else
        {
            redirect('login');
        }
    }
    public function add_to_cart()
    {
        if($this->status === true)
        {
            $user = $this->session->userdata('user_data');
            $status = $this->cart->insert($this->input->post(null,true),$user['id']);
            $this->load->model('product');
            $this->product->cart_count($user['id']);
            $view_data['cart_count'] = $this->session->userdata('cart_count');
            $view_data['csrf'] =  array('name' => $this->security->get_csrf_token_name(), 'hash' => $this->security->get_csrf_hash());
            $response = array(
                'success' => $status,
                'status' => $this->status,
                'view' => $this->load->view('partials/search',$view_data,$this->status),
                'partial' => 'products/details/'.$this->input->post('category_id').'/'.$this->input->post('prod_id')
            );
        }
        else
        {
            $response = array(
                'status' => $this->status,
                'view' => 'http://ecommerce/login'
            );
        }
        echo json_encode($response);
    }
    public function order_carts()
    {
        if($this->status === true)
        {
            $item_price = 0;
            $shipping  = 0;
            $user = $this->session->userdata('user_data');
            $view_data['order'] = $this->cart->get_all($user['id']);
            $view_data['images'] = array();
            foreach($view_data['order'] as $order)
            {
                    $item_price += $order['total_price'];
                    $shipping += $order['shipping'];
                    $view_data['images'][$order['id']] = array(
                        'id' => $order['id'],
                        'image' => json_decode($order['image_path'])
                    );
            }
            $view_data['item_price'] = $item_price;
            $view_data['shipping'] = $shipping;
            $view_data['total_amount'] = $item_price + $shipping;
            $view_data['csrf'] =  array('name' => $this->security->get_csrf_token_name(), 'hash' => $this->security->get_csrf_hash());
            $response = array(
                'status' => $this->status,
                'view' => $this->load->view('partials/order_cart',$view_data,$this->status)
            );
        }
        else
        {
            $response = array(
                'status' => $this->status,
                'view' => 'http://ecommerce/login'
            );
        }
        echo json_encode($response);
    }
    public function remove()
    {
        $item_price = 0;
        $shipping  = 0;
        $user = $this->session->userdata('user_data');
        $this->cart->delete($this->input->post(null,true),$user['id']);
        $this->load->model('product');
        $this->product->cart_count($user['id']);
        $view_data['cart_count'] = $this->session->userdata('cart_count');
        $view_data['order'] = $this->cart->get_all($user['id']);
        $view_data['images'] = array();
        foreach($view_data['order'] as $order)
        {
                $item_price += $order['total_price'];
                $shipping += $order['shipping'];
                $view_data['images'][$order['id']] = array(
                    'id' => $order['id'],
                    'image' => json_decode($order['image_path'])
                );
        }
        $view_data['item_price'] = $item_price;
        $view_data['shipping'] = $shipping;
        $view_data['total_amount'] = $item_price + $shipping;
        $view_data['csrf'] =  array('name' => $this->security->get_csrf_token_name(), 'hash' => $this->security->get_csrf_hash());
        $response = array(
            'searchbar' => $this->load->view('partials/search',$view_data,true),
            'orders' => $this->load->view('partials/order_cart',$view_data,true)
        );
        echo json_encode($response);
    }
    public function change_quantity()
    {
        $this->cart->update_quantity($this->input->post(null,true));
        redirect(base_url('carts/order_carts'));
    }
    public function shipping_info()
    {
        if($this->status === true)
        {
            $user = $this->session->userdata('user_data');
            $response = array('address' => $this->cart->get_address($user['id']));
            echo json_encode($response);
        }
    }
    public function set_shipping()
    {
        $shipping = $this->input->post(null,true);
        $this->cart->shipping($shipping);
    }
    public function set_billing()
    {
        $billing = $this->input->post(null,true);
        $this->cart->billing($billing);
    }
    public function checkout()
    {
        $item_price = 0;
        $shipping_fee  = 0;
        $user = $this->session->userdata('user_data');
        $product = $this->cart->get_all($user['id']);
        $shipping = $this->session->userdata('shipping_info');
        $this->cart->verified_checkout($user['id'],$product,array($shipping));
        $this->load->model('product');
        $this->product->cart_count($user['id']);
        $view_data['cart_count'] = $this->session->userdata('cart_count');
        $view_data['order'] = $this->cart->get_all($user['id']);
        foreach($view_data['order'] as $order)
        {
                $item_price += $order['total_price'];
                $shipping_fee += $order['shipping'];
        }
        $view_data['item_price'] = $item_price;
        $view_data['shipping'] = $shipping_fee;
        $view_data['total_amount'] = $item_price + $shipping_fee;
        $view_data['csrf'] =  array('name' => $this->security->get_csrf_token_name(), 'hash' => $this->security->get_csrf_hash());
        $response = array(
            'searchbar' => $this->load->view('partials/search',$view_data,true),
            'orders' => $this->load->view('partials/order_cart',$view_data,true)
        );
        echo json_encode($response);
    }
}