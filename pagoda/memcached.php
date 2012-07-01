<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// --------------------------------------------------------------------------
// Servers
// --------------------------------------------------------------------------

$config['memcached'] = array(
    'hostname' => $_SERVER["CACHE1_HOST"],
    'port' => $_SERVER["CACHE1_PORT"],
    'weight' => '1',
);

/* End of file memcached.php */
/* Location: ./system/application/config/memcached.php */