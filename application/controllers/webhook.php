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
            $hash = $data->after;
            $user = $data->repository->owner->name;
            $repo = $data->repository->name;
            $branch = (strpos($data->ref, 'refs/heads/') === 0) ? substr($data->ref, 11) : false;

            print_r($repo);

        } else {
            show_error('JSON data not received.');
        }
    }
}

/* End of file webhook.php */
/* Location: ./application/controllers/webhook.php */
