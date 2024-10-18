<?php 

class Orders extends CI_Controller
{
    // THIS CONTROLLER IS FOR MANAGING THE ORDERS OF THE CUSTOMER THROUGH ADMIN SIDE
    // INCLUDING THE CHANGING STATUS FOR THE ORDER
    private $csrf;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('order');
        $this->csrf =  array('name' => $this->security->get_csrf_token_name(), 'hash' => $this->security->get_csrf_hash());
        $this->output->enable_profiler(FALSE);
    }
    public function order_category($category)
    {
        $view_data['orders'] = array(
            'items' => $this->order->filter_order($category),
            'user' => $this->order->order_user_info()
        );
        $view_data['images'] = array();
        foreach($view_data['orders']['items'] as $product)
        {
            $view_data['images'][$product['id']] = array(
                'id' => $product['id'],
                'image' => json_decode($product['image_path'])
            );
        }
        if($category === '0')
        {
            $view_data['all_order'] = count($this->order->get_all());
        }
        else    
        {
            $view_data['all_order'] = array(
                'all' => count($this->order->get_all()),
                'partial' => count($this->order->filter_order($category))
            );
        }
        $view_data['category'] = $category;
        $view_data['pending'] = $this->order->pending_count();
        $view_data['process'] = $this->order->process_count();
        $view_data['shipped'] = $this->order->shipped_count();
        $view_data['delivered'] = $this->order->delivered_count();
        $view_data['csrf'] = $this->csrf;
        $this->load->view('users/admin',$view_data);
    }
    public function change_status()
    {
        $this->order->update_status($this->input->post(null,true));
        redirect(base_url('orders/order_category/0'));
    }
    public function search_order()
    {
        $view_data['orders'] = array(
            'items' => $this->order->search($this->input->post(null,true)),
            'user' => $this->order->order_user_info()
        );
        $view_data['images'] = array();
        foreach($view_data['orders']['items'] as $product)
        {
            $view_data['images'][$product['id']] = array(
                'id' => $product['id'],
                'image' => json_decode($product['image_path'])
            );
        }
        $view_data['all_order'] = count($this->order->get_all());
        $view_data['pending'] = $this->order->pending_count();
        $view_data['process'] = $this->order->process_count();
        $view_data['shipped'] = $this->order->shipped_count();
        $view_data['delivered'] = $this->order->delivered_count();
        $view_data['csrf'] = $this->csrf;
        $this->load->view('users/admin',$view_data);
    }
}