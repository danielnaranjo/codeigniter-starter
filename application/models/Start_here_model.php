<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Start_here_model extends CI_Model {

        public function __construct(){
            $this->load->database();
            $this->load->helper('file');
        }

        public function hacer(){
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

                //https://tutorials.kode-blog.com/codeigniter-model
                $my_model = fopen($path_model, "w+") or die($k.'. Unable to write the file: '. $name_model.'.php <br>');
                $model_template = "<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

                    class $name_model extends CI_Model {

                        public function __construct() {
                            \$this->load->database();
                        }
                        public function listar(\$id = FALSE){
                            if (\$id === FALSE) {
                                    \$query = \$this->db->get('".$v."');
                                    return \$query->result_array();
                            }
                            \$query = \$this->db->get_where('".$v."', array('".$v."_id' => \$id));
                            return \$query->row_array();
                        }
                        public function columnas(){
                            \$query = \$this->db->field_data('".$v."');
                            return \$query;
                        }
                        public function lista(\$id){
                            \$query = \$this->db->get_where('".$v."', array('".$v."_id' => \$id));
                            return \$query->result_array();
                        }
                        public function registrar(\$data){
                            \$query = \$this->db->insert('".$v."', \$data);
                            return \$data;
                        }
                        public function updatear(\$id, \$data){
                            \$this->db->where('".$v."_id', \$id);
                            \$this->db->update('".$v."', \$data);
                        }
                        public function deletear(\$id){
                            \$this->db->where('".$v."_id', \$id);
                            \$this->db->delete('".$v."');
                        }

                    }
                ";
                fwrite($my_model, $model_template);
                fclose($my_model);

                $my_controller = fopen($path_controller, "w+") or die($k.'. Unable to write the file: '. $name_controller.'.php <br>');
                $controller_template = "<?php
                    class ".$name_controller." extends CI_Controller {

                    public function __construct() {
                        parent::__construct();
                        \$this->load->model('".$name_model."');
                    }
                    public function index(\$id){
                        \$data = \$this->".$name_model."->listar(\$id);//AQUI
                        echo json_encode(\$data);
                    }
                    public function add(){
                        //TODO LIST
                        \$data = array(
                            'Audio_id' => \$this->input->post(\"".$v."_id\"),
                        );
                        \$data = \$this->".$name_model."->registrar(\$data);
                        if(\$data){
                            echo json_encode(\$data);
                        }
                    }
                    public function delete(\$id){
                        \$data = \$this->".$name_model."->deletear(\$id);
                        echo json_encode(\$data);
                    }
                    public function update(){
                        //TODO LIST
                        \$id = \$this->input->post(\"".$v."_id\");
                        \$data = array(
                            'note' => \$this->input->post(\"note\"),
                        );
                        \$res = \$this->".$name_model."->updatear(\$id, \$data);
                        echo json_encode(\$res);
                    }
                ";
                fwrite($my_controller, $controller_template);
                fclose($my_controller);

                //if ( ! write_file($path, $k[$v] )) {
                //    echo $k.'. Unable to write the file: '. ucfirst ($v).'_model.php <br>';
                //} else {
                //    $response['table'][$k][$v] = $this->db->field_data($v);
                //}
            }
            return $response;
        }


    }
