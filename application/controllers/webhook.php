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
class webhook extends CI_Controller
{
    public function index()
    {
        $data = @json_decode($this->input->post('payload'));

        if ($data) {
            $out['hash'] = $data->after;
            $out['user'] = $data->repository->owner->name;
            $out['repo'] = $data->repository->name;
            $out['branch'] = (strpos($data->ref, 'refs/heads/') === 0) ? substr($data->ref, 11) : false;

            $file_name = $out['user'].'_'.$out['repo'].'_'.$out['hash'].$out['branch'].'.json';

            $this->load->helper('file');
            write_file(FCPATH.'/webhook_data/'.$file_name, json_encode($out));

        } else {
            show_error('JSON data not received.');
        }
    }
}

/* End of file webhook.php */
/* Location: ./application/controllers/webhook.php */
