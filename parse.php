#!/usr/bin/php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use League\CLImate\CLImate;
use Commando\Command;

const OPTION_INPUT_FILENAME = 0;
const OPTION_OUTPUT_FILENAME = 'o';

const CSV_PATH = 'Path';
const CSV_VERB = 'Verb';
const CSV_SUMMARY = 'Summary';
const CSV_OAUTH = 'OAuth Permissions';
const CSV_DESCRIPTION = 'Description';

$cli = new CLImate();
$cmd = new Command();

$cmd->doNotTrapErrors();
$cmd->setHelp('Extracts info from Swagger spec files for display or storage.');

$cmd->option()
    ->require()
    ->describedAs('Full path to the file to parse')
    ;

$cmd->option('o')
    ->require(false)
    ->describedAs('Full path to output csv file')
    ;

try {
    $cmd->parse();
    if (!file_exists($cmd[OPTION_INPUT_FILENAME])) {
        throw new Exception('File does not exist.');
    }
} catch (Exception $e) {
    $cli->backgroundRed()->white->bold->out($e->getMessage());
} finally {
    if (!$cmd->valid()) {
        $cmd->printHelp();
        exit(1);
    }
}

$contents = file_get_contents($cmd[OPTION_INPUT_FILENAME]);
$swagger = json_decode($contents, true);

$csv = [];

foreach ($swagger['paths'] as $path => $verbs) {
    $cli->bold()->out($path);
    foreach ($verbs as $verb => $details) {
        $row = [];
        $row[CSV_PATH] = $path;
        $row[CSV_VERB] = $verb;
        $cli->out($verb . ' ' . $details['summary']);
        if (is_array($details['security'][0]['oauth2']) && count($details['security'][0]['oauth2'])) {
            $row[CSV_OAUTH] = implode(',', $details['security'][0]['oauth2']);
            $cli->backgroundRed()->white()->out($row[CSV_OAUTH]);
        } else {
            $row[CSV_OAUTH] = "(none)";
            $cli->backgroundGreen()->white()->out('no oauth security');
        }
        $row[CSV_SUMMARY] = $details['summary'];
        $row[CSV_DESCRIPTION] = $details['description'];
    }
    $csv[] = $row;
    $cli->out(PHP_EOL);
}

if (count($csv) > 0 && isset($cmd[OPTION_OUTPUT_FILENAME])) {
    $fhout = fopen($cmd[OPTION_OUTPUT_FILENAME], 'w');
    $header = array_keys($csv[0]);
    fputcsv($fhout, $header);
    foreach ($csv as $row) {
        fputcsv($fhout, $row);
    }
}
