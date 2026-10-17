<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
$pageTitle = 'We would love to hear from you';
$activePage = 'contact';
$sent = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $error = 'Your session has expired. Please refresh the page and try again.';
    } else {
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $message = trim((string) ($_POST['message'] ?? ''));
        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $message === '') {
            $error = 'Please provide your name, a valid email address, and a message.';
        } elseif (strlen($name) > 120 || strlen($email) > 254 || strlen($message) > 5000) {
            $error = 'Please keep your name, email, and message within the indicated limits.';
        } else {
            try {
                $statement = db()->prepare('INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)');
                $statement->execute([$name, $email, $message]);
                $sent = true;
            } catch (PDOException $exception) {
                error_log('Contact form submission failed: ' . $exception->getMessage());
                $error = 'We could not send your message right now. Please try again later or email us directly.';
            }
        }
    }
}
require __DIR__ . '/includes/header.php';
?>
<main class="contact-page">
    <section class="contact-intro"><span class="eyebrow"><span class="eyebrow-dot"></span> LET'S START A CONVERSATION</span><h1>We'd love<br>to <span>hear from you.</span></h1><p>Ask us anything, tell us what you're looking for, or come see the school for yourself. A real person on our team will get back to you.</p></section>
    <div class="contact-layout">
        <aside class="contact-details"><span class="eyebrow">COME SAY HELLO</span><h2>There's always<br>room for a chat.</h2><div class="contact-detail"><span>CALL US</span><a href="tel:+2342012345678">+234 (0) 201 234 5678</a></div><div class="contact-detail"><span>EMAIL US</span><a href="mailto:hello@attqwacollege.edu">hello@attqwacollege.edu</a></div><div class="contact-detail"><span>FIND US</span><p>12 Garden Avenue<br>Lagos, Nigeria</p></div><div class="contact-hours"><span>TERM-TIME OFFICE HOURS</span><p>Monday – Friday<br>8:00 am – 4:00 pm</p></div></aside>
        <section class="contact-form-card"><span class="eyebrow">SEND A NOTE</span><h2>What's on your mind?</h2>
            <?php if ($sent): ?><div class="notice notice-success" role="status">Thanks for reaching out! Our team will be in touch soon.</div><?php elseif ($error !== ''): ?><div class="notice notice-error" role="alert"><?= escape($error) ?></div><?php endif; ?>
            <form method="post" action="contact.php" class="form-stack">
                <input type="hidden" name="csrf_token" value="<?= escape(csrf_token()) ?>">
                <label for="name">Your name</label><input id="name" name="name" type="text" maxlength="120" autocomplete="name" required>
                <label for="email">Email address</label><input id="email" name="email" type="email" maxlength="254" autocomplete="email" required>
                <label for="message">Your message</label><textarea id="message" name="message" rows="5" maxlength="5000" required></textarea>
                <button class="button button-dark" type="submit">Send your message <span aria-hidden="true">↗</span></button>
            </form>
        </section>
    </div>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
