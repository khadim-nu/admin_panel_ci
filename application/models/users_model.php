<?php

class Users_model extends Common_model {

    public function __construct() {
        parent::__construct();
        $this->table_name = "users";
    }
}
