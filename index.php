<?php require("functions.php");  head_top_menu(); ?>
<div class="main-container">
    <main>
        <section class="hero">
            <div class="hero-copy">
                <h1>Handmade with Heart</h1>
                <p>Artisanal jewelry crafted with care, inspired by nature, and made to be uniquely yours.</p>
                <div class="hero-actions">
                    <a class="button button-dark" href="collections.php">
                        <div class="layer" data-text="Shop the Collection">Shop the Collection</div>
                    </a>
                    <a class="button button-light" href="#">Explore New Arrivals</a>
                </div>
            </div>
        </section>

        <section class="features" aria-label="Shopping highlights">
            <article class="feature">
                <span class="feature-icon leaf" aria-hidden="true"></span>
                <h2>Handmade</h2>
                <p>Every piece is thoughtfully crafted by hand.</p>
            </article>
            <article class="feature">
                <span class="feature-icon diamond" aria-hidden="true"></span>
                <h2>Natural Materials</h2>
                <p>We use high-quality gemstones and eco-conscious materials.</p>
            </article>
            <article class="feature">
                <span class="feature-icon heart" aria-hidden="true"></span>
                <h2>One of a Kind</h2>
                <p>Unique designs made in small batches.</p>
            </article>
            <article class="feature">
                <span class="feature-icon gift" aria-hidden="true"></span>
                <h2>Perfect for Gifting</h2>
                <p>Handmade, one-of-a-kind gifts.</p>
            </article>
        </section>

        <section class="collections">
            <div class="section-heading">
                <h2>Shop by Collection</h2>
                <a href="collections.php">View All Collections</a>
            </div>

            <div class="collection-grid">
                <article class="collection-card">
                    <img src="images/collections-card-chimes.png"
                        alt="A handmade wind chime with beads and metal accents">
                    <div>
                        <h3>Wind Chimes</h3>
                        <a class="button button-light" href="#">Shop now</a>
                    </div>
                </article>
                <article class="collection-card">
                    <img src="images/collections-card-rings.webp" alt="A handmade button ring on a dark background">
                    <div>
                        <h3>Button Rings</h3>
                        <a class="button button-light" href="#">Shop now</a>
                    </div>
                </article>

                <article class="collection-card">
                    <img src="images/collections-card-necklaces.webp" alt="Necklaces">
                    <div>
                        <h3>Necklaces</h3>
                        <a class="button button-light" href="#">Shop now</a>
                    </div>
                </article>
                <article class="collection-card">
                    <img src="images/collections-card-new.webp" alt="New Arivals">
                    <div>
                        <h3>New Arrivals</h3>
                        <a class="button button-light" href="#">Shop now</a>
                    </div>
                </article>
            </div>
        </section>

        <section class="story">
            <div class="faded-filter-overlay"></div>
            <div class="story-copy">
                <h2>Handmade with Heart</h2>
                <p>Each piece in Karen's Jewelry Box is handcrafted in small batches using natural stones, crystals, and
                    metals. My jewelry is inspired by nature, designed to bring beauty, meaning, and joy to your
                    everyday.</p>
                <a class="button button-dark" href="#">
                    <div class="layer" data-text="More About Karen">More About Karen</div>
                </a>
            </div>
        </section>

        <section class="testimonial" aria-label="Customer testimonial">
            <blockquote>The necklace I ordered is even more beautiful in person. You can feel the love and care in every
                detail.</blockquote>
            <p><span aria-label="5 stars">★★★★★</span> - Jessica M.</p>
        </section>
    </main>

    <?php footer(); ?>
    <?php flush(); ?>