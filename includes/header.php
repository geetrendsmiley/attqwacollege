<?php
$pageTitle = $pageTitle ?? SCHOOL_NAME;
$activePage = $activePage ?? '';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f7f6f1">
    <meta name="description" content="Attqwa College is a warm, welcoming school where every child can find what excites them and grow with confidence.">
    <title><?= escape($pageTitle) ?> — <?= escape(SCHOOL_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/site.js" defer></script>
</head>
<body>
<header class="site-header">
    <a class="brand" href="index.php" aria-label="<?= escape(SCHOOL_NAME) ?> home"><span class="brand-mark">a<span>✳</span></span><span class="brand-name">attqwa<small>COLLEGE</small></span></a>
    <button class="menu-toggle" type="button" aria-label="Open navigation" aria-expanded="false" aria-controls="main-navigation"><span></span><span></span></button>
    <nav class="main-navigation" id="main-navigation" aria-label="Main navigation">
        <a class="<?= $activePage === 'home' ? 'active' : '' ?>" href="index.php">Home</a>
        <a class="<?= $activePage === 'about' ? 'active' : '' ?>" href="about.php">About</a>
        <a class="<?= $activePage === 'contact' ? 'active' : '' ?>" href="contact.php">Contact</a>
        <a class="nav-portal <?= $activePage === 'portal' ? 'active' : '' ?>" href="portal.php">School portal <span aria-hidden="true">↗</span></a>
    </nav>
</header>
