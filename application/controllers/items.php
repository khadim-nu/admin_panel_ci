<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Items extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Items_model');
    }

    public function index() {
        if (is_admin()) {
            redirect('admin/showNumbers');
        } else {
            redirect('admin/login');
        }
    }

    public function showNumbers() {
        if (is_admin()) {
            $data['user_role'] = 'admin';
            $data['title'] = 'Show Numbers';
            $data['items'] = $this->Items_model->get_all($limit = FALSE, $start = 0);
            $this->load->view('items/index', $data);
        } else {
            redirect('admin/login');
        }
    }

    public function import() {
        if (is_admin()) {
            $data['user_role'] = 'admin';
            $data['title'] = 'Import Numbers';
            $this->load->view('items/import', $data);
        } else {
            redirect('admin/login');
        }
    }

    public function save() {
        if (is_admin()) {
            /////////////
            $filename = $_FILES["file"]["tmp_name"];

            if ($_FILES["file"]["size"] > 0) {

                $file = fopen($filename, "r");
//                
                $index = 0;
//
                while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE) {
                    if ($index != 0 && isset($emapData[1]) && !empty(trim($emapData[1]))) {
                        $item = array(
                            'uniqueId' => trim($emapData[0]),
                            'phone' => trim($emapData[1]),
                        );
                        $this->Items_model->save($item);
                    }
                    $index++;
                }
                $this->session->set_flashdata('message', "Phone Numbers imported successfully");
                redirect('items/showNumbers');
            } else {
                $this->session->set_flashdata('message', ERROR_MESSAGE . ": No Data availabe");
                redirect('items/import');
            }
        } else {
            redirect('admin/login');
        }
    }

    public function export() {
        if (is_admin()) {
            $data['user_role'] = 'admin';
            $data['title'] = 'Export data';
            $this->load->view('items/export', $data);
        } else {
            redirect('admin/login');
        }
    }

    public function export_to_CSV() {
        if (is_admin()) {
            // this is for auction  otherwise 0.00]
            $name = "exported-items"; //This will be the name of the csv file.
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=' . $name . '.csv');
            $output = fopen('php://output', 'wt');
//               fputcsv($output, array('heading1', 'heading2', 'heading... n')); //The column heading row of the csv file

            $items = $this->Items_model->get_all($limit = FALSE, $start = 0);

            foreach ($items as $key => $value) {
                if (!empty($value['phone'])) {
                    // calling API
                    $item = array(
                        $value['uniqueId'],
                        $value['phone'],
                    );
                    fputcsv($output, $item);
                }
            }

            fclose($output);
            if (count($items) == 0) {
                $this->session->set_flashdata('message', ERROR_MESSAGE . ": No Data availabe");
                redirect('items');
            }
        } else {
            redirect('welcome');
        }
    }

    public function deleteNumbers() {
        if (is_admin()) {
            $this->db->from('items');
            $this->db->truncate();
            redirect('items/import');
        } else {
            redirect('admin/login');
        }
    }

}
