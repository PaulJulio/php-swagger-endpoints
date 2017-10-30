#!/usr/local/bin/php
<?php
/**
 * .git/hooks/pre-commit.
 *
 * This pre-commit hooks will check for PHP error (lint), and make sure the code
 * is PSR compliant.
 *
 * Dependecy: PHP-CS-Fixer (https://github.com/friendsofphp/php-cs-fixer)
 *
 * @author  Mardix  http://github.com/mardix
 *
 * @since   Sept 4 2012
 */

/**
 * collect all files which have been added, copied or
 * modified and store them in an array called output.
 */
exec('git diff --cached --name-status --diff-filter=ACM', $output);
$mergeBranch   = exec('git rev-parse -q --verify MERGE_HEAD');
$isMergeCommit = (trim($mergeBranch) !== '');

$gitRepoDirectory = realpath(__DIR__ . '/../../');

foreach ($output as $file) {
    $fileName = trim(substr($file, 1));

    // only run against PHP files
    if (pathinfo($fileName, PATHINFO_EXTENSION) == 'php') {
        // check for error
        $lint_output = [];
        exec('/usr/local/bin/php -l ' . escapeshellarg($fileName), $lint_output, $return);

        if ($return == 0) {
            // we only run php fix if we are not a merge commit
            if (!$isMergeCommit) {
                // run PHP-CS-Fixer && add it back
                exec("/usr/local/bin/php {$gitRepoDirectory}/src/vendor/friendsofphp/php-cs-fixer/php-cs-fixer --config={$gitRepoDirectory}/.php_cs fix {$fileName}; git add {$fileName}");
            }
        } else {
            echo implode("\n", $lint_output), "\n";

            exit(1);
        }
    }
}

exit(0);
