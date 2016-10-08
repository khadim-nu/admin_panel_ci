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
                    if ($index != 0 && isset($emapData[0]) && !empty(trim($emapData[0]))) {
                        $uid = isset($emapData[1]) ? $emapData[0] : 0;
                        $phone = isset($emapData[1]) ? $emapData[1] : $emapData[0];
                        $item = array(
                            'uniqueId' => trim($uid),
                            'phone' => trim($phone),
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

    protected function curl_req($url, $params = array(), $method = 'get') {
        $ch = curl_init();

        if ($method === 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
        }
        if (!empty($params)) {
            if ($method === 'post') {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            } else {
                $url .= (strpos($url, '?') === FALSE ? '?' : '&') . http_build_query($params);
            }
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        // Set cookiejar and cookiefile
        // $cookieJar = 'cookiejar.txt';
        // curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
        //  curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
        // Local...
        // curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:1080');
        // curl_setopt($ch, CURLOPT_PROXYTYPE, 7);
        // Local...

        $result = curl_exec($ch);

        if (empty($result)) {
            $error = curl_error($ch);
            if (!empty($error)) {
                echo "$url: $error";
            }
        }
        curl_close($ch);

        return $result;
    }

    protected function _keyValuesArr($obj) {
        $keys = array();
        $values = array();
        $result = array('keys' => $keys, 'values' => $values);
        if (is_object($obj)) {
            $arr = (array) $obj;
            foreach ($arr as $key => $value) {
                if (!is_object($value) && !is_array($value)) {
                    array_push($result['keys'], $key);
                    array_push($result['values'], $value);
                } else {
                    $r = $this->_keyValuesArr($value);
                    $result['keys'] = array_merge($result['keys'], $r['keys']);
                    $result['values'] = array_merge($result['values'], $r['values']);
                }
            }
        } else if (is_array($obj)) {
            foreach ($obj as $key => $value) {
                if (!is_object($value) && !is_array($value)) {
                    array_push($result['keys'], $key);
                    array_push($result['values'], $value);
                } else {
                    $r = $this->_keyValuesArr($value);
                    $result['keys'] = array_merge($result['keys'], $r['keys']);
                    $result['values'] = array_merge($result['values'], $r['values']);
                }
            }
        }

        return $result;
    }

    public function export_to_CSV() {
        $api_url = 'https://proapi.whitepages.com/3.0/phone';

        if (is_admin()) {
            // this is for auction  otherwise 0.00]
            $name = "exported-items"; //This will be the name of the csv file.
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=' . $name . '.csv');
            $output = fopen('php://output', 'wt');
            //
            fputcsv($output, array(
                'phone_number', 'is_valid', 'country_calling_code', 'line_type',
                'carrier', 'is_prepaid', 'is_commercial', 'name', 'age_range',
                'gender', 'street_line_1',
                'street_line_2', 'city', 'postal_code', 'zip4', 'state_code',
                'country_code', 'latitude', 'longitude', 'accuracy', 'is_active',
                'delivery_point', 'street_line_1', 'street_line_2', 'city',
                'postal_code', 'zip4', 'state_code', 'country_code', 'latitude',
                'longitude', 'accuracy', 'is_active', 'delivery_point',
                'associated_id', 'associated_name', 'associated_relation',
                'alternate_phones', 'warnings',
            )); //The column heading row of the csv file

            $items = $this->Items_model->get_all($limit = FALSE, $start = 0);

            $api_key = trim($this->input->post('key'));

            foreach ($items as $key => $value) {
                if (!empty($value['phone'])) {
                    // calling API
                    $params = array(
                        'api_key' => $api_key,
                        'phone' => $value['phone']
                    );
                    $response = $this->curl_req($api_url, $params, 'get');
                    $response = json_decode($response);
                    $belongs_to = !empty($response->belongs_to) ? $response->belongs_to[0] : '';
                    $current_addresses = !empty($response->current_addresses) ? $response->current_addresses[0] : '';
                    $lat_long = isset($current_addresses->lat_long) ? $current_addresses->lat_long : '';
                    $associated_people = !empty($response->associated_people) ? $response->associated_people[0] : '';
                    $alternate_phones = !empty($response->alternate_phones) ? $response->alternate_phones[0] : '';
                    if (is_object($alternate_phones))
                        $alternate_phones = (array) $alternate_phones;
                    if (is_array($alternate_phones)) {
                        $alternate_phones = implode(',', $alternate_phones);
                    }
                    $warnings = !empty($response->warnings) ? $response->warnings[0] : '';

                    $item = array(
                        isset($response->phone_number) ? $response->phone_number : '',
                        isset($response->is_valid) ? $response->is_valid : '',
                        isset($response->country_calling_code) ? $response->country_calling_code : '',
                        isset($response->line_type) ? $response->line_type : '',
                        isset($response->carrier) ? $response->carrier : '',
                        isset($response->is_prepaid) ? $response->is_prepaid : '',
                        isset($response->is_commercial) ? $response->is_commercial : '',
                        isset($belongs_to->name) ? $belongs_to->name : '',
                        isset($belongs_to->age_range) ? $belongs_to->age_range : '',
                        isset($belongs_to->gender) ? $belongs_to->gender : '',
                        isset($current_addresses->street_line_1) ? $current_addresses->street_line_1 : '',
                        isset($current_addresses->street_line_2) ? $current_addresses->street_line_2 : '',
                        isset($current_addresses->city) ? $current_addresses->city : '',
                        isset($current_addresses->postal_code) ? $current_addresses->postal_code : '',
                        isset($current_addresses->zip4) ? $current_addresses->zip4 : '',
                        isset($current_addresses->state_code) ? $current_addresses->state_code : '',
                        isset($current_addresses->country_code) ? $current_addresses->country_code : '',
                        isset($current_addresses->is_active) ? $current_addresses->is_active : '',
                        isset($current_addresses->delivery_point) ? $current_addresses->delivery_point : '',
                        isset($lat_long->latitude) ? $lat_long->latitude : '',
                        isset($lat_long->longitude) ? $lat_long->longitude : '',
                        isset($lat_long->accuracy) ? $lat_long->accuracy : '',
                        isset($lat_long->id) ? $lat_long->id : '',
                        isset($lat_long->name) ? $lat_long->name : '',
                        isset($lat_long->relation) ? $lat_long->relation : '',
                        $alternate_phones,
                        $warnings,
                    );
                    fputcsv($output, $item);
                }
            }

            // fclose($output);
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
