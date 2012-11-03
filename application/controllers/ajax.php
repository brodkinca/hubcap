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
class ajax extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('hubcap_id')) {
            show_error('You must be logged in to access data.');
        }

        $this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'file'));

        $this->output->set_content_type('application/json');
    }

    public function activate()
    {
        $repo = $this->input->post('repo');

        if ($repo) {
            $this->db
                ->set('name', $repo)
                ->set('user_id', $this->session->userdata('hubcap_id'))
                ->insert('repos');

        } else {
            show_error('Repo parameter is required.', 400);
        }
    }

    public function deactivate()
    {
        $repo = $this->input->post('repo');

        if ($repo) {
            $this->db
                ->where('name', $repo)
                ->delete('repos');

        } else {
            show_error('Repo parameter is required.', 400);
        }
    }

    public function repo_branch_update()
    {
        $repo = $this->input->post('repo');
        $branch = $this->input->post('branch');

        if (!empty($branch) && !empty($repo)) {
            $this->db
                ->set('branch', $branch)
                ->where('name', $repo)
                ->limit(1)
                ->update('repos');

            if ($this->db->rows_affected() === 0) {
                show_error('Repository not found.', 404);
            }
        } else {
            show_error('Bad request.', 400);
        }

    }

    public function repo_branches()
    {
        $repo = $this->input->get('repo');

        $repo_branches = $this->_fetch_data('get', '/repos/'.$repo.'/branches');

        $data = array();

        foreach ($repo_branches as $repo) {
            if ($repo->name !== 'gh-pages') {
                $data[] = $repo->name;
            }
        }

        echo json_encode(array('branches'=>$data));
    }

    public function repos()
    {
        $hubcap_repos = $this->db
            ->where('user_id', $this->session->userdata('hubcap_id'))
            ->limit(50)
            ->get('repos')
            ->result();

        $repo_branches = array();
        foreach ($hubcap_repos as $repo) {
            $repo_branches[$repo->name] = $repo->branch;
        }

        $data = array();

        $user_repos = $this->_fetch_data('get', '/user/repos');
        foreach ($user_repos as $repo) {
            $repo_data['name'] = $repo->full_name;
            $repo_data['repo_url'] = $repo->html_url;
            $repo_data['pages_url'] = 'http://'.$repo->owner->login.'.github.com/'.$repo->name;
            $repo_data['branch'] = @$repo_branches[$repo->full_name];

            $user_data['repos'][] = $repo_data;
        }

        $user_data['login'] = $repo->owner->login;
        $user_data['avatar'] = $repo->owner->avatar_url;

        $data[] = $user_data;

        $organizations = $this->_fetch_data('get', '/user/orgs');
        foreach ($organizations as $org) {
            $org_data = array();
            $org_data['login'] = $org->login;
            $org_data['avatar'] = $org->avatar_url;

            $org_repos = $this->_fetch_data('get', '/orgs/'.$org->login.'/repos');
            foreach ($org_repos as $repo) {
                $repo_data['name'] = $repo->full_name;
                $repo_data['repo_url'] = $repo->html_url;
                $repo_data['pages_url'] = 'http://'.$repo->owner->login.'.github.com/'.$repo->name;
                $repo_data['branch'] = @$repo_branches[$repo->full_name];

                $org_data['repos'][] = $repo_data;
            }

            $data[] = $org_data;
        }

        echo json_encode(array('users'=>$data));
    }

    private function _fetch_data($method, $uri, array $params=array(), $force_update=false)
    {
        $uri = ltrim($uri, '/');

        $user_data = $this->db
            ->where('id', $this->session->userdata('hubcap_id'))
            ->limit(1)
            ->get('users')
            ->row();

        $access_token = $user_data->access_token;

        $request_id = 'request_'.sha1($method.$uri.serialize($params).$access_token);

        if (!$force_update) {
            $data = $this->cache->get($request_id);
        }

        if (!$data) {
            // New request
            $request = new CURL('https://api.github.com/'.$uri);
            $data = $request
                ->header('Accept: application/json')
                ->params($params)
                ->param('access_token', $access_token)
                ->{$method}();

            $this->output->set_status_header($data->status());

            if ($data->success()) {
                $this->cache->save($request_id, "$data", 600);
            } else {
                show_error(
                    'Could not retrieve data from Github API.',
                    $data->status()
                );
            }
        }

        return json_decode("$data");
    }
}

/* End of file ajax.php */
/* Location: ./application/controllers/ajax.php */
