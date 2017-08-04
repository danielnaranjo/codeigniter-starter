<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Install_model extends CI_Model {

        public function __construct(){
            $this->load->database();
            $this->load->helper('file');
        }

        public function makeit(){
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
                        public function listar(\$".$v."_id = FALSE){
                            if (\$".$v."_id === FALSE) {
                                \$query = \$this->db->get('".$v."');
                                return \$query->result_array();
                            }
                            \$query = \$this->db->get_where('".$v."', array('".$v."_id' => \$".$v."_id));
                            return \$query->row_array();
                        }
                        public function columnas(){
                            \$query = \$this->db->field_data('".$v."');
                            return \$query;
                        }
                        public function obtener(\$".$v."id){
                            \$query = \$this->db->get_where('".$v."', array('".$v."_id' => \$".$v."_id));
                            return \$query->result_array();
                        }
                        public function registrar(\$data){
                            unset(\$data['Submit']); // <- Remove garbage POST array!
                            \$query = \$this->db->insert('".$v."', \$data);
                            return \$data;
                        }
                        public function updatear(\$".$v."_id, \$data){
                            \$this->db->where('".$v."_id', \$".$v."_id);
                            \$this->db->update('".$v."', \$data);
                        }
                        public function deletear(\$".$v."_id){
                            \$this->db->where('".$v."_id', \$".$v."_id);
                            \$this->db->delete('".$v."');
                        }
                    }//end
                ";
                fwrite($my_model, $model_template);
                fclose($my_model);

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
                        \$data = \$this->".$name_model."->listar(\$".$v."_id);
                        echo json_encode(\$data);
                    }
                    public function view(\$".$v."_id){
                        \$data = \$this->".$name_model."->obtener(\$".$v."_id);
                        echo json_encode(\$data);
                    }
                    public function add(){
                        \$data = \$this->".$name_model."->registrar(\$this->input->post(NULL, TRUE));
                        if(\$data){
                            echo json_encode(\$data);
                        }
                    }
                    public function delete(\$".$v."_id){
                        \$data = \$this->".$name_model."->deletear(\$".$v."_id);
                        echo json_encode(\$data);
                    }
                    public function update(){
                        \$".$v."_id = \$this->input->post(\"".$v."_id\");
                        \$data = \$this->input->post(NULL, TRUE);
                        \$res = \$this->".$name_model."->updatear(\$".$v."_id, \$data);
                        echo json_encode(\$res);
                    }
                }//end
                ";
                fwrite($my_controller, $controller_template);
                fclose($my_controller);

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
                        \$data = \$this->".$name_model."->listar(\$".$v."_id);
                        echo json_encode(\$data);
                    }
                    public function view(\$".$v."_id){
                        \$data = \$this->".$name_model."->obtener(\$".$v."_id);
                        echo json_encode(\$data);
                    }
                    public function add(){
                        \$data = \$this->".$name_model."->registrar(\$this->input->post(NULL, TRUE));
                        if(\$data){
                            echo json_encode(\$data);
                        }
                    }
                    public function delete(\$".$v."_id){
                        \$data = \$this->".$name_model."->deletear(\$".$v."_id);
                        echo json_encode(\$data);
                    }
                    public function update(){
                        \$".$v."_id = \$this->post(\"".$v."_id\");
                        \$data = \$this->post(NULL, TRUE);
                        \$res = \$this->".$name_model."->updatear(\$".$v."_id, \$data);
                        echo json_encode(\$res);
                    }
                }//end
                ";
                fwrite($my_api, $api_template);
                fclose($my_api);
            }
            return $response;
        }//makeit


    }
