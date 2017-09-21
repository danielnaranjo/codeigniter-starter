<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Install_model extends CI_Model {

        public function __construct(){
            $this->load->database();
            $this->load->helper('file');
        }

        public function setup(){
            $data = [];
            $tables = $this->db->list_tables();
            foreach ($tables as $key=>$val){
                $data[$key] = $val;
            }
            $response = [];
            foreach ($data as $k => $v) {

                $response['table'][$k][$v] = $this->db->field_data($v);

                $name_model = ucfirst ($v).'_model';
                $path_model=APPPATH . 'models/'.$name_model.'.php';

                $name_controller = ucfirst ($v);
                $path_controller=APPPATH . 'controllers/'.$name_controller.'.php';

                $path_routes=APPPATH . 'controllers/api/';
                $yaml_routes=APPPATH . '/';

                if(!file_exists($path_model)){
                    //https://tutorials.kode-blog.com/codeigniter-model
                    $my_model = fopen($path_model, "w+") or die($k.'. Unable to write the file: '. $name_model.'.php <br>');
                    $model_template = "<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
                    /*
                        Generated on ".date('d/m/Y H:i:s')."
                        Author by Daniel Naranjo
                        www.loultimoenlaweb.com

                        ".json_encode($response['table'][$k][$v], JSON_PRETTY_PRINT)."
                    */
                        class $name_model extends CI_Model {
                            public function __construct() {
                                \$this->load->database();
                            }
                            public function getAll(\$".$v."_id = FALSE){
                                if (\$".$v."_id === FALSE) {
                                    //\$this->db->cache_on(); // Create a cache
                                    \$this->db->select('*');
                                    \$this->db->from('".$v."');
                                    //\$this->db->order_by('\$".$v."_id', 'DESC'); //ASC, DESC, RANDOM
                                    //\$this->db->limit(100);// Default 100
                                    \$query = \$this->db->get();
                                    return \$query->result_array();
                                }
                                //\$this->db->cache_off(); // Prevent a cache
                                \$query = \$this->db->get_where('".$v."', array('".$v."_id' => \$".$v."_id));
                                return \$query->row_array();
                            }
                            public function tableFields(){
                                \$query = \$this->db->field_data('".$v."');
                                return \$query;
                            }
                            public function getOne(\$".$v."_id){
                                \$query = \$this->db->get_where('".$v."', array('".$v."_id' => \$".$v."_id));
                                return \$query->result_array();
                            }
                            public function insertInto(\$data){
                                unset(\$data['Submit']); // <- Remove garbage POST array!
                                //\$query = \$this->db->insert('".$v."', \$data);
                                // Values are automatically escaped, producing safer queries.
                                \$query = \$this->db->insert_string('".$v."', \$data);
                                return \$this->db->insert_id();
                            }
                            public function updateData(\$".$v."_id, \$data){
                                //\$this->db->where('".$v."_id', \$".$v."_id);
                                //\$this->db->update('".$v."', \$data);
                                // Values are automatically escaped, producing safer queries.
                                \$data = array('name' => $name, 'email' => $email, 'url' => $url);
                                \$where = '".$v."_id=\$".$v."_id';
                                \$str = \$this->db->update_string('table_name', \$data, \$where);
                                retun \$this->db->affected_rows();
                            }
                            public function removeData(\$".$v."_id){
                                \$this->db->where('".$v."_id', \$".$v."_id);
                                \$this->db->delete('".$v."');
                            }
                            public function joinData(\$".$v."_id){
                                \$this->db->select('*');
                                \$this->db->from('".$v."');
                                \$this->db->join('".$v."','','left');
                                \$this->db->where('".$v."_id', \$".$v."_id);
                                \$query = \$this->db->get();
                                return \$query->row_array();
                            }
                        }//end
                    ";
                    fwrite($my_model, $model_template);
                    fclose($my_model);
                }

                if(!file_exists($path_controller)){
                    $my_controller = fopen($path_controller, "w+") or die($k.'. Unable to write the file: '. $name_controller.'.php <br>');
                    $controller_template = "<?php
                    /*
                        Generated on ".date('d/m/Y H:i:s')."
                        Author by Daniel Naranjo
                        www.loultimoenlaweb.com
                    */
                    class ".$name_controller." extends CI_Controller {
                        public function __construct() {
                            parent::__construct();
                            \$this->load->model('".$name_model."');
                        }
                        public function index(\$".$v."_id=FALSE){
                            \$data = \$this->".$name_model."->getAll(\$".$v."_id);
                            echo json_encode(\$data);
                        }
                        public function view(\$".$v."_id){
                            \$data = \$this->".$name_model."->getOne(\$".$v."_id);
                            echo json_encode(\$data);
                        }
                        public function add(){
                            \$this->form_validation->set_rules('field', 'label', 'required');// Check rules
                            if (\$this->form_validation->run() == TRUE){
                                \$data = \$this->".$name_model."->insertInto(\$this->input->post(NULL, TRUE));
                                if(\$data){
                                    echo json_encode(\$data);
                                }
                            } else {
                                \$_SESSION['message'] = \$this->form_validation->error_array();
                                \$this->session->mark_as_flash('message');
                                redirect('".$v."/index', 'location');
                            }
                        }
                        public function delete(\$".$v."_id){
                            \$data = \$this->".$name_model."->removeData(\$".$v."_id);
                            echo json_encode(\$data);
                        }
                        public function update(){
                            \$".$v."_id = \$this->input->post(\"".$v."_id\");
                            \$data = \$this->input->post(NULL, TRUE);
                            \$res = \$this->".$name_model."->updateData(\$".$v."_id, \$data);
                            echo json_encode(\$res);
                        }
                        public function upload(\$".$v."_id = NULL) {
                            \$config['upload_path'] = './folder/';
                            \$config['allowed_types'] = 'gif|jpg|png|pdf';
                            \$config['max_size'] = 1000;
                            \$config['max_width'] = 2048;
                            \$config['max_height'] = 2048;
                            \$this->load->library('upload', \$config);
                            if (!\$this->upload->do_upload('file')) {
                                \$error = array('error' => \$this->upload->display_errors());
                                echo json_encode(\$error);
                            } else  {
                                \$data = \$this->".$name_model."->registrar(\$this->input->post(NULL, TRUE));
                                if(\$data){
                                    echo json_encode(\$data);
                                }
                            }
                        }
                    }//end
                    ";
                    fwrite($my_controller, $controller_template);
                    fclose($my_controller);
                }

                if(!file_exists($path_controller)){
                    $my_api = fopen($path_routes.$v.".php", "w+") or die($k.'. Unable to write the file: '. $path_routes.$v.'.php <br>');
                    $api_template = "<?php
                    /*
                        Generated on ".date('d/m/Y H:i:s')."
                        Author by Daniel Naranjo
                        www.loultimoenlaweb.com
                    */
                    class ".$name_controller." extends CI_Controller {
                        public function __construct() {
                            parent::__construct();
                            \$this->load->model('".$name_model."');
                        }
                        public function index(\$".$v."_id=FALSE){
                            \$data = \$this->".$name_model."->getAll(\$".$v."_id);
                            echo json_encode(\$data);
                        }
                        public function view(\$".$v."_id){
                            \$data = \$this->".$name_model."->getOne(\$".$v."_id);
                            echo json_encode(\$data);
                        }
                        public function add(){
                            \$this->form_validation->set_rules('field', 'label', 'required');// Check rules
                            if (\$this->form_validation->run() == TRUE){
                                \$data = \$this->".$name_model."->insertInto(\$this->input->post(NULL, TRUE));
                                if(\$data){
                                    echo json_encode(\$data);
                                }
                            } else {
                                \$_SESSION['message'] = \$this->form_validation->error_array();
                                \$this->session->mark_as_flash('message');
                                redirect('".$v."/index', 'location');
                            }
                        }
                        public function delete(\$".$v."_id){
                            \$data = \$this->".$name_model."->removeData(\$".$v."_id);
                            echo json_encode(\$data);
                        }
                        public function update(){
                            \$".$v."_id = \$this->post(\"".$v."_id\");
                            \$data = \$this->post(NULL, TRUE);
                            \$res = \$this->".$name_model."->updateData(\$".$v."_id, \$data);
                            echo json_encode(\$res);
                        }
                    }//end
                    ";
                    fwrite($my_api, $api_template);
                    fclose($my_api);
                }
                // $yaml = fopen($yaml_routes.$v.".yml", "w+") or die($k.'. Unable to write the file: '. $yaml_routes.$v.'.yml <br>');
                // $yaml_template = $response['table'][$k][$v];
                // fwrite($yaml, json_encode($yaml_template, JSON_PRETTY_PRINT));
                // fclose($yaml);
            }
            return $response;
        }//makeit


    }
