<?php

class Common_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->primary_key = "id";
        $this->load->helper("random_password");
        $this->load->helper('array_search');
        $this->load->library('Rabbitmq');
    }

    public function login() {
        $this->load->library('form_validation');
        if ($this->form_validation->run('login') == FALSE) {
            $this->session->set_flashdata('message', ERROR_MESSAGE . ":" . validation_errors());
            return FALSE;
        }
        $email = $this->input->post('email');
        $pass = ($this->input->post('password'));

        //$sql = "email=? and pass=?";

        $this->db->where("email", $email);
        $this->db->where("password", md5($pass));
        $query = $this->db->get($this->table_name);

        $this->session->set_flashdata('message', ERROR_MESSAGE . ":Login failed. Incorrect details.");
        if ($query->num_rows()) {
            $row = $query->row();
            if ($row->is_from_third_party == 0) {
                if ($row->status == 1) {
                    $this->session->set_userdata('user_data', $row);
                    $this->session->set_userdata('is_' . $this->table_name, 'true');
                    $this->session->set_flashdata('message', "Logged in successfully");
                    return true;
                } else {
                    $this->session->set_flashdata('message', ERROR_MESSAGE . ":Your account status is not active");
                }
            }
        }
        return false;
    }

    public function updatePassword($name) {
        if ($this->input->post('new-password') == $this->input->post('confirm-password')) {
            if ($this->session->userdata('user_data')->password == md5($this->input->post('old-password'))) {
                $pass = md5($this->input->post('new-password'));
                $user = $this->session->userdata('user_data');
                $dt = new DateTime();
                $dt = $dt->format('Y-m-d H:i:s');
                $insert_data = array(
                    'password' => $pass,
                    'updated_at' => $dt
                );
                $this->db->where($this->primary_key, $user->id);
                $result = $this->db->update($this->table_name, $insert_data);
                if ($result) {
                    $user->pass = $pass;
                    $this->session->set_userdata('user_data', $user);
                    // sending pararmeter for sending email
                    $recipent_data = array();
                    $recipent_data['password'] = $this->input->post('new-password');
                    $recipent_data['email'] = $user->email;
                    $recipent_data['name'] = $name;
                    //calling function in email_templates_helper
                    $email_status = update_password_email($recipent_data);
                    $this->session->set_flashdata('message', "Password changed Successfully");
                    return true;
                }
                $this->session->set_flashdata('message', ERROR_MESSAGE . ":Failed to change password");
                return false;
                //////////////////////////
            } else {
                $this->session->set_flashdata('message', ERROR_MESSAGE . ":Failed to change password. Old password is not correct.");
            }
        } else {
            $this->session->set_flashdata('message', ERROR_MESSAGE . ":Failed to change password. New-password and Confirm-Password are not equal");
        }
        return false;
    }

    public function updatePassword_by_token($token) {

        $this->db->where("token", $token);
        $query = $this->db->get($this->table_name);

        if ($query->num_rows()) {
            $row = $query->row();
            if ($row->status == 1) {
                $password = generatePassword();
                $recipent_data = array();
                $recipent_data['password'] = $password;
                $recipent_data['email'] = $row->email;
                $name = explode("@", $row->email);
                $recipent_data['name'] = $name[0];
                //updating password
                $insert_data = array(
                    'password' => md5($password)
                );
                $this->db->where('email', $row->email);
                $this->db->where('token', $token);
                if ($this->db->update($this->table_name, $insert_data)) {
                    update_password_email($recipent_data);
                }
                $this->session->set_flashdata('message', ERROR_MESSAGE . ": Password is changed, Please check your Email id. And login.. Thank you");
                return true;
            } else {
                $this->session->set_flashdata('message', ERROR_MESSAGE . ": You can not retrieve password. Your account is not active.");
            }
        } else {
            $this->session->set_flashdata('message', ERROR_MESSAGE . ": Provide correct details");
        }
        return false;
    }

    public function forgot_password() {
        $this->load->library('form_validation');
        if ($this->form_validation->run('forgot_password') == FALSE) {
            $this->session->set_flashdata('message', ERROR_MESSAGE . ":" . validation_errors());
            return FALSE;
        }
        $email = $this->input->post('email');
        $this->db->where("email", $email);
        $query = $this->db->get($this->table_name);

        if ($query->num_rows() == 1) {
            $row = $query->row();
                if ($row->status == 1) {
                    $password = generatePassword();
                    $recipent_data = array();
                    $recipent_data['password'] = $password;
                    $recipent_data['email'] = $row->email;
                    //$name = explode("@", $row->email);
                    $recipent_data['name'] = $row->name;
                    $recipent_data['url'] = base_url() . $this->table_name . '/verify_forgot_password/' . $row->token;
                    forgot_password_email($recipent_data);
                    return true;
                } else {
                    $this->session->set_flashdata('message', ERROR_MESSAGE . ":Your Account is not active, Please contact to Admin.");
                    return FALSE;
                }
        }
        $this->session->set_flashdata('message', ERROR_MESSAGE . ":Provide your correct Email ID");
        return false;
    }
}
