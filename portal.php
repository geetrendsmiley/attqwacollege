<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
$pageTitle = 'Your school portal';
$activePage = 'portal';
$error = '';
$notice = '';
$paymentNotice = isset($_GET['payment']) && is_string($_GET['payment']) ? trim($_GET['payment']) : '';
$paymentNoticeType = isset($_GET['type']) && $_GET['type'] === 'notice-success' ? 'notice-success' : 'notice-error';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $error = 'Your session has expired. Please refresh the page and try again.';
    } else {
        $action = (string) ($_POST['action'] ?? '');
        try {
            if ($action === 'logout') {
                $_SESSION = [];
                session_regenerate_id(true);
                header('Location: portal.php');
                exit;
            } elseif ($action === 'login') {
                $admissionNumber = strtoupper(trim((string) ($_POST['admission_number'] ?? '')));
                $password = (string) ($_POST['password'] ?? '');
                $statement = db()->prepare('SELECT id, admission_number, full_name, email, class_name, password_hash FROM students WHERE admission_number = ? LIMIT 1');
                $statement->execute([$admissionNumber]);
                $student = $statement->fetch();
                if (!$student || !$student['password_hash'] || !password_verify($password, $student['password_hash'])) {
                    $error = 'We could not sign you in with those details. Please check them and try again.';
                } else {
                    session_regenerate_id(true);
                    unset($_SESSION['csrf_token']);
                    $_SESSION['student'] = [
                        'id' => (int) $student['id'],
                        'admission_number' => $student['admission_number'],
                        'full_name' => $student['full_name'],
                        'email' => $student['email'],
                        'class_name' => $student['class_name'],
                    ];
                    header('Location: portal.php');
                    exit;
                }
            } elseif ($action === 'activate') {
                $admissionNumber = strtoupper(trim((string) ($_POST['admission_number'] ?? '')));
                $email = trim((string) ($_POST['email'] ?? ''));
                $password = (string) ($_POST['password'] ?? '');
                if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 10) {
                    $error = 'Enter the email address on your school record and choose a password of at least 10 characters.';
                } else {
                    $statement = db()->prepare('UPDATE students SET password_hash = ? WHERE admission_number = ? AND email = ? AND password_hash IS NULL');
                    $statement->execute([password_hash($password, PASSWORD_DEFAULT), $admissionNumber, $email]);
                    if ($statement->rowCount() !== 1) {
                        $error = 'We could not match an unused student record to those details. Please contact the school office.';
                    } else {
                        $notice = 'Your portal account is ready. You can now sign in.';
                    }
                }
            } else {
                $error = 'That action is not available.';
            }
        } catch (PDOException $exception) {
            error_log('Portal request failed: ' . $exception->getMessage());
            $error = 'The portal is temporarily unavailable. Please try again later.';
        }
    }
}

$student = $_SESSION['student'] ?? null;
$payments = [];
if ($student) {
    try {
        $statement = db()->prepare('SELECT reference, amount, currency, term, status, created_at FROM payments WHERE student_id = ? ORDER BY created_at DESC LIMIT 10');
        $statement->execute([$student['id']]);
        $payments = $statement->fetchAll();
    } catch (PDOException $exception) {
        error_log('Portal payment history lookup failed: ' . $exception->getMessage());
        $error = 'Your account is available, but payment history could not be loaded right now.';
    }
}
require __DIR__ . '/includes/header.php';
?>
<main class="portal-page">
    <?php if ($student): ?>
        <section class="portal-dashboard">
            <div class="portal-welcome"><span class="eyebrow"><span class="eyebrow-dot"></span> YOUR ATTQWA COLLEGE PORTAL</span><h1>Welcome back,<br><span><?= escape(explode(' ', $student['full_name'])[0]) ?>.</span></h1><p>Find your school details and take care of your term fees, all in one place.</p></div>
            <?php if ($error !== ''): ?><div class="notice notice-error" role="alert"><?= escape($error) ?></div><?php endif; ?>
            <?php if ($paymentNotice !== ''): ?><div class="notice <?= escape($paymentNoticeType) ?>" role="status"><?= escape($paymentNotice) ?></div><?php endif; ?>
            <div class="portal-grid">
                <section class="portal-card profile-card"><span class="eyebrow">STUDENT PROFILE</span><h2><?= escape($student['full_name']) ?></h2><dl><div><dt>Admission number</dt><dd><?= escape($student['admission_number']) ?></dd></div><div><dt>Class</dt><dd><?= escape($student['class_name']) ?></dd></div><div><dt>School email</dt><dd><?= escape($student['email']) ?></dd></div></dl></section>
                <section class="portal-card fee-card"><span class="eyebrow">TERM FEES</span><h2>Make a payment</h2><p>Your fee is set automatically for your school level. Payments are securely processed by Kora.</p><form method="post" action="payment.php" class="form-stack"><input type="hidden" name="csrf_token" value="<?= escape(csrf_token()) ?>"><label for="term">Choose term</label><select name="term" id="term" required><option value="First Term">First Term</option><option value="Second Term">Second Term</option><option value="Third Term">Third Term</option></select><button class="button button-dark" type="submit">Continue to secure payment <span aria-hidden="true">↗</span></button></form></section>
            </div>
            <section class="portal-card history-card"><div class="history-heading"><div><span class="eyebrow">RECENT ACTIVITY</span><h2>Payment history</h2></div><form method="post"><input type="hidden" name="csrf_token" value="<?= escape(csrf_token()) ?>"><input type="hidden" name="action" value="logout"><button class="text-link sign-out-button" type="submit">Sign out <span aria-hidden="true">→</span></button></form></div>
                <?php if ($payments): ?><div class="table-wrap"><table><thead><tr><th>Reference</th><th>Term</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead><tbody><?php foreach ($payments as $payment): ?><tr><td><?= escape($payment['reference']) ?></td><td><?= escape($payment['term']) ?></td><td><?= escape(format_money((int) $payment['amount'])) ?></td><td><span class="status-pill status-<?= escape(strtolower($payment['status'])) ?>"><?= escape(ucfirst($payment['status'])) ?></span></td><td><?= escape(date('M j, Y', strtotime($payment['created_at']))) ?></td></tr><?php endforeach; ?></tbody></table></div><?php else: ?><p class="empty-state">Your payments will appear here once you make your first payment.</p><?php endif; ?>
            </section>
        </section>
    <?php else: ?>
        <section class="portal-auth-layout"><div class="portal-welcome"><span class="eyebrow"><span class="eyebrow-dot"></span> A LITTLE SOMETHING FOR FAMILIES</span><h1>Your school,<br><span>all in one place.</span></h1><p>Sign in to check your student details and pay term fees securely online.</p><div class="portal-illustration" aria-hidden="true"><span>✳</span><span>✿</span><span>↗</span></div></div>
            <div class="portal-auth-card"><span class="eyebrow">WELCOME TO THE PORTAL</span><h2>Sign in to continue</h2><p>Use the admission number and password connected to your student record.</p>
                <?php if ($error !== ''): ?><div class="notice notice-error" role="alert"><?= escape($error) ?></div><?php elseif ($notice !== ''): ?><div class="notice notice-success" role="status"><?= escape($notice) ?></div><?php endif; ?>
                <form method="post" class="form-stack"><input type="hidden" name="csrf_token" value="<?= escape(csrf_token()) ?>"><input type="hidden" name="action" value="login"><label for="admission-number">Admission number</label><input id="admission-number" name="admission_number" type="text" maxlength="40" autocomplete="username" required><label for="login-password">Password</label><input id="login-password" name="password" type="password" autocomplete="current-password" required><button class="button button-dark" type="submit">Sign in to your portal <span aria-hidden="true">→</span></button></form>
                <details class="activate-account"><summary>First time here? Activate your account</summary><p>Student accounts are prepared by the school. Use your registered email and admission number.</p><form method="post" class="form-stack"><input type="hidden" name="csrf_token" value="<?= escape(csrf_token()) ?>"><input type="hidden" name="action" value="activate"><label for="activate-admission">Admission number</label><input id="activate-admission" name="admission_number" type="text" maxlength="40" required><label for="activate-email">School-record email</label><input id="activate-email" name="email" type="email" maxlength="254" required><label for="new-password">Create password (10+ characters)</label><input id="new-password" name="password" type="password" minlength="10" autocomplete="new-password" required><button class="button button-outline" type="submit">Activate account</button></form></details>
            </div>
        </section>
    <?php endif; ?>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
