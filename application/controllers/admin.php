<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Admin_model');
    }

    public function seed() {
      $this->Admin_model->seed_admin();
      die("Seeded");
    }
    public function index() {
        if (is_admin()) {
            $data['user_role'] = 'admin';
            $data['title'] = 'Dashboard';
            $this->load->view('admin/dashboard', $data);
        } else {
            redirect('admin/login');
        }
    }

    public function login() {
        if (!is_logged_in()) {
            if ($this->input->server('REQUEST_METHOD') === 'POST') {
                if ($this->Admin_model->login()) {
                    redirect('admin');
                } else {
                    $this->session->set_flashdata('form_data', $_POST);
                    redirect('admin/login');
                }
            }
            else{
                $data['user_role'] = 'admin';
                $data['title'] = 'Admin Login';
                $this->load->view('admin/login', $data);
            }
        } else
            redirect('welcome');
    }

    public function forgot_password() {
        if (!is_admin()) {
            if ($this->input->server('REQUEST_METHOD') === 'POST') {
                if ($this->Admin_model->forgot_password()) {
                    $this->session->set_flashdata('message', "Please check your Email and Login. Thank You.");
                    redirect('admin/login');
                } else {
                    $this->session->set_flashdata('form_data', $_POST);
                    redirect('admin/forgot_password');
                }
            } else {
                $data['user_role'] = 'admin';
                $data['title'] = 'Forgot Password';
                $this->load->view('admin/forgot_password', $data);
                $this->load->view('include/footer');
            }
        } else
            redirect('welcome');
    }

    public function verify_forgot_password($token) {
        if ($this->Admin_model->updatePassword_by_token($token))
            redirect('admin/login');
        else {
            $this->session->set_flashdata('message', ERROR_MESSAGE . ": forgot password Verification failed. Try again.");
            redirect('admin/forgot_password');
        }
    }
    
    public function changepassword() {
        if (is_admin()) {
            if ($this->input->server('REQUEST_METHOD') === 'POST') {
                if ($this->Admin_model->updatePassword($this->session->userdata('user_data')->name)) {
                    redirect('admin');
                }
                else{
                    redirect('admin/changepassword');
                }
            } else {
                $data['title'] = 'Change password';
                $this->load->view("admin/change_pwd", $data);
            }
        } else {
            redirect('welcome');
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        $this->session->set_flashdata('message', "Logout Successfully");
        redirect('admin/login');
    }

    public function admin_registration() {
        if (is_admin()) {
            if ($this->input->server('REQUEST_METHOD') === 'POST') {
                if ($this->Admin_model->register()) {
                    $this->session->set_flashdata('message', "Registered Successfully");
                    redirect('admin/view_all');
                } else {
                    $this->session->set_flashdata('form_data', $_POST);
                    redirect('admin/admin_registration');
                }
            } else {
                $data['title'] = 'Admin Registration';
                $this->load->view('admin/register', $data);
            }
        } else {
            redirect('welcome');
        }
    }

    public function email_authentication($token) {
        if ($this->Admin_model->update_status($token)) {
            $this->session->set_flashdata('message', "Verified Successfully");
            redirect('admin/login');
        } else {
            $this->session->set_flashdata('message', ERROR_MESSAGE . ": Not Verifed check your email address.");
            redirect('welcome');
        }
    }

    public function view_all() {
        if (is_admin()) {
            $data['data'] = $this->Admin_model->get_all(FALSE,FALSE, "created_at","id != ",$this->session->userdata('user_data')->id,"role_id != ",1);
            $data['title'] = 'All Admins';
            $this->load->view('admin/view_admins', $data);
        } else {
            redirect('welcome');
        }
    }

    public function change_status($id, $status) {
        if (is_admin()) {
            $this->Admin_model->change_status($id, $status);
            redirect('admin/view_all');
        } else {
            redirect('welcome');
        }
    }
        public function basic_info() {
        if (is_admin()) {
            $data['title'] =  $this->session->userdata('user_data')->name;
            $this->load->view('admin/basic_info', $data);
        } else {
            redirect('welcome');
        }
    }

    public function edit_basic_info() {
        if (is_admin()) {
            if ($this->input->server('REQUEST_METHOD') === 'POST') {
                if ($this->Admin_model->save_basic_info()) {
                    $this->session->set_flashdata('message', "Information Saved");
                    redirect('admin/basic_info');
                } else {
                    redirect('admin/edit_basic_info');
                }
            } else {
                $data['user'] = $this->session->userdata('user_data');
                $data['title'] = 'Edit Basic Info';
                $this->load->view('admin/edit_basic_info', $data);
            }
        } else {
            redirect('welcome');
        }
    }
}
