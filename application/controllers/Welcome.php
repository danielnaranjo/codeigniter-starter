<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
         $this->load->helper('url');
         $this->load->helper('file');
         $this->load->library('parser');
         $this->load->model('Test_model');
     }
	public function index()
	{
        $data['msg']='<div id="body">
            <p>The page you are looking at is being generated dynamically by CodeIgniter.</p>
            <p>If you would like to create all starter kit with CRUD and API, this page you\'ll find it located at:</p>
            <a href="'.base_url().'index.php/install">Make Controllers and Models from database</a>
            <p>All script will generated with table name (ex. user or news) and installations script will be removed.</p>
            <p>If you are exploring CodeIgniter for the very first time, you should start by reading the <a href="user_guide/">User Guide</a>.</p>
        </div>';
        $this->load->view('welcome_message', $data);
	}
    public function completed(){
        // Confirmation
        $data['msg']='<div id="body">
            <p>Task completed</p>
            <ul>
            <li>Install is removed (controllers, models, views)</li>
            <li>Welcome is removed (controllers, models, views)</li>
            </ul>
            <p>All script will generated with table name (ex. user or news) and installations script will be removed.</p>
            <p>If you are exploring CodeIgniter for the very first time, you should start by reading the <a href="user_guide/">User Guide</a>.</p>
        </div>';
        $this->load->view('welcome_message', $data);
    }
}
