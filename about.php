<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
$pageTitle = 'A school with room to grow';
$activePage = 'about';
require __DIR__ . '/includes/header.php';
?>
<main>
    <section class="page-hero">
        <span class="eyebrow"><span class="eyebrow-dot"></span> OUR SCHOOL, OUR STORY</span>
        <h1>A school that sees<br><span>who you could be.</span></h1>
        <p>We believe a great education gives children more than answers. It gives them the curiosity, courage, and kindness to ask better questions.</p>
        <img class="page-hero-image" src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=1800&q=85" alt="Learners sharing ideas in class">
    </section>
    <section class="story-section section-wrap"><div><span class="eyebrow">A LITTLE ABOUT US</span><h2>Built around<br><span>every learner.</span></h2></div><div class="story-copy"><p>Attqwa College is a welcoming school community for children from their earliest years through senior secondary. We combine ambitious learning with the patient, personal support that helps young people feel safe enough to stretch.</p><p>Our teachers know their students, celebrate what makes each one different, and create space for every voice. That's how we turn a school day into a foundation for life.</p><a class="text-link" href="contact.php">Come meet our community <span aria-hidden="true">→</span></a></div></section>
    <section class="values-section" id="learning"><div class="section-wrap"><span class="eyebrow">WHAT GUIDES US</span><h2>Learning with <span>heart.</span></h2><div class="value-grid"><article><span>01 / WONDER</span><h3>Stay curious.</h3><p>Questions are welcome here. We help every child follow their curiosity and discover the joy of figuring things out.</p></article><article><span>02 / BELONGING</span><h3>Make room.</h3><p>Every learner deserves to feel seen, supported, and included. We build a community where people can be themselves.</p></article><article><span>03 / COURAGE</span><h3>Keep growing.</h3><p>With thoughtful challenges and caring guidance, children learn to take a chance, learn from it, and try again.</p></article></div></div></section>
    <section class="journey-section section-wrap"><div><span class="eyebrow">A PLACE FOR EVERY NEXT STEP</span><h2>Growing, together.</h2></div><div class="journey-grid"><?php foreach (['Early Years' => 'A warm first step into a world of play, friendships, and discovery.', 'Primary' => 'Strong foundations and the confidence to ask big questions.', 'Junior Secondary' => 'New ideas and growing independence, with caring support close by.', 'Senior Secondary' => 'Purposeful preparation for further study and a world of possibilities.'] as $stage => $description): ?><article><span class="journey-flower">✳</span><h3><?= escape($stage) ?></h3><p><?= escape($description) ?></p></article><?php endforeach; ?></div></section>
    <section class="closing-cta"><div><span class="eyebrow">COME SEE FOR YOURSELF</span><h2>There's a place<br>for <span>your family here.</span></h2></div><a class="button button-light" href="contact.php">Get in touch <span aria-hidden="true">↗</span></a><div class="cta-sun" aria-hidden="true">✳</div></section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
