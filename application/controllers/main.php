<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class main extends CI_Controller
{
    public function index()
    {
        $this->load->helper('file');

        $data['user'] = $this->session->all_userdata();
        $data['count_queue'] = count(get_filenames(FCPATH.'/webhook_data'));

        $this->load->view('layout', $data);
    }

    public function login()
    {
        redirect('oauth/begin');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect();
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
