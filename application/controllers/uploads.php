<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Uploads extends CI_Controller {


	var $forum_link;

	function __construct()
	{
		parent::__construct();
		// load phpbb library
		$this->load->library('Phpbb_library');
		// load config file
		$this->config->load('ci_phpbb_config');
		$this->forum_link = $this->config->item('forum_link');
		if (!($this->phpbb_library->isLoggedIn() === TRUE))
			ci_redirect($this->forum_link.'ucp.php?mode=login');
	
	}
	
	public function index($forum_id = 0)
	{
		$userId = $this->phpbb_library->getUserInfo('user_id');
		$username = $this->phpbb_library->getUserInfo('username');
		$email = $this->phpbb_library->getUserInfo('user_email');
		
		
		$data['config_dateformat'] = $this->phpbb_library->getConfigByName('default_dateformat')['config_value'];
		$data['page_is_admin'] = $this->phpbb_library->isAdministrator() === TRUE;        
		$data['page_username'] = $username;
		$data['forum_id'] = $forum_id;
		$data['page_context'] = $this->phpbb_library->getForums();
		$data['page_attachments'] = $this->phpbb_library->getAttachments($forum_id);
		$data['forum_name'] = $this->phpbb_library->getForumNameById($forum_id);
		$data['page_new_attachments'] = $this->phpbb_library->getNewAttachments();
		
		$data['site_title'] = $this->phpbb_library->getConfigByName('sitename')['config_value'];
		$data['header_name'] = $this->phpbb_library->getConfigByName('sitename')['config_value'];
		
		if($forum_id == 0)
		{
			$this->load->view('includes/header',$data);
			$this->load->view('includes/menue_top',$data);
			$this->load->view('uploads_index_start',$data);
			$this->load->view('includes/footer',$data);
		}else{
		
		
			/* PAGINATION */
			$this->load->library('pagination');
			$config = array();
			$config['base_url'] = site_url('uploads/'.$forum_id);
			$config['total_rows'] = count($data['page_attachments']);
			$config['per_page'] =  $this->config->item('items_per_page') == '' ? $this->phpbb_library->getConfigByName('posts_per_page') : $this->config->item('items_per_page');
			$this->pagination->initialize($config);
			
			$data['page_files'] = $this->phpbb_library->getAttachmentsOffset($forum_id,$config['per_page'],$this->uri->segment(3));
			
							
		
				
			$this->load->view('includes/header',$data);
			$this->load->view('includes/menue_top',$data);
			$this->load->view('uploads_index',$data);
			$this->load->view('includes/footer',$data);
				
		
			
		}
	}
	
	public function myuploads()
	{
	
	
		$userId = $this->phpbb_library->getUserInfo('user_id');
		$username = $this->phpbb_library->getUserInfo('username');
		$email = $this->phpbb_library->getUserInfo('user_email');
		
		$data['page_is_admin'] = $this->phpbb_library->isAdministrator() === TRUE;        
		$data['page_username'] = $username;
		$data['forum_id'] = $forum_id;
		$data['page_context'] = $this->phpbb_library->getForums();
		$data['page_attachments'] = $this->phpbb_library->getAttachments($forum_id);
		$data['forum_name'] = $this->phpbb_library->getForumNameById($forum_id);
		$data['page_attachments'] = $this->phpbb_library->getAttachmentsByUserId($userId);
		
		$data['site_title'] = $this->phpbb_library->getConfigByName('sitename')['config_value'];
		$data['header_name'] = $this->phpbb_library->getConfigByName('sitename')['config_value'];
		
		/* PAGINATION */
		$this->load->library('pagination');
		$config = array();
		$config['base_url'] = site_url('uploads/myuploads/');
		$config['total_rows'] = count($data['page_attachments']);
		$config['per_page'] =  $this->config->item('items_per_page') == '' ? $this->phpbb_library->getConfigByName('posts_per_page') : $this->config->item('items_per_page');

		
		$this->pagination->initialize($config);
		$data['page_files'] = $this->phpbb_library->getAttachmentsByUserIdOffset($userId, $config['per_page'],$this->uri->segment(3));
			
							
		
				
		$this->load->view('includes/header',$data);
		$this->load->view('includes/menue_top',$data);
		$this->load->view('uploads_myuploads',$data);
		$this->load->view('includes/footer',$data);
		
	}
	
	public function search()
	{
		$this->load->library('user_agent');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('text', 'Suchtext', 'required|min_length[3]|max_length[50]|xss_clean');
		$this->form_validation->set_rules('submit', 'Button', 'required');
	

		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('fehlertext', 'Es ist ein <strong>Fehler</strong> aufgetreten. Der Suchtext muss mindestens 3 Zeichen enthalten.');
			
			if ($this->agent->is_referral())
			{
				ci_redirect($this->agent->referrer());
			}else{
				ci_redirect('uploads');
			}
			
		}
		else
		{

			$userId = $this->phpbb_library->getUserInfo('user_id');
			$username = $this->phpbb_library->getUserInfo('username');
			$email = $this->phpbb_library->getUserInfo('user_email');
			
			$data['page_is_admin'] = $this->phpbb_library->isAdministrator() === TRUE;        
			$data['page_username'] = $username;
		
			$data['page_context'] = $this->phpbb_library->getForums();
		
		
			
			$data['site_title'] = $this->phpbb_library->getConfigByName('sitename')['config_value'];
			$data['header_name'] = $this->phpbb_library->getConfigByName('sitename')['config_value'];
			
		


			$data['page_files'] = $this->phpbb_library->searchAttachmentsByName($this->input->post('text'));
				
								
			
					
			$this->load->view('includes/header',$data);
			$this->load->view('includes/menue_top',$data);
			$this->load->view('uploads_search',$data);
			$this->load->view('includes/footer',$data);

		}
		
	}
	
	
	
}

/* End of file uploads.php */
/* Location: ./application/controllers/uploads.php */