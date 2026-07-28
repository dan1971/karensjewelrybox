<?php require("functions.php");  head_top_menu(); ?>

<div class="main-container">
    <main class="checkout-page">
        <section class="section-heading">
            <h2>Checkout</h2>
        </section>

        <div class="checkout-layout">
            <div class="checkout-left">
                <div class="checkout-thumbs" aria-label="Product thumbnails">
                    <button class="thumb-item" type="button"><img src="images/chime-001.webp" alt="Golden Windstone wind chime"></button>
                    <button class="thumb-item" type="button"><img src="images/collections-card-chimes.png" alt="Wind chime detail"></button>
                    <button class="thumb-item" type="button"><img src="images/collections-card-new.webp" alt="Alternate product view"></button>
                    <button class="thumb-item" type="button"><img src="images/collections-card-rings.webp" alt="Related product option"></button>
                </div>

                <div class="checkout-main-photo">
                    <img src="images/chime-001.webp" alt="Golden Windstone wind chime">
                </div>
            </div>

            <section class="checkout-details">
                <p class="product-category">Wind Chimes</p>
                <h1 class="product-title">Golden Windstone</h1>
                <p class="product-description">A bestselling wind chime finished in warm gold tones, perfect for adding gentle sound and elegant movement to any outdoor space.</p>

                <div class="product-info-summary">
                    <p><strong>Unit Price:</strong> <span id="unit-price">$155</span> <small class="original-price">$119</small></p>
                    <p><strong>Total:</strong> <span id="order-total">$155</span></p>
                    <p>Get Fast, Free Shipping with Amazon Prime • FREE Returns</p>
                    <p><strong>Collection:</strong> Wind Chimes</p>
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
            </section>

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
    </main>
</div>

<?php footer(); ?>
<?php flush(); ?>