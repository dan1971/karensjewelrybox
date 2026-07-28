<?php require("functions.php");  head_top_menu(); ?>

<div class="main-container">
    <main class="checkout-page">
        <section class="section-heading">
            <h2>Checkout</h2>
        </section>

        <div class="checkout-layout">
            <div class="checkout-left">
                <div class="checkout-thumbs" aria-label="Product thumbnails">
                    <button class="thumb-item" type="button"><img src="images/sample.jpg" alt="Watch color option"></button>
                    <button class="thumb-item" type="button"><img src="images/collections-card-new.webp" alt="Accessory option"></button>
                    <button class="thumb-item" type="button"><img src="images/collections-card-rings.webp" alt="Style option"></button>
                    <button class="thumb-item" type="button"><img src="images/collections-card-chimes.png" alt="Product detail"></button>
                </div>

                <div class="checkout-main-photo">
                    <img src="images/sample.jpg" alt="Classic leather strap watch">
                </div>
            </div>

            <section class="checkout-details">
                <p class="product-category">Unisex Fashion Wrist Watch</p>
                <h1 class="product-title">Classic Leather Strap Watch</h1>
                <p class="product-description">Ultra-thin 6.7 mm dial with rounded black case, minimalist style, and a soft leather strap built for everyday wear.</p>

                <div class="product-info-summary">
                    <p><strong>List Price:</strong> <span>$32.99</span></p>
                    <p>Get Fast, Free Shipping with Amazon Prime • FREE Returns</p>
                    <p><strong>Color:</strong> 01-OrangeBlue</p>
                </div>

                <div class="product-features">
                    <h3>About this item</h3>
                    <ul>
                        <li>Ultra-thin 6.7 mm thickness dial with rounded black case and minimalist style.</li>
                        <li>Classic black dial with rose gold markers and a small date window.</li>
                        <li>Soft leather strap with buckle that is easy to adjust and remove.</li>
                        <li>Daily water-resistant design for splash and rain protection.</li>
                        <li>Exclusive double-hand layout for a clear, elegant reading experience.</li>
                    </ul>
                </div>
            </section>

            <aside class="checkout-summary">
                <div class="order-card">
                    <div class="order-head">
                        <div class="order-price">
                            <span class="order-label">Price:</span>
                            <strong>$32.99</strong>
                        </div>
                        <div class="order-savings">Save 12%</div>
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
                        <a class="button button-dark-layer" href="#">Add to Cart</a>
                        <a class="button button-light" href="#">Buy Now</a>
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