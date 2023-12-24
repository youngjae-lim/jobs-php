<?php

/**
 * Get the base path of the project
 *
 * @param  string  $path
 * @return string
 */
function basePath($path = '')
{
    return __DIR__.'/'.$path;
}

/**
 * Load a view
 *
 * @param  string  $name
 * @param  array  $data
 * @return void
 */
function loadView($name, $data = [])
{
    $viewPath = basePath("App/views/{$name}.view.php");

    if (file_exists($viewPath)) {
        // Extract data from array so we can use it as variables in the view
        extract($data);

        require $viewPath;
    } else {
        echo "View {$name} not found.";
    }
}

/**
 * Load a partial
 *
 * @param  string  $name
 * @param  array  $data
 * @return void
 */
function loadPartial($name, $data = [])
{
    $partialPath = basePath("App/views/partials/{$name}.php");

    if (file_exists($partialPath)) {
        // Extract data from array so we can use it as variables in the view
        extract($data);

        require $partialPath;
    } else {
        echo "Partial {$name} not found.";
    }
}

/**
 * Inspect a variable
 *
 * @param  mixed  $var
 * @return void
 */
function inspect($var)
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
}

/**
 * Inspect a variable and die
 *
 * @param  mixed  $var
 * @return string
 */
function inspectAndDie($var)
{
    echo '<pre>';
    exit(var_dump($var));
    echo '</pre>';
}

/**
 * Format number as currency
 *
 * @param  int  $number
 * @return string
 */
function formatCurrency($number)
{
    return '$'.number_format(floatval($number));
}

/**
 * Sanitize a string
 *
 * @param  string  $dirty
 * @return string
 */
function sanitize($dirty)
{
    return filter_var(trim($dirty), FILTER_SANITIZE_SPECIAL_CHARS);
}

/**
 * Redirect to a given path
 *
 * @param  string  $path
 * @return void
 */
function redirect($path)
{
    header("Location: /{$path}");
    exit;
}
