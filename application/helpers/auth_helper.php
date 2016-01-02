<?php
function is_admin() {
    
  $CI = & get_instance();
  if($CI->session->userdata('user_data')){
    if($CI->session->userdata('is_admin')){
      return true;
    }
  }
  return false;
}

function is_logged_in(){
  $CI = & get_instance();  
  if($CI->session->userdata('user_data')){
      return true;
  } 
  else{
      return false;
  }
}
function get_status($status){
    if($status==0){
        return "Inactive";
    }
    else if($status==1){
        return "Active";
    }
    else{
        return "";
    }
}
function get_role_string($role_id){
    if($role_id==1){
        return "Super Admin";
    }
    else if($role_id==2){
        return "Admin";
    }
    else{
        return "";
    }
}
function change_status($statusNo) {
    if($statusNo==0){
        return "Activate";
    }
    else if($statusNo==1){
        return "Deactivate";
    }
    else{
        return "---";
    }
    
}
