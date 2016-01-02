<?php

class Admin_model extends Common_model {

    public function __construct() {
        parent::__construct();
        $this->table_name = "admin";
    }

    public function seed_admin($name="Temp Admin", $email="spdevtesting@gmail.com", $pass = "12345678", $role = ADMIN, $status = 1) {
        if (!$this->Admin_model->is_already_registered($email)) {
            $token = md5(uniqid() . microtime() . rand());
            $insert_data = array(
                'name' => $name,
                'email' => $email,
                'password' => md5($pass),
                'token' => $token,
                'status' => $status,
                'role_id' => $role,
                'created_by'=>1
            );
            $result = $this->db->insert($this->table_name, $insert_data);
            if ($result) {
                // sending pararmeter for sending email
                $recipent_data = array();
                $recipent_data['password'] = $pass;
                $recipent_data['email'] = $email;
                $recipent_data['name'] = $name;
                $recipent_data['type'] = 'Admin';
                //calling function in email_templates_helper
                $email_status = seed_email($recipent_data);
            }
            return $result;
        } else {
            return false;
        }
    }

    public function register() {
        $this->load->library('form_validation');
        if ($this->form_validation->run('admin-registration') == FALSE) {
            $this->session->set_flashdata('message', ERROR_MESSAGE . ":" . validation_errors());
            return FALSE;
        }
        $token = md5(uniqid() . microtime() . rand());
        $password = generatePassword();
        $email = $this->input->post('email');
        $insert_data = array(
            'name' => $this->input->post('name'),
            'email' => $email,
            'password' => md5($password),
            'token' => $token,
            'status' => 0,
            'role_id' => ADMIN,
            'created_at'=>$today_datetime,
            'created_by'=>$this->session->userdata('user_data')->id
        );
        $result = $this->db->insert($this->table_name, $insert_data);
        $this->session->set_flashdata('message', ERROR_MESSAGE . ":Registration failed. Something went wrong");
        if ($result) {
            $this->session->set_flashdata('message', "Registered Successfully");
            // sending pararmeter for sending email
            $recipent_data = array();
            $recipent_data['password'] = $password;
            $recipent_data['email'] = $email;
            $recipent_data['type'] = 'Admin';
            $recipent_data['name'] = $this->input->post('name');
            //calling function in email_templates_helper
           // $email_status = register_email($recipent_data);

            // to admin
            $recipent_data['token'] = $token;
            $recipent_data['url'] = '<a href="' . base_url() . 'admin/email_authentication/' . $token . '"> verify here</a>';
            $email_status = admin_registration_verification_email($recipent_data);
        }
        return $result;
    }

    public function update_status($token) {
        $dt = new DateTime();
        $dt = $dt->format('Y-m-d H:i:s');
        $insert_data = array(
            'status' => 1,
            'updated_at'=>$today_datetime
        );
        $this->db->where('token', $token);
        $user = $this->Admin_model->get_single("token", $token);
        $status_val = 'Verified';
        if ($this->db->update($this->table_name, $insert_data)) {
            // sending pararmeter for sending email
            $recipent_data = array();
            $recipent_data['email'] = $user->email;
            $recipent_data['type'] = 'Admin';
            $recipent_data['name'] = $user->name;
            //calling function in email_templates_helper
            $email_status = verify_registration_email($recipent_data);
            return true;
        }
        return false;
    }

    public function change_status($id, $status) {
        if (is_admin()) {
            $status = ($status == 1) ? 0 : 1;
            $dt = new DateTime();
            $dt = $dt->format('Y-m-d H:i:s');
            $insert_data = array(
                'status' => $status,
                'updated_at'=>$dt,
                'updated_by'=>$this->session->userdata('user_data')->id
            );
            $this->db->where('id', $id);
            $result = $this->db->update($this->table_name, $insert_data);
            if ($result) {
                $user = $this->Admin_model->get_single("id", $id);
                $status_val = get_status($status);
                // sending pararmeter for sending email
                $recipent_data = array();
                $recipent_data['value1'] = $status_val;
                $recipent_data['email'] = $user->email;
                $recipent_data['name'] = $user->name;
                //calling function in email_templates_helper
                $email_status = change_status_email($recipent_data);
                $this->session->set_flashdata('message', "Status updated");
                return true;
            }
        }
        return false;
    }

   
    public function save_basic_info() {
        if (is_admin()) {
            $this->load->library('form_validation');
            if ($this->form_validation->run('admin-edit') == FALSE) {
                $this->session->set_flashdata('message', ERROR_MESSAGE . ":" . validation_errors());
                return FALSE;
            }
            $email = $this->session->userdata('user_data')->email;
            ////  uploading profile picture ///
            $profileImageData = $this->Admin_model->upload_image('profileImage');
            $dt = new DateTime();
            $dt = $dt->format('Y-m-d H:i:s');
            $insert_data = array(
                'name' => $this->input->post('name'),
                'updated_at' => $dt,
            );
            if ($profileImageData) {
                $insert_data['image_url'] = base_url() . UPLOAD_PATH . $profileImageData['upload_data']['orig_name'];
            } elseif (!empty($_FILES['profileImage']['name'])) {
                return FALSE;
            }
            $this->db->where('id', $this->session->userdata('user_data')->id);
            $result = $this->db->update($this->table_name, $insert_data);
            if ($result) {
                $updated_user = $this->session->userdata('user_data');
                $updated_user->name = $this->input->post('name');
                $updated_user->updated_at = $dt;
                if ($profileImageData) {
                    $updated_user->imageUrl = base_url() . UPLOAD_PATH . $profileImageData['upload_data']['orig_name'];
                }
                $this->session->set_userdata('user_data', $updated_user);
                // sending pararmeter for sending email
                $recipent_data = array();
                $recipent_data['name'] = $this->input->post('name');
                $recipent_data['email'] = $this->session->userdata('user_data')->email;
                $recipent_data['name'] = $this->session->userdata('user_data')->name;
                //calling function in email_templates_helper
                $email_status = email_edit_basic_info($recipent_data);
                return $result;
            } else {
                $this->session->set_flashdata('message', ERROR_MESSAGE . ":Provide Correct Information.");
                return $result;
            }
        }
        return false;
    }
}
