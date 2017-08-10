<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Install extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

    public function __construct(){
        parent::__construct();
        $this->load->model('Install_model');
        $this->load->helper('url');
        $this->load->helper('file');
        $this->load->library('parser');
    }

    public function index(){
        $data = $this->Install_model->makeit();
        // Remove controller/model
        @unlink(APPPATH . 'models/Install_model.php');
        @unlink(APPPATH . 'controllers/Install.php');
        // Confirmation
        redirect(base_url().'index.php/welcome/completed', 'location');
    }
}
