<?php require("functions.php");  head_top_menu(); ?>
<div class="main-container">
    <main>
        <section class="collections">
            <div class="section-heading">
                <h2>Shopping Cart</h2>
            </div>

        <main class="cart-main">
            <div class="cart-container">
                <div class="cart-content">
                    <!-- Cart Items Section -->
                    <div class="cart-items">
            

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
                            <span class="checkOut_subtotal"></span>
                        </div>
                        
                        <div class="cart-summary-line">
                            <span>Shipping</span>
                            <span class="checkOut_shipping"></span>
                        </div>
                        
                        <div class="cart-summary-line">
                            <span>Tax</span>
                            <span class="checkOut_tax"></span>
                        </div>
                        
                        <div class="cart-summary-divider"></div>
                        
                        <div class="cart-summary-line cart-summary-total">
                            <span>Total</span>
                            <span class="checkOut_total" data-total=""></span>
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