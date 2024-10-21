<?php
     defined("BASEPATH") OR exit("No direct script access allowed");
class Products extends CI_Controller
{
    private $status;
    private $csrf;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('product');
        $this->status = $this->session->userdata('is_logged_in');
        $this->csrf =  array('name' => $this->security->get_csrf_token_name(), 'hash' => $this->security->get_csrf_hash());
        $this->output->enable_profiler(FALSE);
    }
    // MAIN VIEW FOR CUSTOMER
    public function main()
    {   
        if($this->status === true)
        {
            $view_data['user_data'] = $this->session->userdata('user_data');
            $this->product->cart_count($view_data['user_data']['id']);
            $view_data['cart_count'] = $this->session->userdata('cart_count');
        }
        $view_data['title'] = 'Dashboard';
        $view_data['content'] = 'Choose your favorite clothes';
        $view_data['csrf'] = $this->csrf;
        if(isset($this->session->userdata('user_data')['is_admin']) && $this->session->userdata('user_data')['is_admin'] === '1')
        {
            $view_data['admin'] = true;
        }
        $this->load->view('dashboard/main',$view_data);
    }
    // THIS FUNCTION IS FOR SWITCHING FROM ADMIN VIEW TO CUSTOMER VIEW
    public function admin_switch_view()
    {
        $search_data['admin'] = false;
        $search_data['selected_product'] = false;
        $view_data['products'] = $this->product->get_all_products();
        $view_data['images'] = array();
        foreach($view_data['products'] as $product)
        {
            $view_data['images'][$product['id']] = array(
                'id' => $product['id'],
                'image' => json_decode($product['image_path'])
            );
        }
        $view_data['total_products'] = count($this->product->get_all_products());
        $view_data['all_products'] = count($this->product->get_all_products());
        $view_data['men'] = $this->product->category_men(1);
        $view_data['women'] = $this->product->category_women(2);
        $view_data['kids'] = $this->product->category_kids(3);
        $view_data['cart_count'] = $this->session->userdata('cart_count');
        $view_data['csrf'] = $this->csrf;
        $response = array(
            'partial' => $this->load->view('partials/products',$view_data,true),
            'searchbar' => $this->load->view('partials/search',$search_data,true)
        );
        echo json_encode($response);
    }
     // THIS FUNCTION IS FOR SWITCHING FROM CUSTOMER VIEW TOADMIN VIEW
    public function admin_view($view)
    {
        $search_data['admin'] = true;
        $this->load->model('order');
        $view_data['orders'] = array(
            'items' => $this->order->get_all(),
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
        if($view !== 'main')
        {
            $response = array(
                'partial' => $this->load->view('users/admin',$view_data,true),
                'searchbar' => $this->load->view('partials/search',$search_data,true)
            );
            echo json_encode($response);
        }
        else
        {
            $this->load->view('users/admin',$view_data);
        }
       
    }
    // THIS FUNCTION IS FOR SWITCHING FROM ORDER DETAILS TO PRODUCT DETAILS
    public function switch_product_view($what_view)
    { 
        if(!empty($what_view)){
            $view_data['category'] = 'All Products';
        }
        $search_data['admin'] = true;
        $search_data['selected_product'] = true;
        $view_data['products'] = $this->product->admin_get_all();
        $view_data['images'] = array();
        foreach($view_data['products'] as $product)
        {
            $view_data['images'][$product['id']] = array(
                'id' => $product['id'],
                'image' => json_decode($product['image_path'])
            );
        }
        $view_data['sold'] = $this->product->get_sold($view_data['products']);
        $view_data['total_products'] = count($this->product->get_all_products());
        $view_data['all_products'] = count($this->product->get_all_products());
        $view_data['men'] = $this->product->category_men(1);
        $view_data['women'] = $this->product->category_women(2);
        $view_data['kids'] = $this->product->category_kids(3);
        $view_data['cart_count'] = $this->session->userdata('cart_count');
        $view_data['csrf'] = $this->csrf;
        $response = array(
            'partial' => $this->load->view('partials/admin_products',$view_data,true),
            'searchbar' => $this->load->view('partials/search',$search_data,true)
        );
        echo json_encode($response);
    }
    // THIS FUNCTION IS RESPONSIBLE FOR DECIDING WHAT'S THE CORRECT VIEW FOR ADMIN AND USER
    public function product_api()
    {
        if(isset($this->session->userdata('user_data')['is_admin']) && $this->session->userdata('user_data')['is_admin'] === '1')
        {
            redirect('products/admin_view/main');
        }
        else
        {
            $view_data['products'] = $this->product->get_all_products();
            $view_data['images'] = array();
            foreach($view_data['products'] as $product)
            {
                $view_data['images'][$product['id']] = array(
                    'id' => $product['id'],
                    'image' => json_decode($product['image_path'])
                );
            }
            $view_data['total_products'] = count($this->product->get_all_products());
            $view_data['all_products'] = count($this->product->get_all_products());
            $view_data['men'] = $this->product->category_men(1);
            $view_data['women'] = $this->product->category_women(2);
            $view_data['kids'] = $this->product->category_kids(3);
            $view_data['cart_count'] = $this->session->userdata('cart_count');
            $view_data['csrf'] = $this->csrf;
            $this->load->view('partials/products',$view_data);
        }
    }
    // THIS FUNCTION IS FOR GETTING THE CONTENTS OF EVERY CATEGORY IN ADMIN PRODUCT DETAILS
    public function admin_categories($category)
    {
       switch($category)
       {
            case 'men':
                $view_data['category'] = $category;
                $view_data['total_products'] = $this->product->category_men(1);
                break;
            case 'women':
                $view_data['category'] = $category;
                $view_data['total_products'] = $this->product->category_women(2);;
                break;
            case 'kids':
                $view_data['category'] = $category;
                $view_data['total_products'] = $this->product->category_kids(3);
                break;
            default:
                $view_data['category'] = 'All Products';
                $view_data['total_products'] = count($this->product->get_all_products());
                break;
       }
       $view_data['products']  = $this->product->admin_filter_category($this->input->post(null,true));
       $view_data['images'] = array();
       foreach($view_data['products'] as $product)
       {
           $view_data['images'][$product['id']] = array(
               'id' => $product['id'],
               'image' => json_decode($product['image_path'])
           );
       }
       $view_data['all_products'] = count($this->product->get_all_products());
       $view_data['sold'] =$this->product->get_sold($view_data['products']);
       $view_data['men'] = $this->product->category_men(1);
       $view_data['women'] = $this->product->category_women(2);
       $view_data['kids'] = $this->product->category_kids(3);
       $view_data['cart_count'] = $this->session->userdata('cart_count');
       $view_data['csrf'] = $this->csrf;
       $this->load->view('partials/admin_products',$view_data);
    }
    // THIS FUNCTION IS FOR GETTING THE CONTENT OF CATEGORY FOR CUSTOMER SIDE
    public function categories($category)
    {
       switch($category)
       {
            case 'men':
                $view_data['category'] = $category;
                $view_data['total_products'] = $this->product->category_men(1);
                break;
            case 'women':
                $view_data['category'] = $category;
                $view_data['total_products'] = $this->product->category_women(2);;
                break;
            case 'kids':
                $view_data['category'] = $category;
                $view_data['total_products'] = $this->product->category_kids(3);
                break;
            default:
                $view_data['category'] = $category;
                $view_data['total_products'] = count($this->product->get_all_products());
                break;
       }
       $view_data['products']  = $this->product->filter_category($this->input->post(null,true));
       $view_data['images'] = array();
        foreach($view_data['products'] as $product)
        {
            $view_data['images'][$product['id']] = array(
                'id' => $product['id'],
                'image' => json_decode($product['image_path'])
            );
        }
       $view_data['all_products'] = count($this->product->get_all_products());
       $view_data['men'] = $this->product->category_men(1);
       $view_data['women'] = $this->product->category_women(2);
       $view_data['kids'] = $this->product->category_kids(3);
       $view_data['cart_count'] = $this->session->userdata('cart_count');
       $view_data['csrf'] = $this->csrf;
       $this->load->view('partials/products',$view_data);
    }
    // THIS FUNCTION IS FOR PAGINATION
    public function next_page($page)
    {
        switch($this->session->userdata('selected_category'))
        {
             case '1':
                 $view_data['category'] = 'Men';
                 break;
             case '2':
                 $view_data['category'] = 'Women';
                 break;
             case '3':
                 $view_data['category'] = 'Kids';
                 break;
             default:
                 $view_data['category'] = 'All Products';
                 break;
        }
        $cleaned_input = $this->security->xss_clean($page);
        $this->product->get_next_page($cleaned_input);
        $view_data['products'] = $this->session->userdata('next_page');
        $view_data['images'] = array();
        foreach($view_data['products'] as $product)
        {
            $view_data['images'][$product['id']] = array(
                'id' => $product['id'],
                'image' => json_decode($product['image_path'])
            );
        }
        $view_data['total_products'] = count($this->session->userdata('serve_as_products_count'));
        $view_data['all_products'] = count($this->product->get_all_products());
        $view_data['men'] = $this->product->category_men(1);
        $view_data['women'] = $this->product->category_women(2);
        $view_data['kids'] = $this->product->category_kids(3);
        $view_data['cart_count'] = $this->session->userdata('cart_count');
        $view_data['csrf'] = $this->csrf;
        $this->load->view('partials/products',$view_data);
    }
    // THIS FUNCTION IS FOR ADMIN PRODUCT DETAILS SEARCHING
    public function admin_product_search()
    {
        $view_data['products'] = $this->product->admin_search_product($this->input->post(null,true));
        $view_data['images'] = array();
        foreach($view_data['products'] as $product)
        {
            $view_data['images'][$product['id']] = array(
                'id' => $product['id'],
                'image' => json_decode($product['image_path'])
            );
        }
        $view_data['sold'] = $this->product->get_sold($view_data['products']);
        $view_data['total_products'] = count($this->product->get_all_products());
        $view_data['all_products'] = count($this->product->get_all_products());
        $view_data['men'] = $this->product->category_men(1);
        $view_data['women'] = $this->product->category_women(2);
        $view_data['kids'] = $this->product->category_kids(3);
        $view_data['cart_count'] = $this->session->userdata('cart_count');
        $view_data['csrf'] = $this->csrf;
        $this->load->view('partials/admin_products',$view_data);
    }
    // THIS FUNCTION IS FOR CUSTOMER SIDE PRODUCT SEARCHING
    public function search()
    {
        switch($this->session->userdata('selected_category'))
        {
             case '1':
                 $view_data['category'] = 'Men';
                 break;
             case '2':
                 $view_data['category'] = 'Women';
                 break;
             case '3':
                 $view_data['category'] = 'Kids';
                 break;
             default:
                 $view_data['category'] = 'All Products';
                 break;
        }
        $view_data['products'] = $this->product->searching($this->input->post(null,true));
        $view_data['images'] = array();
        foreach($view_data['products'] as $product)
        {
            $view_data['images'][$product['id']] = array(
                'id' => $product['id'],
                'image' => json_decode($product['image_path'])
            );
        }
        $view_data['total_products'] = count($this->session->userdata('serve_as_products_count'));
        $view_data['all_products'] = count($this->product->get_all_products());
        $view_data['men'] = $this->product->category_men(1);
        $view_data['women'] = $this->product->category_women(2);
        $view_data['kids'] = $this->product->category_kids(3);
        $view_data['cart_count'] = $this->session->userdata('cart_count');
        $view_data['csrf'] = $this->csrf;
        $this->load->view('partials/products',$view_data);
    }
    // THIS FUNCTION IS FOR GETTING THE RIGHT CONTENT FOR SPECIFIC PRODUCT IN CUSTOMER SIDE
    public function details($category_id,$id)
    {
        $category_id = $this->security->xss_clean($category_id);
        $id = $this->security->xss_clean($id);
        $view_data['category_id'] = $category_id;
        $view_data['prod_id'] = $id;
        $view_data['details'] = $this->product->product_details($id,$category_id);
        $view_data['images'] = json_decode($view_data['details']['image_path']);
        $view_data['similar_image'] = array();
        $view_data['similar'] = $this->session->userdata('similar');
        foreach($view_data['similar'] as $data)
        {
            $view_data['similar_image'][$data['id']] = array(
                'id' => $data['id'],
                'image' => json_decode($data['image_path'])
            );
        }
        $view_data['csrf'] = $this->csrf;
        return $this->load->view('partials/products_details',$view_data);
    }
    // THIS FUNCTION IS FOR ADDING PRODUCT IN ADMIN SIDE
    public function add_products()
    {
        $this->product->insert($this->input->post(null,true));
        redirect(base_url(''));
    }
    // THIS FUNCTION IS FOR EDITING THE PRODUCT IN ADMIN SIDE
    public function change_products()
    {
        $this->product->update($this->input->post(null,true));
        redirect(base_url(''));
    }
}