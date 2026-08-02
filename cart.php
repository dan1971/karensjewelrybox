<?php require("functions.php");  head_top_menu(); ?>
<div class="main-container">
    <main>
        <section class="collections">
            <div class="section-heading">
                <h2>Shopping Cart</h2>
            </div>

        <main class="cart-main">
            <div class="cart-container">
                <h1>Shopping Cart</h1>
                
                <div class="cart-content">
                    <!-- Cart Items Section -->
                    <div class="cart-items">
                        <!-- Cart Item 1 -->
                        <div class="cart-item">
                            <div class="cart-item-image">
                                <img src="images/Chime-001.webp" alt="Chime Magic">
                            </div>
                            <div class="cart-item-details">
                                <h3>Chime Magic</h3>
                                <p class="cart-item-description">Handcrafted windchime with glass beads</p>
                                <p class="cart-item-price">$79.00</p>
                            </div>
                            <div class="cart-item-quantity">
                                <label for="qty-1">Qty:</label>
                                <input type="number" id="qty-1" value="1" min="1" max="99">
                            </div>
                            <div class="cart-item-total">
                                <p>$79.00</p>
                            </div>
                            <button class="cart-item-remove" aria-label="Remove item">×</button>
                        </div>

                        <!-- Cart Item 2 -->
                        <div class="cart-item">
                            <div class="cart-item-image">
                                <img src="images/Chime-002.webp" alt="Chime Love">
                            </div>
                            <div class="cart-item-details">
                                <h3>Chime Love</h3>
                                <p class="cart-item-description">Whimsical design with copper accents</p>
                                <p class="cart-item-price">$115.00</p>
                            </div>
                            <div class="cart-item-quantity">
                                <label for="qty-2">Qty:</label>
                                <input type="number" id="qty-2" value="2" min="1" max="99">
                            </div>
                            <div class="cart-item-total">
                                <p>$230.00</p>
                            </div>
                            <button class="cart-item-remove" aria-label="Remove item">×</button>
                        </div>

                        <!-- Empty Cart Message (hidden when items exist) -->
                        <div class="cart-empty" style="display: none;">
                            <p>Your cart is empty</p>
                            <a href="index.html" class="continue-shopping-btn">Continue Shopping</a>
                        </div>
                    </div>

                    <!-- Cart Summary Section -->
                    <div class="cart-summary">
                        <h2>Order Summary</h2>
                        
                        <div class="cart-summary-line">
                            <span>Subtotal</span>
                            <span>$309.00</span>
                        </div>
                        
                        <div class="cart-summary-line">
                            <span>Shipping</span>
                            <span>$12.00</span>
                        </div>
                        
                        <div class="cart-summary-line">
                            <span>Tax</span>
                            <span>$24.72</span>
                        </div>
                        
                        <div class="cart-summary-divider"></div>
                        
                        <div class="cart-summary-line cart-summary-total">
                            <span>Total</span>
                            <span>$345.72</span>
                        </div>

                        <button class="checkout-btn">Proceed to Checkout</button>
                        
                        <a href="index.html" class="continue-shopping-link">← Continue Shopping</a>
                        
                        <div class="promo-code">
                            <input type="text" placeholder="Enter promo code" id="promo-input">
                            <button class="apply-promo-btn">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>

<?php footer(); ?>
<?php flush(); ?>