<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
$student = require_student();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        http_response_code(403);
        exit('Your session has expired. Please return to the portal and try again.');
    }
    $term = (string) ($_POST['term'] ?? '');
    $fees = SCHOOL_FEES;
    if (!isset($fees[$student['class_name']]) || !in_array($term, ['First Term', 'Second Term', 'Third Term'], true)) {
        http_response_code(400);
        exit('Your student record or selected term is not valid for payment.');
    }
    $secret = getenv('KORA_SECRET_KEY');
    if (!$secret) {
        http_response_code(503);
        exit('Online payments are not configured yet. Please contact the school office.');
    }
    $reference = 'ATQ-' . bin2hex(random_bytes(12));
    $amount = $fees[$student['class_name']];
    $baseUrl = rtrim(getenv('APP_BASE_URL') ?: '', '/');
    if ($baseUrl === '') {
        http_response_code(503);
        exit('The public site address is not configured for secure payment. Please contact the school office.');
    }
    try {
        $statement = db()->prepare('INSERT INTO payments (student_id, reference, amount, currency, term, status) VALUES (?, ?, ?, ?, ?, ?)');
        $statement->execute([$student['id'], $reference, $amount, SCHOOL_CURRENCY, $term, 'pending']);
        $payload = json_encode([
            'reference' => $reference,
            'amount' => $amount,
            'currency' => SCHOOL_CURRENCY,
            'redirect_url' => $baseUrl . '/payment.php?return=1',
            'customer' => ['name' => $student['full_name'], 'email' => $student['email']],
            'metadata' => ['student_id' => $student['id'], 'term' => $term],
        ], JSON_THROW_ON_ERROR);
        if (!function_exists('curl_init')) {
            throw new RuntimeException('PHP cURL is required to process payments.');
        }
        $curl = curl_init('https://api.korapay.com/merchant/api/v1/charges/initialize');
        if ($curl === false) {
            throw new RuntimeException('Could not connect to the payment service.');
        }
        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $secret, 'Content-Type: application/json', 'Accept: application/json'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,
        ]);
        $responseBody = curl_exec($curl);
        $statusCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        curl_close($curl);
        $response = is_string($responseBody) ? json_decode($responseBody, true) : null;
        $checkoutUrl = $response['data']['checkout_url'] ?? null;
        if ($curlError !== '' || $statusCode < 200 || $statusCode >= 300 || !is_string($checkoutUrl) || !filter_var($checkoutUrl, FILTER_VALIDATE_URL) || parse_url($checkoutUrl, PHP_URL_HOST) !== 'checkout.korapay.com') {
            $failed = db()->prepare('UPDATE payments SET status = ? WHERE reference = ?');
            $failed->execute(['failed', $reference]);
            error_log('Kora payment initialization failed: HTTP ' . $statusCode . '; ' . $curlError);
            http_response_code(502);
            exit('We could not start your payment. Please try again or contact the school office.');
        }
        header('Location: ' . $checkoutUrl, true, 303);
        exit;
    } catch (PDOException | JsonException | RuntimeException $exception) {
        error_log('Payment initialization failed: ' . $exception->getMessage());
        http_response_code(503);
        exit('We could not start your payment. Please try again or contact the school office.');
    }
}

$message = '';
$messageType = 'notice-error';
if (isset($_GET['return'], $_GET['reference']) && is_string($_GET['reference'])) {
    $reference = $_GET['reference'];
    try {
        $statement = db()->prepare('SELECT id, amount, currency FROM payments WHERE reference = ? AND student_id = ? LIMIT 1');
        $statement->execute([$reference, $student['id']]);
        $payment = $statement->fetch();
        $secret = getenv('KORA_SECRET_KEY');
        if (!$payment || !$secret) {
            throw new RuntimeException('The payment could not be matched to your account.');
        }
        if (!function_exists('curl_init')) {
            throw new RuntimeException('PHP cURL is required to verify payments.');
        }
        $curl = curl_init('https://api.korapay.com/merchant/api/v1/charges/' . rawurlencode($reference));
        if ($curl === false) {
            throw new RuntimeException('Could not connect to the payment service.');
        }
        curl_setopt_array($curl, [
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $secret, 'Accept: application/json'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,
        ]);
        $responseBody = curl_exec($curl);
        $statusCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        curl_close($curl);
        $response = is_string($responseBody) ? json_decode($responseBody, true) : null;
        $transaction = $response['data'] ?? [];
        if ($curlError !== '' || $statusCode < 200 || $statusCode >= 300 || !is_array($transaction)) {
            throw new RuntimeException('Payment status could not be verified. Please check back shortly.');
        }
        $paid = ($transaction['status'] ?? '') === 'success'
            && ($transaction['reference'] ?? '') === $reference
            && (int) ($transaction['amount'] ?? 0) === (int) $payment['amount']
            && ($transaction['currency'] ?? '') === $payment['currency'];
        $newStatus = $paid ? 'paid' : (($transaction['status'] ?? '') === 'failed' ? 'failed' : 'pending');
        $update = db()->prepare('UPDATE payments SET status = ? WHERE id = ? AND status != ?');
        $update->execute([$newStatus, $payment['id'], 'paid']);
        if ($paid) {
            $message = 'Your payment has been confirmed. Thank you!';
            $messageType = 'notice-success';
        } elseif ($newStatus === 'pending') {
            $message = 'Your payment is still being processed. Please refresh your portal payment history in a moment.';
        } else {
            $message = 'The payment was not completed. You can try again from your portal.';
        }
    } catch (PDOException | JsonException | RuntimeException $exception) {
        error_log('Payment verification failed: ' . $exception->getMessage());
        $message = 'We could not verify your payment yet. Please check your payment history shortly or contact the school office.';
    }
} else {
    http_response_code(405);
    $message = 'That payment link is not valid. Please return to your portal.';
}
header('Location: portal.php?payment=' . rawurlencode($message) . '&type=' . rawurlencode($messageType), true, 303);
