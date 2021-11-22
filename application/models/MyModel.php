<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MyModel extends CI_Model {

    var $client_service = "frontend-client";
    var $auth_key       = "simplerestapi";
    var $durasi_login = "9999";

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
        $q  = $this->db->select('password,id,role,nama,lokasi,id_dipa')->from('users')->where('username',$username)->get()->row();
        if($q == ""){
            // echo 'wkwk';
            return array('status' => 400,'message' => 'Username not found.');
        } else {
            $hashed_password = $q->password;
            $id              = $q->id;
            $role = $q->role;
            $nama = $q->nama;
            $lokasi = $q->lokasi;
            $id_dipa = $q->id_dipa;
            if (hash_equals($hashed_password, crypt($password, $hashed_password))) {
               $last_login = date('Y-m-d H:i:s');
               $token = crypt(substr( md5(rand()), 0, 7),"coba-salt");
               $expired_at = date("Y-m-d H:i:s", strtotime("+$this->durasi_login hours"));
               $this->db->trans_start();
               $this->db->where('id',$id)->update('users',array('last_login' => $last_login));
               $this->db->insert('users_authentication',array('users_id' => $id,'token' => $token,'expired_at' => $expired_at));
               if ($this->db->trans_status() === FALSE){
                  $this->db->trans_rollback();
                  return array('status' => 500,'message' => 'Internal server error.');
               } else {
                  $this->db->trans_commit();
                  return array('status' => 200,'message' => 'Successfully login.',
                                'id' => $id, 'token' => $token,
                                'nama' => $nama, 'lokasi' => $lokasi,'role' => $role,
                                'id_dipa' => $id_dipa
                                );
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
                $expired_at = date("Y-m-d H:i:s", strtotime("+$this->durasi_login hours"));
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
        return $this->db->select('*')->from('folder_data_dukung')->order_by('id','asc')->get()->result();
    }

    public function book_detail_data($id)
    {
        return $this->db->select('id,title,author')->from('books')->where('id',$id)->order_by('id','desc')->get()->row();
    }

    public function get_row_detail($table, $id)
    {
        return $this->db->select('*')->from($table)->where('id',$id)->order_by('id','desc')->get()->row();
    }

    public function get_row_detail_by_foreignkey($table, $foreignkey, $column)
    {
        return $this->db->select('*')->from($table)->where($column,$foreignkey)->order_by('id','desc')->get()->result();
    }

    public function get_row_detail_by_two_foreignkey($table, $foreignkey1, $foreignkey2, $column1, $column2)
    {
        return $this->db->select('*')->from($table)->where([$column1=>$foreignkey1, $column2 => $foreignkey2])->order_by('id','desc')->get()->result();
    }

    public function get_all_rows_table($table_name)
    {
        return $this->db->select('*')->from($table_name)->order_by('id','asc')->get()->result();
    }

    public function get_last_id($table_name){
        return $this->db->select('id')->from($table_name)->order_by('id','desc')->get()->result()[0]->{'id'};
    }

    public function insert_to_table($table_name, $data){
        // try{
        //     $this->db->insert($table_name,$data);
        //     $db_error = $this->db->error(); //cek kalo ada error
        //     if (!empty($db_error)) {
        //         throw new Exception('Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message']);
        //     }
        //     return array('status' => 201,'message' => 'Data berhasil di-insert');
        // } catch(Exception $e){
        //     return array('status' => 500,'message' => $e->getMessage());
        // }
        $this->db->insert($table_name,$data);
        return array('status' => 201,'message' => 'Data berhasil di-insert');
    }

    public function update_data_table($table_name, $id,$data)
    {
        if($this->is_exist($table_name, $id)){
            $this->db->where('id',$id)->update($table_name,$data);
            return array('status' => 200,'message' => 'Data berhasil di update');
        } 
        else return array('status' => 200,'message' => 'Tidak ada ID yang cocok');

    }

    public function delete_data_table($table_name, $id)
    {
        if($this->is_exist($table_name, $id)){
            $this->db->where('id',$id)->delete($table_name);
            return array('status' => 200,'message' => 'Data has been deleted.');
        } 
        else return array('status' => 200,'message' => 'There is no matching ID.');
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

    public function delete_all_rows($table_name)
    {
        $this->db->truncate($table_name);
    }

    public function delete_where($table_name, $column_name, $value){
        $this->db->query("DELETE FROM $table_name WHERE $column_name = $value");
    }

    public function total_pagu_by_kode_satker($kode_satker){
        $result = $this->db->query("SELECT SUM(nominal_akun) AS jumlah FROM api_dipa_pusdatin WHERE kode_satker = $kode_satker")->result();
        return $result[0]->jumlah;
    }

    public function total_realisasi_by_kode_satker($kode_satker){
        $result = $this->db->query("SELECT SUM(nominal_akun) AS jumlah FROM api_realisasi_pusdatin WHERE kode_satker = $kode_satker")->result();
        return $result[0]->jumlah;
    }

    public function total_realisasi_by_kode_satker_monsakti($kode_satker){
        $result = $this->db->query("SELECT SUM(jumlah_realisasi) AS jumlah FROM api_realisasi_monsakti WHERE kode_satker = $kode_satker")->result();
        return $result[0]->jumlah;
    }

    public function total_realisasi_jenis_belanja_by_kode_satker($kode_satker){
        return $this->db->query("SELECT SUBSTRING(kode_akun, 1,2) AS jenis_belanja, SUM(nominal_akun) AS total_realisasi FROM api_realisasi_pusdatin WHERE kode_satker = $kode_satker GROUP BY SUBSTRING(kode_akun, 1,2) ")->result();
    }

    public function total_realisasi_jenis_belanja_monsakti(){
        return $this->db->query("SELECT kode_satker, SUBSTRING(kode_akun, 1,2) AS jenis_belanja, SUM(jumlah_realisasi) AS total_realisasi FROM api_realisasi_monsakti GROUP BY kode_satker, SUBSTRING(kode_akun, 1,2) ")->result();
    }

    public function total_realisasi_jenis_belanja_perbulan_monsakti(){
        return $this->db->query("SELECT kode_satker, SUBSTRING(tanggal_realisasi, 4,3) AS bulan_realisasi, SUBSTRING(kode_akun, 1,2) AS jenis_belanja, SUM(jumlah_realisasi) AS total_realisasi FROM api_realisasi_monsakti GROUP BY kode_satker, SUBSTRING(tanggal_realisasi, 4,3), SUBSTRING(kode_akun, 1,2) ")->result();
    }

    public function total_realisasi_jenis_belanja_by_kode_satker_monsakti($kode_satker){
        return $this->db->query("SELECT SUBSTRING(kode_akun, 1,2) AS jenis_belanja, SUM(jumlah_realisasi) AS total_realisasi FROM api_realisasi_monsakti WHERE kode_satker = $kode_satker GROUP BY SUBSTRING(kode_akun, 1,2) ")->result();
    }

    public function total_realisasi_jenis_belanja_perbulan_by_kode_satker_monsakti($kode_satker){
        return $this->db->query("SELECT kode_satker, SUBSTRING(tanggal_realisasi, 4,3) AS bulan_realisasi, SUBSTRING(kode_akun, 1,2) AS jenis_belanja, SUM(jumlah_realisasi) AS total_realisasi FROM api_realisasi_monsakti WHERE kode_satker = $kode_satker GROUP BY kode_satker, SUBSTRING(tanggal_realisasi, 4,3), SUBSTRING(kode_akun, 1,2) ")->result();
    }

    public function total_realisasi_jenis_belanja(){
        return $this->db->query("SELECT kode_satker, SUBSTRING(kode_akun, 1,2) AS jenis_belanja, SUM(nominal_akun) AS total_realisasi FROM api_realisasi_pusdatin GROUP BY kode_satker, SUBSTRING(kode_akun, 1,2) ")->result();
    }

    
    
    public function total_pagu(){
        return $this->db->query("SELECT kode_satker, SUM(nominal_akun) AS jumlah FROM api_dipa_pusdatin GROUP BY kode_satker")->result();
    }

    public function total_realisasi(){
        return $this->db->query("SELECT kode_satker, SUM(nominal_akun) AS jumlah FROM api_realisasi_pusdatin GROUP BY kode_satker")->result();
    }

    public function total_realisasi_monsakti(){
        return $this->db->query("SELECT kode_satker, SUM(jumlah_realisasi) AS jumlah FROM api_realisasi_monsakti GROUP BY kode_satker")->result();
    }

    public function total_pelaksanaan_anggaran_akun_detil(){
        return $this->db->query("SELECT id_dipa, id_pelaksanaan_anggaran, COUNT(*) as jumlah_akun_detil , SUM(jumlah_realisasi) as total_realisasi FROM pelaksanaan_anggaran JOIN pelaksanaan_angGaran_akun_detil ON pelaksanaan_anggaran.id = id_pelaksanaan_anggaran GROUP BY id_dipa, id_pelaksanaan_anggaran")->result();
    }

    public function total_pelaksanaan_anggaran_akun_detil_by_dipa($id_dipa){
        return $this->db->query("SELECT id_dipa, id_pelaksanaan_anggaran, COUNT(*) as jumlah_akun_detil , SUM(jumlah_realisasi) as total_realisasi FROM pelaksanaan_anggaran JOIN pelaksanaan_angGaran_akun_detil ON pelaksanaan_anggaran.id = id_pelaksanaan_anggaran WHERE id_dipa = $id_dipa GROUP BY id_dipa, id_pelaksanaan_anggaran")->result();
    }

    public function coba(){
        
        //DAPETIN TOTAL ANGGARAN DIPA HAM
        // $result = $this->db->query("SELECT kode_satker, SUM(nominal_akun) AS jumlah FROM api_dipa_pusdatin GROUP BY kode_satker")->result();
        // echo json_encode($result);

        $result = $this->db->query("SELECT SUBSTRING(kode_akun, 1,2) AS JENIS_BELANJA, SUM(nominal_akun) AS TOTAL_REALISASI FROM api_realisasi_pusdatin GROUP BY kode_satker, SUBSTRING(kode_akun, 1,2) ")->result();
        
        echo json_encode($result);
    }

}
