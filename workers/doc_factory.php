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

require_once '../vendor/autoload.php';

/* Setup Logger */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('DOC_FACTORY');
$log->pushHandler(new StreamHandler('../hubcap_logs/workers.log', Logger::DEBUG));

/* Worker is Active by Default */
$active = true;

/* Set Paths */
$temp_path = realpath('../repo_temp');
$data_path = realpath('../webhook_data');
$key_path = $temp_path.'/rsa.key';

/* Init Messages */
$log->addDebug('Document factory initialized.');
echo "\n###################################################\n";
echo "Document factory worker reporting for duty, sir.\n\n";
echo "Repos will be processed in the following directory:\n";
echo $temp_path."\n";
echo "###################################################\n";
$log->addDebug('Git Working Path: '.$temp_path);
$log->addDebug('JSON Data Source: '.$data_path);
$log->addDebug('RSA Keyfile: '.$key_path);

while (1) {
    $log->addDebug('Starting Worker Loop...');

    // Prcess SIGQUIT if no longer active
    if (!$active) {
        $log->addDebug('Terminating worker.');
        echo "Terminating worker...";
        exit;
    }

    $requests = glob("$data_path/*.json");

    $log->addDebug(count($requests).' requests found.');
    echo "\n###################################################\n";
    echo date(DATE_RFC1123)."\n";
    echo count($requests).' requests found.'."\n";
    echo "###################################################\n";

    foreach ($requests as $path_request_file) {

        // Use request filename as request ID
        $request_id = basename($path_request_file);

        // Log request ID
        $log->addDebug('Processing '.$request_id);

        // Set future paths
        $path_working_dir = $temp_path.'/'.sha1($request_id);
        $path_request_file_active = $path_request_file.'.active';

        // Rename request file to identify it as active
        $rename = rename($path_request_file, $path_request_file_active);

        // Test rename success
        if ($rename) {
            $log->addDebug(
                $request.' moved to '.basename($path_request_file_active)
            );
        } else {
            $log->addError(
                'Failed to rename '.$request_id.' to '.
                basename($path_request_file_active)
            );
            $log->addError('Continuing to next request.');
            continue;
        }

        // Get and process request data
        $request_data_raw = file_get_contents($path_request_file_active);
        $request_data = json_decode($request_data_raw);

        // Verify that JSON was decoded
        if (!$request_data) {
            $log->addError('Error reading variables from JSON.');
            $log->addError('Continuing to next request.');
            continue;
        }

        // Break out variables
        $user = $request_data->user;
        $repo = $request_data->repo;
        $ref = $request_data->ref;
        $source_path = $request_data->config->source_path;
        $dest_branch = $request_data->config->dest_branch;
        $dest_path = $request_data->config->dest_path;

        // Write key to file
        $key_success = file_put_contents($key_path, $request_data->private_key);

        if (!$key_success) {
            $log->addError('Could not write private key to '.$key_path);
            $log->addError('Continuing to next request.');
            continue;
        }

        $mkdir_success = mkdir($path_working_dir);

        if (!$mkdir_success) {
            $log->addError('Could not create working directory '.$path_working_dir);
            $log->addError('Continuing to next request.');
            continue;
        }

        if (is_writable($path_working_dir)) {
            // Update the docs at Github
            system("./doc_factory.sh $user $repo $ref $path_working_dir $source_path $dest_branch $dest_path $key_path");
            unlink($path_request_file_active);
            continue;
        } else {
            $log->addError('Working directory is not writable.');
        }

        /* @todo Should probably do this as a separate cleanup loop */
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
