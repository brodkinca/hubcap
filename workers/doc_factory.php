#!/usr/bin/env php
<?php

/**
 * Hubcap Worker Process
 *
 * PHP Version 5.3
 *
 * @category  Worker
 * @package   Hubcap
 * @author    Brodkin CyberArts <support@brodkinca.com>
 * @copyright 2012 Brodkin CyberArts.
 * @license   All rights reserved.
 * @version   GIT: $Id$
 * @link      http://github.com/brodkinca/hubcap
 */

$active = true;

echo "\n###################################################\n";
echo "Document factory worker reporting for duty, sir.\n\n";

$temp_path = realpath('../repo_temp');
$data_path = realpath('../webhook_data');
$key_path = $temp_path.'/rsa.key';

echo "Repos will be processed in the following directory:\n";
echo $temp_path."\n";

echo "###################################################\n";

while (1) {

    // Prcess SIGQUIT if no longer active
    if (!$active) {
        echo "Terminating worker...";
        exit;
    }

    $requests = glob("$data_path/*.json");

    echo "\n###################################################\n";
    echo date(DATE_RFC1123)."\n";
    echo count($requests).' requests found.'."\n";
    echo "###################################################\n";

    foreach ($requests as $path_request_file) {

        // Use request filename as request ID
        $request_id = basename($path_request_file);

        // Set future paths
        $path_working_dir = $temp_path.'/'.sha1($request_id);
        $path_request_file_active = $path_request_file.'.active';

        // Rename request file to identify it as active
        rename($path_request_file, $path_request_file_active);

        // Get and process request data
        $request_data_raw = file_get_contents($path_request_file_active);
        $request_data = json_decode($request_data_raw);

        if (!$request_data) {
            file_put_contents('php://stderr', 'Error reading variables from JSON.');
        }

        // Break out variables
        $user = $request_data->user;
        $repo = $request_data->repo;
        $ref = $request_data->ref;
        $source_path = $request_data->config->source_path;
        $dest_branch = $request_data->config->dest_branch;
        $dest_path = $request_data->config->dest_path;

        // Write key to file
        file_put_contents($key_path, $request_data->private_key);

        mkdir($path_working_dir);

        if (is_writable($path_working_dir)) {

            // Update the docs at Github
            system("./doc_factory.sh $user $repo $ref $path_working_dir $source_path $dest_branch $dest_path $key_path");
            unlink($path_request_file_active);
            continue;
        }

        if (file_exists($path_request_file_active)) {

            // FAILURE: Remove .active suffix from request file
            rename($path_request_file_active, $path_request_file);
        }
    }

    sleep(60); /* @todo Make sleep time dynamic based on previous batch size. */
}

/**
 * Process SIGQUIT Signal
 *
 * @return null
 */
function processSigquit()
{
    $GLOBALS['active'] = false;
}
pcntl_signal(SIGQUIT, "process_sigquit");
