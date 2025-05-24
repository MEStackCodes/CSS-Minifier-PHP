#!/usr/bin/env php
<?php

/**
* CLI-CSS-Minifier
* Version: 1.0.0
* Author: MEStackCodes
* MIT License
* A simple CLI tool to minify CSS files.
*/

define('TOOL_NAME', 'CLI-CSS-Minifier');
define('TOOL_VERSION', '1.0.0');
define('TOOL_AUTHOR', 'MEStackCodes. 2025');
define('TOOL_REPOSITORY', 'https://github.com/MEStackCodes/CSS-Minifier-PHP');

function printBanner()
{
 echo "==============================\n";
 echo TOOL_NAME . "\n";
 echo "Version: " . TOOL_VERSION . "\n";
 echo "Author: " . TOOL_AUTHOR . "\n";
 echo TOOL_REPOSITORY . "\n";
 echo "==============================\n";
}

function printHelp()
{
        printBanner();

echo <<<EOT
Usage:
  php cssminifier.php [input-file|*.css|-help]

Options:
  -help           Show this help message.
  [file].css      Specify a CSS file to minify.
  *.css           Specify wildcard to minify all CSS files in current directory.

Examples:
  php cssminifier.php styles.css
  php cssminifier.php styles1.css styles2.css styles3.css
  php cssminifier.php *.css
  php cssminifier.php -help

EOT;
    }

    function error($message)
    {
        fwrite(STDERR, "[ERROR] $message\n");
        exit(1);
    }

    function minifyCss($cssContent)
    {
        // Remove comments
        $minified = preg_replace('!/\*.*?\*/!s', '', $cssContent);
        // Remove space after colons
        $minified = preg_replace('/\s*:\s*/', ':', $minified);
        // Remove whitespace
        $minified = preg_replace('/\s+/', ' ', $minified);
        // Remove unnecessary spaces
        $minified = preg_replace('/;\s+/', ';', $minified);
        $minified = preg_replace('/\s*{\s*/', '{', $minified);
        $minified = preg_replace('/\s*}\s*/', '}', $minified);
        $minified = preg_replace('/\s*,\s*/', ',', $minified);
        // Remove trailing ; inside blocks
        $minified = preg_replace('/;}/', '}', $minified);
        // Trim
        $minified = trim($minified);
        return $minified;
    }

    function getOutputFilename($inputFile)
    {
        $info = pathinfo($inputFile);
        return $info['dirname'] . DIRECTORY_SEPARATOR . $info['filename'] . '.min.css';
    }

    function processFile($inputFile)
    {
        if (!is_readable($inputFile)) {
            error("Cannot read file: $inputFile");
        }
        $cssContent = file_get_contents($inputFile);
        $minifiedCss = minifyCss($cssContent);
        $outputFile = getOutputFilename($inputFile);

        if (file_put_contents($outputFile, $minifiedCss) === false) {
            error("Failed to write minified CSS to $outputFile");
        }
        return [$inputFile, $outputFile];
    }

    // MAIN CLI ENTRY POINT
    function main($argv)
    {

        array_shift($argv); // Remove script name

        if (empty($argv)) {
            error("No input file provided. Use -help for usage instructions.");
        }

        if ($argv[0] === '-help' || $argv[0] === '--help') {
            printHelp();
            exit(0);
        }

        printBanner();

        foreach ($argv as $key => $input) {
        
            $inputFiles = [];
            $outputFiles = [];

            // Wildcard support (only *.css)
            if (strpos($input, '*.') !== false) {
                $cwd = getcwd();
                $pattern = $cwd . DIRECTORY_SEPARATOR . $input;
                foreach (glob($pattern) as $file) {
                    if (is_file($file) && strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'css' && substr($file, -8) !== '.min.css') {
                        list($in, $out) = processFile($file);
                        $inputFiles[] = $in;
                        $outputFiles[] = $out;
                    }
                }
                if (empty($inputFiles)) {
                    error("No CSS files found matching pattern: $input");
                }
            } else {
                // Single file
                if (!file_exists($input)) {
                    error("File not found: $input");
                }
                if (strtolower(pathinfo($input, PATHINFO_EXTENSION)) !== 'css') {
                    error("Input file must have .css extension");
                }
                if (substr($input, -8) === '.min.css') {
                    error("Cannot minify an already minified file: $input");
                }
                list($in, $out) = processFile($input);
                $inputFiles[] = $in;
                $outputFiles[] = $out;
            }

            // Output info
            echo "Input file(s):\n";
            foreach ($inputFiles as $file) {
                echo "  - $file\n";
            }
            echo "Output file(s):\n";
            foreach ($outputFiles as $file) {
                echo "  - $file\n";
            }
            echo "Done.\n";

        }
    }

if (php_sapi_name() === 'cli') {
main($argv);
}