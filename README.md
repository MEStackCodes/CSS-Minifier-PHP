# CSS Minifier CLI (PHP)

This CLI tool efficiently minifies CSS files using PHP. It's perfect for PHP development environments needing quick, dependency-free CSS compression. The lightweight CSS Minifier CLI processes one or more CSS files, removing whitespace, comments, and formatting to output minified CSS.

## Features

- **No external dependencies**: Pure PHP solution.
- **Batch processing**: Minify multiple CSS files at once.
- **CLI usage**: Simple command-line interface for rapid workflow integration.

## Requirements

- PHP 7.0 or higher
- CLI access to your development machine

## Usage

```bash
php cssminifier.php input1.css input2.css ...
```
This creates the output file: **input.min.css**, **input2.min.css....**

### Arguments

- `input.css`: The CSS file(s) to minify. You can pass one or more files.

### Examples

**Minify a single file**
```bash
php cssminifier.php styles.css
```

**Minify multiple files:**
```bash
php cssminifier.php reset.css main.css
```

**Minify all css files :**
```bash
php cssminifier.php *.css
```

## Share
If you liked and found this repository useful for your projects, star it. Thank you for your support! ⭐
