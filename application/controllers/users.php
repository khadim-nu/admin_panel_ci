<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
define('FACEBOOK_SDK_V4_SRC_DIR', __DIR__ . '/vendor/facebook/graph-sdk/');
require_once '/var/www/walter/vendor/autoload.php';

class Users extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -  
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('Users_model');
    }

    public function index() {

        redirect('users/join');
    }

    public function join() {
        session_start();
        # login.php
        $fb = new Facebook\Facebook([
            'app_id' => '502684076787978',
            'app_secret' => 'eeb756be705a505d4a67d064a884a779',
            'default_graph_version' => 'v2.5',
            'persistent_data_handler' => 'session'
        ]);

        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email', 'user_friends', 'public_profile']; // optional
        $loginUrl = $helper->getLoginUrl(base_url() . 'users/join_callback', $permissions);

        $this->load->view('users/login', ['loginUrl' => $loginUrl]);
    }

    public function join_callback() {

        session_start();
        $fb = new Facebook\Facebook([
            'app_id' => '502684076787978',
            'app_secret' => 'eeb756be705a505d4a67d064a884a779',
            'default_graph_version' => 'v2.5',
            'persistent_data_handler' => 'session'
        ]);

        $helper = $fb->getRedirectLoginHelper(base_url() . 'users/join_callback');
        $_SESSION['FBRLH_state'] = $_GET['state'];
        try {
            $accessToken = $helper->getAccessToken();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (isset($accessToken)) {
            // Logged in!
            $token = (string) $accessToken;
            $user_details = "https://graph.facebook.com/me?access_token=" . $accessToken;

            $response = file_get_contents($user_details);
            $response = json_decode($response);
            // print_r($response);
            // turn this:
            $data = array(
                'name' => $response->name,
                'socialiD' => $response->id,
                'type' => 'fb'
            );

            if (!$this->Users_model->get_single('socialiD', $response->id)) {
                $this->Users_model->save($data);
            }
            $this->load->view('users/thanks');
            // Now you can redirect to another page and use the
            // access token from $_SESSION['facebook_access_token']
        }
    }

    public function show() {

        $data['users'] = $this->Users_model->get_all();
        $data['title']= 'Show social users';
        $this->load->view('users/show',$data);
    }

}

/* End of file welcome.php */
    /* Location: ./application/controllers/welcome.php */    