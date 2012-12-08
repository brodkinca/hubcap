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
        $data = @json_decode($this->input->post('payload'));

        if ($data) {

            $config_db = $this->db
                ->select('branch, private_key')
                ->where(
                    'name',
                    $data->repository->owner->name.'/'.$data->repository->name
                )
                ->limit(1)
                ->get('repos')
                ->row();

            if (!isset($config_db->private_key)) {
                show_error('Repository not active in Hubcap. Sign up at http://hubcap.it/', 404);
            }

            $request_data['ref'] = $data->after;
            $request_data['user'] = $data->repository->owner->name;
            $request_data['repo'] = $data->repository->name;
            $request_data['private_key'] = $config_db->private_key;

            $push_branch = substr($data->ref, 11);

            $file_name = $request_data['user'].'_'.$request_data['repo'].'_'.$request_data['hash'].'_'.$push_branch.'.json';

            // Write file if branch matches
            if ($config_db->branch == $push_branch) {
                $this->load->helper('file');
                write_file(FCPATH.'/webhook_data/'.$file_name, json_encode($request_data));
            } else {
                show_error('This repository only updates docs when branch '.$config_db->branch.' is updated. Push to branch '.$push_branch.' ignored.', 200);
            }

        } else {
            show_error('JSON data not received.', 400);
        }
    }
}

/* End of file webhook.php */
/* Location: ./application/controllers/webhook.php */
