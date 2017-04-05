<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GSK_Controller extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        // $this->output->enable_profiler(TRUE);
        if ( in_array($this->session->user_id, $this->config->item('priv') ) && $this->uri->segment(2) != 'signin' )
        {
        	redirect('/main/signin');
        }
    }
}
