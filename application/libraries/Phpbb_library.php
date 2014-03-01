<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
* CodeIgniter phpBB3 Library
*
* CodeIgniter phpBB3 bridge (access phpBB3 user sessions and other functions inside your CodeIgniter applications).
*
* @author Tomaž Muraus
* modified by Musa A. Armagan
* @version    1.2
* @link http://www.tomaz-muraus.info
*/
class Phpbb_library
{
    public $CI;
    protected $_user;

    /**
     * Constructor.
     */
    public function __construct()
    {
        if (!isset($this->CI))
        {
            $this->CI =& get_instance();
        }

        // Set the variables scope
        global $phpbb_root_path, $phpEx, $user, $auth, $cache, $db, $config, $template, $table_prefix;

        define('IN_PHPBB', TRUE);
        define('FORUM_ROOT_PATH', '../phpBB3/');
	
        $phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : FORUM_ROOT_PATH;
        $phpEx = substr(strrchr(__FILE__, '.'), 1);

        // Include needed files
        include($phpbb_root_path . 'common.' . $phpEx);
        include($phpbb_root_path . 'config.' . $phpEx);
        include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
        include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
        include($phpbb_root_path . 'includes/functions_privmsgs.' . $phpEx);
        include($phpbb_root_path . 'includes/functions_posting.' . $phpEx);

        // Initialize phpBB user session
        $user->session_begin();
        $auth->acl($user->data);
        $user->setup();

        // Save user data into $_user variable
        $this->_user = $user;
    }

    /**
     * Returns information from the user data array.
     *
     * @param string $key Item key.
     *
     * @return string/boolean User information on success, FALSE otherwise.
     */
    public function getUserInfo($key)
    {
        if (array_key_exists($key, $this->_user->data))
        {
            return $this->_user->data[$key];
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Returns user status.
     *
     * @return boolean TRUE is user is logged in, FALSE otherwise.
     */
    public function isLoggedIn()
    {
        return $this->_user->data['is_registered'];
    }

    /**
     * Checks if the currently logged-in user is an administrator.
     *
     * @return boolean TRUE if the currently logged-in user is an administrator, FALSE otherwise.
     */
    public function isAdministrator()
    {
        return $this->isGroupMember('administrators');
    }

    /**
     * Checks if the currently logged-in user is a moderator.
     *
     * @return boolean TRUE if the currently logged-in user is a moderator, FALSE otherwise.
     */
    public function isModerator()
    {
        return  $this->isGroupMember('moderators');
    }

    /**
     * Checks if a user is a member of the given user group.
     *
     * @param string $group Group name in lowercase.
     *
     * @return boolean TRUE if user is a group member, FALSE otherwise.
     */
    public function isGroupMember($group)
    {
        $groups = array_map(strtolower, $this->getUserGroupMembership());

        if (in_array($group, $groups))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Returns information for a given user.
     *
     * @param int $userId User ID.
     *
     * @return array/boolean Array with user information on success, FALSE otherwise.
     */
    public function getUserById($userId)
    {
        global $table_prefix;

        $this->CI->db->select('*');
        $this->CI->db->from($table_prefix . 'users');
        $this->CI->db->where('user_id', $userId);
        $this->CI->db->limit(1);

        $query = $this->CI->db->get();

        if ($query->num_rows() == 1)
        {
            return $query->row_array();
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Returns information for a given user.
     *
     * @param string $username User name.
     *
     * @return array/boolean Array with user information on success, FALSE otherwise.
     */
    public function getUserByName($username)
    {
        global $table_prefix;

        $this->CI->db->select('*');
        $this->CI->db->from($table_prefix . 'users');
        $this->CI->db->where('username', $username);
        $this->CI->db->limit(1);

        $query = $this->CI->db->get();

        if ($query->num_rows() == 1)
        {
            return $query->row_array();
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Returns all user groups.
     *
     * @return array User groups.
     */
    public function getUserGroupMembership()
    {
        global $table_prefix;

        $userId = $this->_user->data['user_id'];

        $this->CI->db->select('g.group_name');
        $this->CI->db->from($table_prefix . 'groups g');
        $this->CI->db->from($table_prefix . 'user_group u');
        $this->CI->db->where('u.user_id', $userId);
        $this->CI->db->where('u.group_id', 'g.group_id', FALSE);

        $query = $this->CI->db->get();

        foreach ($query->result_array() as $group)
        {
            $groups[] = $group['group_name'];
        }

        return $groups;
    }

	
	/**
     * Returns all forums .
     *
     * @return array forums.
     */
    public function getForums()
    {
        global $table_prefix;

      

        $this->CI->db->select('f.forum_name, f.forum_id');
        $this->CI->db->from($table_prefix . 'forums f');
        $this->CI->db->where('f.forum_type', '1');
		$this->CI->db->where('EXISTS( SELECT 1 FROM '.$table_prefix.'attachments a, '.$table_prefix . 'topics t WHERE t.forum_id = f.forum_id and a.topic_id  = t.topic_id  )  ','',FALSE);
        $query = $this->CI->db->get();

        foreach ($query->result_array() as $forum)
        {
            $forums[] = $forum;
        }

        return $forums;
    }
	
	public function getForumNameById($forum_id)
    {
        global $table_prefix;

        $this->CI->db->select('*');
        $this->CI->db->from($table_prefix . 'forums');
        $this->CI->db->where('forum_id', $forum_id);
        $this->CI->db->limit(1);

        $query = $this->CI->db->get();

        if ($query->num_rows() == 1)
        {
            return $query->row_array();
        }
        else
        {
            return FALSE;
        }
    }
	
	public function getAttachments($forum_id)
    {
        global $table_prefix;

      

        $this->CI->db->select('*');
        $this->CI->db->from($table_prefix . 'attachments a');
		$this->CI->db->from($table_prefix . 'topics t');
        $this->CI->db->where('t.forum_id', $forum_id);
		$this->CI->db->where('a.topic_id', 't.topic_id', FALSE);

        $query = $this->CI->db->get();

        foreach ($query->result_array() as $row)
        {
            $attachments[] = $row;
        }

        return $attachments;
    }
	
	public function getAttachmentsOffset($forum_id, $num = NULL, $offset = NULL)
	{
		 global $table_prefix;

      

        $this->CI->db->select('*');
        $this->CI->db->from($table_prefix . 'attachments a');
		$this->CI->db->from($table_prefix . 'topics t');
		$this->CI->db->from($table_prefix . 'users u');
        $this->CI->db->where('t.forum_id', $forum_id);
		$this->CI->db->where('a.topic_id', 't.topic_id', FALSE);
		$this->CI->db->where('a.poster_id', 'u.user_id', FALSE);
		if ( ! is_null($num))
		{
			$this->CI->db->limit($num, $offset);
		}
		
        $query = $this->CI->db->get();

        foreach ($query->result_array() as $row)
        {
            $attachments[] = $row;
        }

        return $attachments;
	}
	
	public function getNewAttachments()
    {
        global $table_prefix;

      

       $this->CI->db->select('*');
        $this->CI->db->from($table_prefix . 'attachments a');
		$this->CI->db->from($table_prefix . 'topics t');
		$this->CI->db->from($table_prefix . 'users u');
		$this->CI->db->where('a.topic_id', 't.topic_id', FALSE);
		$this->CI->db->where('a.poster_id', 'u.user_id', FALSE);
		$this->CI->db->order_by('a.filetime', 'DESC');
		$this->CI->db->limit(10);
		
        $query = $this->CI->db->get();

        foreach ($query->result_array() as $row)
        {
            $attachments[] = $row;
        }

        return $attachments;
    }
	
	
	public function getAttachmentsByUserId($user_id, $num = NULL, $offset = NULL)
    {
        global $table_prefix;

      

         $this->CI->db->select('*');
        $this->CI->db->from($table_prefix . 'attachments a');
		$this->CI->db->from($table_prefix . 'topics t');
		$this->CI->db->from($table_prefix . 'users u');
        $this->CI->db->where('a.poster_id', $user_id);
		$this->CI->db->where('a.topic_id', 't.topic_id', FALSE);
		$this->CI->db->where('a.poster_id', 'u.user_id', FALSE);
        $query = $this->CI->db->get();

        foreach ($query->result_array() as $row)
        {
            $attachments[] = $row;
        }

        return $attachments;
    }
	
	public function getAttachmentsByUserIdOffset($user_id)
    {
        global $table_prefix;

      

        $this->CI->db->select('*');
        $this->CI->db->from($table_prefix . 'attachments a');
		$this->CI->db->from($table_prefix . 'topics t');
		$this->CI->db->from($table_prefix . 'users u');
        $this->CI->db->where('a.poster_id', $user_id);
		$this->CI->db->where('a.topic_id', 't.topic_id', FALSE);
		$this->CI->db->where('a.poster_id', 'u.user_id', FALSE);
		if ( ! is_null($num))
		{
			$this->CI->db->limit($num, $offset);
		}
        $query = $this->CI->db->get();

        foreach ($query->result_array() as $row)
        {
            $attachments[] = $row;
        }

        return $attachments;
    }
	
	public function getConfigByName($config_name)
    {
        global $table_prefix;

      

        $this->CI->db->select('*');
        $this->CI->db->from($table_prefix . 'config c');
        $this->CI->db->where('c.config_name', $config_name);
		$this->CI->db->limit(1);

        $query = $this->CI->db->get();

        if ($query->num_rows() == 1)
        {
            return $query->row_array();
        }
        else
        {
            return FALSE;
        }
    }
	
	public function searchAttachmentsByName($name)
    {
        global $table_prefix;

      

		$this->CI->db->select('*');
        $this->CI->db->from($table_prefix . 'attachments a');
		$this->CI->db->from($table_prefix . 'topics t');
		$this->CI->db->from($table_prefix . 'users u');
		$this->CI->db->where('a.topic_id', 't.topic_id', FALSE);
		$this->CI->db->where('a.poster_id', 'u.user_id', FALSE);
		$this->CI->db->like('a.real_filename', $name);
		$this->CI->db->order_by('a.filetime', 'DESC');
		
		
        $query = $this->CI->db->get();

        foreach ($query->result_array() as $row)
        {
            $attachments[] = $row;
        }

        return $attachments;
    }
	
	
}

/* End of file phpbb_library.php */
/* Location: ./application/libraries/phpbb_library.php */