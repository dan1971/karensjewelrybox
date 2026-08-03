<?php require("functions.php");  head_top_menu(); ?>

<div class="main-container">
    <main class="checkout-page">
        <section class="section-heading">
            <h2>Checkout</h2>
        </section>

        <div class="checkout-layout">
            <div class="checkout-left">

                <div class="checkout-main-photo">
                    <img id="checkout-main-photo-image" src="images/chime-001.webp" alt="Golden Windstone wind chime">
                </div>
                <div class="thumb-gallery">
                    <button class="thumb-nav thumb-nav-left" type="button" aria-label="Show previous thumbnails">&#10094;</button>

                    <div class="checkout-thumbs" aria-label="Product thumbnails">
                        <button class="thumb-item is-active" type="button" data-image="images/chime-001.webp" data-alt="Golden Windstone wind chime"><img src="images/chime-001.webp" alt="Golden Windstone wind chime"></button>
                        <button class="thumb-item" type="button" data-image="images/collections-card-chimes.png" data-alt="Wind chime detail"><img src="images/collections-card-chimes.png" alt="Wind chime detail"></button>
                        <button class="thumb-item" type="button" data-image="images/collections-card-new.webp" data-alt="Alternate product view"><img src="images/collections-card-new.webp" alt="Alternate product view"></button>
                        <button class="thumb-item" type="button" data-image="images/collections-card-rings.webp" data-alt="Related product option"><img src="images/collections-card-rings.webp" alt="Related product option"></button>
                    </div>

                    <button class="thumb-nav thumb-nav-right" type="button" aria-label="Show next thumbnails">&#10095;</button>
                </div>
                                <div class="product-features">
                    <h3>About this item</h3>
                    <ul>
                        <li>Bestseller in the Wind Chimes collection with a rich Golden Windstone finish.</li>
                        <li>Handcrafted design that offers soothing chime tones for outdoor living spaces.</li>
                        <li>High-quality construction with careful attention to sound and durability.</li>
                        <li>Perfect gift for home decor, patio, garden, or porch accents.</li>
                        <li>Highly rated item with 57 reviews from happy customers.</li>
                    </ul>
                </div>
            </div>

            <div class="checkout-details">
                <p class="product-category">Wind Chimes</p>
                <h1 class="product-title">Golden Windstone</h1>
                <p class="product-description">A bestselling wind chime finished in warm gold tones, perfect for adding gentle sound and elegant movement to any outdoor space.</p>

                <div class="product-info-summary">
                    <p><strong>Unit Price:</strong> <span id="unit-price">$155</span> <small class="original-price">$119</small></p>
                    <p><strong>Total:</strong> <span id="order-total">$155</span></p>
                    <p>Get Fast, Free Shipping with Amazon Prime • FREE Returns</p>
                    <p><strong>Collection:</strong> Wind Chimes</p>
                </div>



                <aside class="checkout-summary">
                    <div class="order-card" id="order-card" data-unit-price="155" data-product-name="Golden Windstone">
                        <div class="order-head">
                            <div class="order-price">
                                <span class="order-label">Price:</span>
                                <strong>$155</strong>
                            </div>
                            <div class="order-savings">Was $119</div>
                        </div>

                        <div class="order-status">
                            <p class="order-delivery">FREE delivery Friday, December 29</p>
                            <p class="order-arrives">Arrives after Christmas. Need a gift sooner? Send an Amazon Gift Card instantly by email or text message.</p>
                        </div>

                        <div class="order-stock">In Stock</div>

                        <div class="order-controls">
                            <label for="quantity">Qty:</label>
                            <select id="quantity" name="quantity">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>

                        <div class="checkout-actions">
                            <a class="button button-dark-layer" href="#" data-product="Golden Windstone" data-price="155" id="add-to-cart-button">Add to Cart</a>
                            <a class="button button-light" href="#payment-section" data-product="Golden Windstone" data-price="155" id="buy-now-button">Buy Now</a>
                        </div>

                        <div class="payment-section" id="payment-section" data-square-app-id="REPLACE_WITH_SQUARE_APP_ID" data-square-location-id="REPLACE_WITH_LOCATION_ID">
                            <h3>Pay with Square</h3>
                            <div id="card-container"></div>
                            <button id="pay-button" class="button button-dark-layer" type="button">Pay $<span id="pay-amount">155</span></button>
                            <div id="payment-status" class="payment-status"></div>
                        </div>

                        <div class="order-meta">
                            <div><strong>Ships from</strong> Amazon</div>
                            <div><strong>Sold by</strong> Héstia Store</div>
                            <div><strong>Returns</strong> Returnable until Jan 31, 2024</div>
                            <div><strong>Payment</strong> Secure transaction</div>
                        </div>

                        <label class="gift-option">
                            <input type="checkbox" name="gift-receipt">
                            Add a gift receipt for easy returns
                        </label>
                    </div>
                </aside>
            </div>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const gallery = document.querySelector('.thumb-gallery');
    if (!gallery) return;

    const track = gallery.querySelector('.checkout-thumbs');
    const thumbs = Array.from(gallery.querySelectorAll('.thumb-item'));
    const prevButton = gallery.querySelector('.thumb-nav-left');
    const nextButton = gallery.querySelector('.thumb-nav-right');
    const mainImage = document.getElementById('checkout-main-photo-image');

    if (!track || !thumbs.length || !mainImage) return;

    const setActiveThumb = (activeThumb) => {
        thumbs.forEach((thumb) => thumb.classList.toggle('is-active', thumb === activeThumb));
        if (activeThumb) {
            const imageSrc = activeThumb.dataset.image || activeThumb.querySelector('img')?.getAttribute('src');
            const imageAlt = activeThumb.dataset.alt || activeThumb.querySelector('img')?.getAttribute('alt') || '';
            mainImage.setAttribute('src', imageSrc);
            mainImage.setAttribute('alt', imageAlt);
        }
    };

    thumbs.forEach((thumb) => {
        thumb.addEventListener('click', () => setActiveThumb(thumb));
    });

    const scrollToThumb = (direction) => {
        const currentIndex = thumbs.findIndex((thumb) => thumb.classList.contains('is-active'));
        const nextIndex = direction === 'next'
            ? Math.min(currentIndex + 1, thumbs.length - 1)
            : Math.max(currentIndex - 1, 0);

        const nextThumb = thumbs[nextIndex];
        setActiveThumb(nextThumb);
        nextThumb.scrollIntoView({ behavior: 'smooth', inline: 'start', block: 'nearest' });
    };

    prevButton?.addEventListener('click', () => scrollToThumb('prev'));
    nextButton?.addEventListener('click', () => scrollToThumb('next'));
});
</script>

<?php footer(); ?>
<?php flush(); ?>