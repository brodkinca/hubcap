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
 * @link      https://github.com/brodkinca/hubcap
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
 * @link      https://github.com/brodkinca/hubcap
 */
class webhook extends CI_Controller
{
    public function index()
    {
        $payload = @json_decode($this->input->post('payload'));

        if (!$payload) {
            show_error('JSON data not received.', 400);
        }

        $repo_name = $payload->repository->owner->name.'/'.$payload->repository->name;

        $config_db = $this->db
            ->select('repos.branch, repos.private_key, users.access_token')
            ->where('repos.name', $repo_name)
            ->join('users', 'users.id=repos.user_id')
            ->limit(1)
            ->get('repos')
            ->row();

        if (!isset($config_db->private_key)) {
            show_error('Repository not active in Hubcap. Sign up at http://hubcap.it/', 404);
        }

        // Default Values for User Configurable Options
        $config_user_defaults['dest_branch'] = 'gh-pages';
        $config_user_defaults['dest_path'] = '/';
        $config_user_defaults['source_path'] = '/docs';

        $config_file_request = new CURL(
            'https://api.github.com/repos/'.$repo_name.'/contents/hubcap.json'
        );
        $config_file_response = $config_file_request
            ->header('Accept: application/json')
            ->param('ref', $payload->after)
            ->param('access_token', $config_db->access_token)
            ->get();

        if ($config_file_response->success()) {
            $config_file_data = json_decode("$config_file_response");
            $config_file_data = json_decode(
                base64_decode($config_file_data->content),
                true
            );
            $config_user = array_merge($config_user_defaults, $config_file_data);
        } else {
            $config_user = $config_user_defaults;
        }

        $request_data['ref'] = $payload->after;
        $request_data['user'] = $payload->repository->owner->name;
        $request_data['repo'] = $payload->repository->name;
        $request_data['private_key'] = $config_db->private_key;
        $request_data['config'] = $config_user;

        $push_branch = substr($payload->ref, 11);

        $file_name = $request_data['user'].'_'.$request_data['repo'].'_'.$request_data['hash'].'_'.$push_branch.'.json';

        // Write file if branch matches
        if ($config_db->branch == $push_branch) {
            $this->load->helper('file');
            write_file(FCPATH.'/webhook_data/'.$file_name, json_encode($request_data));
        } else {
            show_error('This repository only updates docs when branch '.$config_db->branch.' is updated. Push to branch '.$push_branch.' ignored.', 200);
        }

    }
}

/* End of file webhook.php */
/* Location: ./application/controllers/webhook.php */
