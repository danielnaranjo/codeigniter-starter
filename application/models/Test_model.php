<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Test_model extends CI_Model {

        public function __construct(){
            $this->load->database();
            $this->load->helper('file');
        }

        public function yaml(){
            $data = [];
            //$yaml_template = [];
            $tables = $this->db->list_tables();
            foreach ($tables as $key=>$val){
                $data[$key] = $val;
                echo $val."<br>";
                //$yaml_template[$key] = $val;
                $columnas = $this->db->field_data($val);
                foreach ($columnas as $k => $v) {
                    // $response[$k] = $v;
                    foreach ($v as $z => $x) {
                        echo " -- ".$z." : ". $x ."<br>";
                    }
                    echo "<br>";
                }
                echo "<br>";
            }
            // echo json_encode($response);
            // $response = [];
            // foreach ($data as $k => $v) {
            //     $response['table'][$k] = $v;
            //     $response['table'][$k][$v] = $this->db->field_data($v);
            //     $yaml_template = $response['table'][$k][$v];
            //
            //     $yaml = fopen($v.".yml", "w+") or die($k.'. Unable to write the file: '.$v.'.yml <br>');
            //     fwrite($yaml, $yaml_template);
            //     fclose($yaml);
            // }
            // echo json_encode($yaml_template);
        }//makeit


    }
