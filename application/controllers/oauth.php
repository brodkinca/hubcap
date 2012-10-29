<?php

/**
 * Hubcap for GitHub Pages
 *
 * PHP Version 5.3
 *
 * @category  Controller
 * @package   Codeigniter
 * @author    Brodkin CyberArts <support@brodkinca.com>
 * @copyright 2012 Brodkin CyberArts.
 * @license   All rights reserved.
 * @version   GIT: $Id$
 * @link      TBD
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use \BCA\CURL\CURL;

/**
 * Hubcap for GitHub Pages
 *
 * PHP Version 5.3
 *
 * @category  Controller
 * @package   Codeigniter
 * @author    Brodkin CyberArts <support@brodkinca.com>
 * @copyright 2012 Brodkin CyberArts.
 * @license   All rights reserved.
 * @link      TBD
 */
class oauth extends CI_Controller
{
    private $_client_id = '8c43d958053cf9c3a608';
    private $_client_secret;
    private $_state;

    public function begin()
    {
        $this->_client_secret = $_SERVER['GITHUB_SECRET'];
        $this->_state = $this->session->userdata('session_id');

        $params['client_id'] = $this->_client_id;
        $params['redirect_uri'] = site_url('oauth/complete');
        $params['scope'] = 'public_repo';
        $params['state'] = $this->_state;

        $url = 'https://github.com/login/oauth/authorize';
        $url.= '?'.http_build_query($params);

        redirect($url);
    }

    public function complete()
    {
        // Check for code in redirect
        if (!$this->input->get('code')) {
            show_error('Request failed.');
        }

        // Validate state
        if ($this->input->get('state') != $this->_state) {
            show_error('Invalid request.');
        }

        // Build access token request
        $token_params['client_id'] = $this->_client_id;
        $token_params['client_secret'] = $this->_client_secret;
        $token_params['code'] = $this->input->get('code');
        $token_params['state'] = $this->_state;

        $token_request = new CURL('https://github.com/login/oauth/access_token');
        $token_response = $token_request
            ->params($token_params)
            ->header('Accept: application/json')
            ->post();

        $token_data = json_decode("$token_response");

        // Display github error
        if (isset($data->error)) {
            show_error($data->error);
        }

        $access_token = $token_data->access_token;

        $user_params['access_token'] = $access_token;

        $user_request = new CURL('https://api.github.com/user');
        $user_response = $user_request
            ->params($user_params)
            ->header('Accept: application/json')
            ->get();

        $user_data = json_decode($user_response);

        $hubcap_data = $this->db
            ->where('access_token', $access_token)
            ->limit(1)
            ->get('users')
            ->row();

        if (isset($hubcap_data->id)) {
            $hubcap_id = $hubcap_data->id;
        } else {
            $this->db
                ->set('access_token', $access_token)
                ->insert('users');
            $hubcap_id = $this->db->insert_id();
        }

        $session_data['hubcap_id'] = $hubcap_id;
        $session_data['github_login'] = $user_data->login;
        $session_data['avatar'] = $user_data->avatar_url;

        $this->session->set_userdata($session_data);

        redirect();
    }
}

/* End of file oauth.php */
/* Location: ./application/controllers/oauth.php */
