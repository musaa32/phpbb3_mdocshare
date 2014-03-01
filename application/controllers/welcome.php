<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	var $forum_link;
	function __construct()
	{
		parent::__construct();
		
		$this->load->library('Phpbb_library');

		
		$this->config->load('ci_phpbb_config');
		$this->forum_link = $this->config->item('forum_link');
		if ($this->phpbb_library->isLoggedIn() === TRUE)
        {
			ci_redirect('uploads');
        }else{
			ci_redirect($this->forum_link.'ucp.php?mode=login');
		}
	}
	
	
	
	
	
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */