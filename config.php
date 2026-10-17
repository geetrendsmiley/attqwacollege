<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'httponly' => true,
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax',
    ]);
    session_start();
}

const SCHOOL_NAME = 'Attqwa College';
const SCHOOL_CURRENCY = 'NGN';
const SCHOOL_FEES = [
    'Early Years' => 85000,
    'Primary' => 120000,
    'Junior Secondary' => 165000,
    'Senior Secondary' => 195000,
];

function db(): PDO
{
    static $pdo;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $database = getenv('DB_NAME') ?: 'attqwa_college';
    $username = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASSWORD') ?: '';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $sslCa = getenv('DB_SSL_CA');
    if ($sslCa !== false && $sslCa !== '') {
        if (!is_file($sslCa)) {
            $sslCa = __DIR__ . DIRECTORY_SEPARATOR . $sslCa;
        }
        if (!is_file($sslCa)) {
            throw new RuntimeException('The configured DB_SSL_CA file could not be found.');
        }
        $options[PDO::MYSQL_ATTR_SSL_CA] = $sslCa;
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = true;
    }

    $pdo = new PDO(
        "mysql:host={$host};dbname={$database};charset=utf8mb4",
        $username,
        $password,
        $options
    );

    return $pdo;
}

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function csrf_token(): string
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(): bool
{
    return isset($_POST['csrf_token'], $_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], (string) $_POST['csrf_token']);
}

function require_student(): array
{
    if (!isset($_SESSION['student'])) {
        header('Location: portal.php');
        exit;
    }
    return $_SESSION['student'];
}

function format_money(int $amount): string
{
    return SCHOOL_CURRENCY . ' ' . number_format($amount);
}
