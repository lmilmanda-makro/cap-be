<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * This file is part of Auth_Ldap.

    Auth_Ldap is free software: you can redistribute it and/or modify
    it under the terms of the GNU Lesser General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Auth_Ldap is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Auth_Ldap.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */
/**
 * Auth_Ldap Class
 *
 * Simple LDAP Authentication library for Code Igniter.
 *
 * @package         Auth_Ldap
 * @author          Greg Wojtak <gwojtak@techrockdo.com>
 * @version         0.5
 * @link            http://www.techrockdo.com/projects/auth_ldap
 * @license         GNU Lesser General Public License (LGPL)
 * @copyright       Copyright © 2010,2011 by Greg Wojtak <gwojtak@techrockdo.com>
 * @todo            Allow for privileges in groups of groups in AD
 * @todo            Rework roles system a little bit to a "auth level" paradigm
 */
class Auth_Ldap {
    function __construct() {
        $this->ci =& get_instance();

        log_message('debug', 'Auth_Ldap initialization commencing...');

        // Load the session library
        $this->ci->load->library('session');

        // Load the configuration
        $this->ci->load->config('auth_ldap');

        // Load the language file
        // $this->ci->lang->load('auth_ldap');

        $this->_init();
    }

    
    /**
     * @access private
     * @return void
     */
    private function _init() {

        // Verify that the LDAP extension has been loaded/built-in
        // No sense continuing if we can't
        if (! function_exists('ldap_connect')) {
            show_error('LDAP functionality not present.  Either load the module ldap php module or use a php with ldap support compiled in.');
            log_message('error', 'LDAP functionality not present in php.');
        }

        $this->hosts = $this->ci->config->item('hosts');
        $this->ports = $this->ci->config->item('ports');
        $this->basedn = $this->ci->config->item('basedn');
		$this->groupdn = $this->ci->config->item('groupdn');
        $this->account_ou = $this->ci->config->item('account_ou');
        $this->ad_domain = $this->ci->config->item('ad_domain');
        $this->proxy_user = $this->ci->config->item('proxy_user');
        $this->proxy_pass = $this->ci->config->item('proxy_pass');
        $this->auditlog = $this->ci->config->item('auditlog');

    }

    /**
     * @access public
     * @param string $username
     * @param string $password
     * @return bool 
     */
    function login($username, $password, $usingtoken = false, $token = '') {
        /*
         * For now just pass this along to _authenticate.  We could do
         * something else here before hand in the future.
         */
		 if($usingtoken){
		 $user_info = $this->_authenticate_token($token);
		 } else {
		 $user_info = $this->_authenticate($username,$password);
		 }
        

		if(!$user_info) {
			log_message('error', "Cuenta o clave inválida.");
			return FALSE;
        } else if(empty($user_info['role'])) {
            log_message('info', $username." has no role to play.");
            show_error($username.' succssfully authenticated, but is not allowed because the username was not found in an allowed access group.');
			return FALSE;
        }
        // Record the login
        $this->_audit("Successful login: ".$user_info['cn']."(".$username.") from ".$this->ci->input->ip_address());


		$cn = $username;
		$role = '';

		if(isset($user_info['id'])) {
			$cn = $user_info['id'];
		} 
		$cn = strtolower(utf8_decode($cn));

		if(isset($user_info['role'])) {
			$role = $user_info['role'];
			$out = array();
			if (is_array($role)) { 
				foreach ($role as $key => $value) { 
					$out[utf8_encode($key)] = utf8_encode($value); 
				} 
				$role = $out;
			} elseif(is_string($role)) { 
				if(mb_detect_encoding($role) != "UTF-8") {
					$role = utf8_encode($role); 
				} else {
					//$role = $role;
				}
			} else { 
				$role = array();
			}
		} 

        // Set the session data
        $customdata = array('username' => $cn,
                            'cn' => $cn,
                            'role' => $role,
                            'logged_in' => TRUE);
			
        $this->ci->session->set_userdata($customdata);
        return TRUE;
    }

    /**
     * @access public
     * @return bool
     */
    function is_authenticated() {
        if($this->ci->session->userdata('logged_in')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * @access public
     */
    function logout() {
        // Just set logged_in to FALSE and then destroy everything for good measure
        $this->ci->session->set_userdata(array('logged_in' => FALSE));
        $this->ci->session->sess_destroy();
    }

    /**
     * @access private
     * @param string $msg
     * @return bool
     */
    private function _audit($msg){
        $date = date('Y/m/d H:i:s');
        if( ! file_put_contents($this->auditlog, $date.": ".$msg."\n",FILE_APPEND)) {
            log_message('info', 'Error opening audit log '.$this->auditlog);
            return FALSE;
        }
        return TRUE;
    }
	
    /**
     * @access private
     * @param string $token
     * @return array 
     */
	private function _authenticate_token($token) {
	
		$sql = 'SELECT * FROM RCK_USERS_REGISTRY WHERE TOKEN like \''.$token.'\' ';
		$result = $this->ci->db->query($sql);

		$username = '';
		if(!$result) {
			return FALSE;
		} else {
			$items = $result->result_array();
			if(sizeof($items) == 1){
				$username = $items[0]['USERNAME'];
			} else {
				return FALSE;
			}
		}
		
		foreach($this->hosts as $host) {
            $this->ldapconn = ldap_connect($host);
            if($this->ldapconn) {
               break;
            }else {
                log_message('info', 'Error connecting to '.$uri);
            }
        }
        // At this point, $this->ldapconn should be set.  If not... DOOM!
        if(! $this->ldapconn) {
            log_message('error', "Couldn't connect to any LDAP servers.  Bailing...");
            show_error('Error connecting to your LDAP server(s).  Please check the connection and try again.');
        }

        // We've connected, now we can attempt the login...
		// Find the DN of the user we are binding as
		// If proxy_user and proxy_pass are set, use those, else bind anonymously
		if($this->proxy_user) {
			$bind = ldap_bind($this->ldapconn, $this->proxy_user, $this->proxy_pass);
		}else {
			$bind = ldap_bind($this->ldapconn);
		}

		if(!$bind){
			log_message('error', 'Unable to perform anonymous/proxy bind');
			show_error('Unable to bind for user id lookup');
		}

		log_message('debug', 'Successfully bound to directory.  Performing dn lookup for '.$username);
		//echo $username . '-----'. $this->login_attribute;
	
		$filter = '(sAMAccountName='.$username.')';
		$attributes_ad = array('dn', 'sAMAccountName', 'cn', 'objectClass','member');
		$search = ldap_search($this->ldapconn, $this->basedn, $filter, $attributes_ad);
		
		//var_dump($search);
		$entries = ldap_get_entries($this->ldapconn, $search);
		if(!isset($entries[0]['dn'])){
			return FALSE;
		}
		$binddn = $entries[0]['dn'];
		
		//var_dump($bind); 
		$cn = $entries[0]['cn'][0];
		$dn = stripslashes($entries[0]['dn']);
		$id = $entries[0]['samaccountname'][0];
		$roles = $this->_get_role($cn);
        $get_role_arg = $id;

        return array('cn' => $cn, 'dn' => $dn, 'id' => $id, 'role' => $roles );
	}
	
    /**
     * @access private
     * @param string $username
     * @param string $password
     * @return array 
     */
    private function _authenticate($username, $password) {

		foreach($this->hosts as $host) {
            $this->ldapconn = ldap_connect($host);
            if($this->ldapconn) {
               break;
            }else {
                log_message('info', 'Error connecting to '.$uri);
            }
        }
        // At this point, $this->ldapconn should be set.  If not... DOOM!
        if(! $this->ldapconn) {
            log_message('error', "Couldn't connect to any LDAP servers.  Bailing...");
            show_error('Error connecting to your LDAP server(s).  Please check the connection and try again.');
        }

        // We've connected, now we can attempt the login...
		// Find the DN of the user we are binding as
		// If proxy_user and proxy_pass are set, use those, else bind anonymously
		if($this->proxy_user) {
			$bind = ldap_bind($this->ldapconn, $this->proxy_user, $this->proxy_pass);
		}else {
			$bind = ldap_bind($this->ldapconn);
		}

		if(!$bind){
			log_message('error', 'Unable to perform anonymous/proxy bind');
			show_error('Unable to bind for user id lookup');
		}

		log_message('debug', 'Successfully bound to directory.  Performing dn lookup for '.$username);
		//echo $username . '-----'. $this->login_attribute;
	
		$filter = '(sAMAccountName='.$username.')';
		$attributes_ad = array('dn', 'sAMAccountName', 'cn', 'objectClass','member');
		$search = ldap_search($this->ldapconn, $this->basedn, $filter, $attributes_ad);
		
		//var_dump($search);
		$entries = ldap_get_entries($this->ldapconn, $search);
		if(!isset($entries[0]['dn'])){
			return FALSE;
		}
		$binddn = $entries[0]['dn'];
		
		//var_dump($binddn); 
		// Now actually try to bind as the user

		$bind = @ldap_bind($this->ldapconn, $binddn, $password);
		if(! $bind) {
			$this->_audit("Failed login attempt: ".$username."/".$password." from ".$_SERVER['REMOTE_ADDR']);
			return FALSE;
		} else {
			//$this->_audit("Successful login attempt: ".$username."/". $password." from ".$_SERVER['REMOTE_ADDR']);
		}
		
		//var_dump($bind); 
		$cn = $entries[0]['cn'][0];
		$dn = stripslashes($entries[0]['dn']);
		$id = $entries[0]['samaccountname'][0];
		$roles = $this->_get_role($cn);
        $get_role_arg = $id;

        return array('cn' => $cn, 'dn' => $dn, 'id' => $id, 'role' => $roles );
    }

    /**
     * @access private
     * @param string $str
     * @param bool $for_dn
     * @return string 
     */
    private function ldap_escape($str, $for_dn = false) {
        /**
         * This function courtesy of douglass_davis at earthlink dot net
         * Posted in comments at
         * http://php.net/manual/en/function.ldap-search.php on 2009/04/08
         */
        // see:
        // RFC2254
        // http://msdn.microsoft.com/en-us/library/ms675768(VS.85).aspx
        // http://www-03.ibm.com/systems/i/software/ldap/underdn.html  
        
        if  ($for_dn)
            $metaChars = array(',','=', '+', '<','>',';', '\\', '"', '#');
        else
            $metaChars = array('*', '(', ')', '\\', chr(0));

        $quotedMetaChars = array();
        foreach ($metaChars as $key => $value) $quotedMetaChars[$key] = '\\'.str_pad(dechex(ord($value)), 2, '0');
        $str=str_replace($metaChars,$quotedMetaChars,$str); //replace them
        return ($str);  
    }
    
    /**
     * @access private
     * @param string $username
     * @return int
     */
    private function _get_role($username) {
        return array('admin','user','marketing');
    }
}

?>
