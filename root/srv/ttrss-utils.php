<?php

function env($name, $default = null)
{
    $v = getenv($name) ?: $default;

    if ($v === null) {
        error('The env ' . $name . ' does not exist');
    }

    return $v;
}

function error($text)
{
    echo 'Error: ' . $text . PHP_EOL;
    exit(1);
}

function dbconnect($config)
{
    $map = array('host' => 'HOST', 'port' => 'PORT', 'dbname' => 'NAME', 'user' => 'USER', 'password' => 'PASS');
    $dsn = $config['TTRSS_DB_TYPE'] . ':';
    foreach ($map as $d => $h) {
        if (isset($config['TTRSS_DB_' . $h])) {
            $dsn .= $d . '=' . $config['TTRSS_DB_' . $h] . ';';
        }
    }
    echo($dsn);
    if ($config['TTRSS_DB_TYPE'] == 'pgsql'){
        $pdo = new \PDO($dsn);
    } else {
        $pdo = new \PDO($dsn, $config['TTRSS_DB_USER'], $config['TTRSS_DB_PASS']);
    }
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

function dbcheck($config)
{
    try {
        dbconnect($config);
        return true;
    }
    catch (PDOException $e) {
        return false;
    }
}

?>
