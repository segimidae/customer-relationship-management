<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Files extends CI_Controller {

    public function access_map(){
        return array(
            'index'=>'view',
            'update'=>'edit'
        );
    }

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('grocery_CRUD');
    }

    public function index()
    {

        // Component
        $this->output->enable_profiler(TRUE); //Profiler Debug
        $this->load->model('Midae_model');
        $data['user_meta'] = $this->Midae_model->get_user_meta();
        $data['top_title'] = ucwords(strtolower($this->uri->segment('1'))); //URI title.
        $data['top_desc'] = "Change your page purpose here"; //function purpose here.
        //End of component

        $crud = new grocery_CRUD();
        $state = $crud->getState();
        $crud->set_theme('datatables');
        $crud->set_table('files');
        $crud->set_subject('Files');
        //$crud->set_relation('country_id','country','country_nicename');
        $crud->display_as('file_name','File name')
             ->display_as('file_content', 'File')
             ->display_as('last_update','Date Changes');
        $crud->set_field_upload('file_content','assets/uploads/files');
       // $crud->unset_texteditor('lead_address','full_text');
        $crud->unset_print();

       



        if($state == "add" | $state == "edit"){
        $crud->fields('file_name','file_content');
        $crud->callback_before_insert(array($this,'_last_update'));
        $output = $crud->render();
        $output = array_merge($data,(array)$output);
        $this->load->view('cruds.php',$output);
        }elseif ($state == "read") {
        $crud->fields('file_name','file_content','last_update');
        $crud->callback_before_insert(array($this,'_last_update'));
        $output = $crud->render();
        $output = array_merge($data,(array)$output);
        $this->load->view('cruds.php',$output);
        }
        else{
        $crud->columns('file_name','file_content');
        $output = $crud->render();
        $output = array_merge($data,(array)$output);
        $this->load->view('cruds.php',$output);
        }

    }

}

/* End of file dashboard.php */
/* Location: ./application/controllers/leads.php */