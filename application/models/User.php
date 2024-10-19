<?php
    defined("BASEPATH") OR exit("No direct script access allowed");
class User extends CI_Model
{
    // THIS MODEL IS RESPONSIBLE FOR CRUD OPERATION IN USER
    // INLUDING THE LOGIN, USER CREATION
    public bool $valid = true;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }
    public function add_information($data)
    {
        $exstnum = $this->db->query("SELECT id FROM user_information WHERE mobilenumber = ?",array($data['mobilenumber']))->row_array();
        if($this->db->error()['code'] != 0)
        {
            $this->session->set_flashdata('error', 'Query failed' . $this->db->error()['message']);
            return $this->valid = false;
        }
        if(!empty($exstnum))
        {
            $this->session->set_flashdata('error', 'Mobile Number Already Taken');
            return $this->valid = false;
        }
        else
        {
            $this->add_address($data);
        }
       
    }
    public function add_account($data)
    {
       $valid_email = 'SELECT id FROM user_account WHERE email = ?';
       $result = $this->db->query($valid_email,array($data['email']))->row_array();
       if($this->db->error()['code'] != 0)
       {
           $this->session->set_flashdata('error', 'Query failed' . $this->db->error()['message']);
           return $this->valid = false;
       }
       if(!empty($result))
       {
            $this->session->set_flashdata('error', 'Email Already Taken');
            return $this->valid = false;
       }
       else
       {
            $this->add_information($data);
       }
       return $this->valid;
    }
    public function add_address($data)
    {
        $exst = 'SELECT id FROM address WHERE city = ? and street = ? and postal_code = ?';
        $value = array($data['city'],$data['street'],$data['postal']);
        $addresses = $this->db->query($exst,$value)->row_array();
        if($this->db->error()['code'] != 0)
        {
            $this->session->set_flashdata('error', 'Query failed' . $this->db->error()['message']);
            return $this->valid = false;
        }
        if(!empty($addresses))

        {
            $this->final_insert($data,$addresses);
        }
        else
        {
            $query = 'INSERT INTO address (city,street,postal_code,created_at,updated_at) VALUES (?,?,?,NOW(),NOW())';
            $Values = array($data['city'],$data['street'],$data['postal']);
            $this->db->query($query,$Values);
            $id = $this->db->query('SELECT id FROM address WHERE city = ? and street = ? and postal_code = ?',array($data['city'],$data['street'],$data['postal']))->row_array();
            $this->final_insert($data,$id);
        }
    }
    public function final_insert($data,$id)
    {
        // add information
        $info_query = 'INSERT INTO user_information (firstname,lastname,mobilenumber,address_id,created_at,updated_at) VALUES (?,?,?,?,NOW(),NOW())';
        $info_value = array($data['firstname'],$data['lastname'],$data['mobilenumber'],$id);
        $this->db->query($info_query,$info_value);
        $info_id = $this->db->query('SELECT id FROM user_information WHERE mobilenumber = ?',array($data['mobilenumber']))->row_array();
        // add account
        $data['password'] = md5($data['password']);
        $salt = bin2hex(openssl_random_pseudo_bytes('123'));
        $query = 'INSERT INTO user_account (user_information_id,email,password,salt,created_at,updated_at) VALUES (?,?,?,?,NOW(),NOW())';
        $values = array($info_id,$data['email'],$data['password'] . $salt,$salt);
        $this->session->unset_userdata('user_data');
        $this->db->query($query,$values);
    }
    public function user_login($data)
    {
        $query = "SELECT * FROM user_account WHERE email = ? LIMIT 1";
        $value = array($data['email']);
        $result = $this->db->query($query,$value)->row_array();
        if(!empty($result))
        {
            $data['password'] = md5($data['password']);
            if($result['password'] === $data['password'] . $result['salt'])
            {
                $get_info = "SELECT user_information.*,user_account.is_admin FROM user_information LEFT JOIN user_account ON user_information.id = user_account.id WHERE user_information.id = ?";
                $value = array($result['user_information_id']);
                $this->session->set_userdata('is_logged_in', true);
                $this->session->set_userdata('user_data', $this->db->query($get_info,$value)->row_array());
                return true;
            }
            else
            {
                return $this->session->set_flashdata('error', 'Wrong Email or Password');
            }
        }
        else
        {
            return $this->session->set_flashdata('error', 'Wrong Email or Password');
        }
    }
    public function validation($data ,$type)
    {
        switch($type)
        {
            case 'register':
            $this->form_validation->set_rules('firstname', 'FirstName', 'trim|required');
            $this->form_validation->set_rules('lastname', 'LastName', 'trim|required');
            $this->form_validation->set_rules('mobilenumber', 'Mobile Number', 'trim|required|numeric|min_length[11]');
            $this->form_validation->set_rules('city', 'City', 'trim|required');
            $this->form_validation->set_rules('street', 'Street', 'trim|required');
            $this->form_validation->set_rules('postal', 'Postal', 'trim|required|numeric|max_length[10]');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
            $this->form_validation->set_rules('confirm-password', 'Confirmation Password', 'trim|required|matches[password]');
            break;
            default:
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            break;
        }
        if($this->form_validation->run())
        {
            return true;
        }
        else
        {
            return $this->session->set_flashdata('error', validation_errors());
        }
    }
}