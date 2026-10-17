<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
$pageTitle = 'A brighter beginning starts here';
$activePage = 'home';
require __DIR__ . '/includes/header.php';
?>
<main>
    <section class="hero">
        <div class="hero-copy">
            <span class="eyebrow"><span class="eyebrow-dot"></span> LEARN WITH PURPOSE. GROW WITH CONFIDENCE.</span>
            <h1>Big dreams start<br>with a <span>brighter</span><br>beginning.</h1>
            <p>A curious mind, a kind heart, and the confidence to shape what comes next. Welcome to a school where every possibility has room to grow.</p>
            <div class="hero-actions">
                <a class="button button-dark" href="about.php">Discover our school <span aria-hidden="true">↗</span></a>
                <a class="text-link" href="portal.php">School portal <span aria-hidden="true">→</span></a>
            </div>
            <div class="hero-proof"><div class="avatar-stack" aria-hidden="true"><span>A</span><span>M</span><span>J</span><span>+</span></div><p><strong>A community that cares.</strong><br>For every learner, every day.</p></div>
        </div>
        <div class="hero-art">
            <div class="hero-photo" role="img" aria-label="Students learning together in a bright classroom"></div>
            <div class="floating-note"><span class="note-icon">✳</span><span><strong>Curiosity lives here</strong><br>Since 1998</span></div>
            <div class="hero-sticker">A place to<br><span>become.</span></div>
            <span class="art-spark spark-one">✳</span><span class="art-spark spark-two">✳</span>
        </div>
        <div class="hero-caption"><span>01 / 03</span><span>THOUGHTFUL LEARNING, MEANINGFUL FUTURES</span></div>
    </section>

    <section class="intro-section section-wrap">
        <div><span class="eyebrow">MORE THAN A SCHOOL</span><h2>Growing good people<br>and <span>great ideas.</span></h2></div>
        <div class="intro-copy"><p>At Attqwa College, learning feels like possibility. We pair thoughtful teaching with a warm, welcoming community, so children can find what excites them and build the confidence to follow it.</p><a class="text-link" href="about.php">Get to know us <span aria-hidden="true">→</span></a></div>
    </section>

    <section class="experience-section">
        <div class="section-wrap experience-heading"><div><span class="eyebrow">A DAY AT ATTQWA COLLEGE</span><h2>Room to learn.<br><span>Space to flourish.</span></h2></div><p>Every day brings something new to wonder about, make, explore, and share.</p></div>
        <div class="image-grid">
            <article class="image-card image-card-tall"><img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1100&q=85" alt="Students engaged in a bright, welcoming classroom" loading="lazy"><div class="image-label"><span>01</span><strong>Learning that comes alive</strong></div></article>
            <article class="image-card image-card-short"><img src="https://images.unsplash.com/photo-1472162072942-cd5147eb3902?auto=format&fit=crop&w=1000&q=85" alt="Children discovering the outdoors together" loading="lazy"><div class="image-label"><span>02</span><strong>Confidence to explore</strong></div></article>
            <article class="image-card image-card-tall image-card-offset"><img src="https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?auto=format&fit=crop&w=1000&q=85" alt="Young learner enjoying time outdoors" loading="lazy"><div class="image-label"><span>03</span><strong>Friendships that take root</strong></div></article>
        </div>
    </section>

    <section class="pathways section-wrap">
        <div><span class="eyebrow">EVERY AGE, EVERY NEXT STEP</span><h2>A little different<br>at every <span>stage.</span></h2><p>Small classes, caring teachers, and a learning journey built around who your child is—and who they could become.</p><a class="button button-outline" href="about.php">Explore the journey <span aria-hidden="true">↗</span></a></div>
        <div class="pathway-list">
            <a href="about.php#learning"><span class="pathway-number">01</span><span><strong>Early Years</strong><small>Where wonder gets its start</small></span><span class="pathway-arrow">↗</span></a>
            <a href="about.php#learning"><span class="pathway-number">02</span><span><strong>Primary</strong><small>Curiosity finds its footing</small></span><span class="pathway-arrow">↗</span></a>
            <a href="about.php#learning"><span class="pathway-number">03</span><span><strong>Junior Secondary</strong><small>New voices, new horizons</small></span><span class="pathway-arrow">↗</span></a>
            <a href="about.php#learning"><span class="pathway-number">04</span><span><strong>Senior Secondary</strong><small>Ready for what comes next</small></span><span class="pathway-arrow">↗</span></a>
        </div>
    </section>

    <section class="quote-band"><div class="quote-mark">“</div><blockquote>They see the person my child is becoming, not just the pupil sitting in class.</blockquote><p>— An Attqwa College parent</p><span class="quote-flower">✳</span></section>

    <section class="closing-cta"><div><span class="eyebrow">YOUR NEXT CHAPTER STARTS HERE</span><h2>Come see what<br>they <span>could become.</span></h2></div><a class="button button-light" href="contact.php">Plan a visit <span aria-hidden="true">↗</span></a><div class="cta-sun" aria-hidden="true">✳</div></section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
