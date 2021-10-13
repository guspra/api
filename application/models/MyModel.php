<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MyModel extends CI_Model {

    var $client_service = "frontend-client";
    var $auth_key       = "simplerestapi";

    public function check_auth_client(){
        $client_service = $this->input->get_request_header('Client-Service', TRUE);
        $auth_key  = $this->input->get_request_header('Auth-Key', TRUE);
        if($client_service == $this->client_service && $auth_key == $this->auth_key){
            return true;
        } else {
            return json_output(401,array('status' => 401,'message' => 'Unauthorized.'));
        }
    }

    public function login($username,$password)
    { 
        $q  = $this->db->select('password,id')->from('users')->where('username',$username)->get()->row();
        if($q == ""){
            // echo 'wkwk';
            return array('status' => 400,'message' => 'Username not found.');
        } else {
            $hashed_password = $q->password;
            $id              = $q->id;
            if (hash_equals($hashed_password, crypt($password, $hashed_password))) {
               $last_login = date('Y-m-d H:i:s');
               $token = crypt(substr( md5(rand()), 0, 7),"coba-salt");
               $expired_at = date("Y-m-d H:i:s", strtotime('+12 hours'));
               $this->db->trans_start();
               $this->db->where('id',$id)->update('users',array('last_login' => $last_login));
               $this->db->insert('users_authentication',array('users_id' => $id,'token' => $token,'expired_at' => $expired_at));
               if ($this->db->trans_status() === FALSE){
                  $this->db->trans_rollback();
                  return array('status' => 500,'message' => 'Internal server error.');
               } else {
                  $this->db->trans_commit();
                  return array('status' => 200,'message' => 'Successfully login.','id' => $id, 'token' => $token);
               }
            } else {
               return array('status' => 400,'message' => 'Wrong password.');
            }
        }
        
    }

    public function logout()
    {
        $users_id  = $this->input->get_request_header('User-ID', TRUE);
        $token     = $this->input->get_request_header('Authorization', TRUE);
        $this->db->where('users_id',$users_id)->where('token',$token)->delete('users_authentication');
        return array('status' => 200,'message' => 'Successfully logout.');
    }

    public function auth()
    {
        $users_id  = $this->input->get_request_header('User-ID', TRUE);
        $token     = $this->input->get_request_header('Authorization', TRUE);
        $q  = $this->db->select('expired_at')->from('users_authentication')->where('users_id',$users_id)->where('token',$token)->get()->row();
        if($q == ""){
            // echo '1';
            return json_output(401,array('status' => 401,'message' => 'Unauthorized.'));
        } else {
            if($q->expired_at < date('Y-m-d H:i:s')){
                return json_output(401,array('status' => 401,'message' => 'Your session has been expired.'));
            } else {
                $updated_at = date('Y-m-d H:i:s');
                $expired_at = date("Y-m-d H:i:s", strtotime('+12 hours'));
                $this->db->where('users_id',$users_id)->where('token',$token)->update('users_authentication',array('expired_at' => $expired_at,'updated_at' => $updated_at));
                return array('status' => 200,'message' => 'Authorized.');
            }
        }
    }

    public function book_all_data()
    {
        return $this->db->select('id,title,author')->from('books')->order_by('id','desc')->get()->result();
    }

    public function folderdatadukung_all_data()
    {
        return $this->db->select('uraian')->from('folder_data_dukung')->order_by('id','asc')->get()->result();
    }

    public function book_detail_data($id)
    {
        return $this->db->select('id,title,author')->from('books')->where('id',$id)->order_by('id','desc')->get()->row();
    }

    public function insert_to_table($table_name, $data){
        try{
            $this->db->insert($table_name,$data);
            $db_error = $this->db->error(); //cek kalo ada error
            if (!empty($db_error)) {
                throw new Exception('Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message']);
            }
            return array('status' => 201,'message' => 'Data berhasil di-insert');
        } catch(Exception $e){
            return array('status' => 500,'message' => $e->getMessage());
        }
    }

    public function book_create_data($data)
    {
        $this->db->insert('books',$data);
        return array('status' => 201,'message' => 'Data has been created.');
    }

    public function is_exist($table_name, $id){
        return $this->db->query("SELECT * FROM $table_name WHERE id = $id")->num_rows();
    }

    public function book_update_data($id,$data)
    {
        if($this->is_exist('books', $id)){
            $this->db->where('id',$id)->update('books',$data);
            return array('status' => 200,'message' => 'Data has been updated.');
        } 
        else return array('status' => 200,'message' => 'There is no matching ID.');

    }

    public function book_delete_data($id)
    {
        if($this->is_exist('books', $id)){
            $this->db->where('id',$id)->delete('books');
            return array('status' => 200,'message' => 'Data has been deleted.');
        } 
        else return array('status' => 200,'message' => 'There is no matching ID.');
    }

}
