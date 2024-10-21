<?php
    defined("BASEPATH") OR exit("No direct script access allowed");
    use Cloudinary\Configuration\Configuration;
    use Cloudinary\Cloudinary;

    class Cloud{
        private $cloud; 
        private $action;
        private $get_url;
        public function __construct(){
            $config = Configuration::instance([
                'cloud' => [
                    'cloud_name' => getenv('cloudName'),
                    'api_key' => getenv('apiKey'),
                    'api_secret' => getenv('apiSecret')],
                    'url' => [
                        'secure' => TRUE
                    ]
            ]);         
            $this->get_url = getenv('url');
            $this->cloud = new Cloudinary($config);
            $this->action = $this->cloud->uploadApi();
        }

        public function upload($file_dir,$file_name): void{
             $this->action->upload(
                $file_dir,
                [
                    'public_id' => $file_name,
                    'folder' => 'ecommerce_img',
                    'use_filename'=> true,
                    'overwrite' => true,
                    'assets_folder' => 'ecommerce_img'
                ]
            );
        }

        public function delete($file_name): void{
            $upload = $this->cloud->uploadApi();
            $upload->destroy(
                'ecommerce_img/'. $file_name,
                [
                    'invalidate' => true
                ]
            );
        }

        public function url(): string{
            return $this->get_url;
        }
    }
