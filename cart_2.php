<?php function head_top_menu() { ?>

    <!-- Cart Info Widget -->
    <div class="cart-status">
        Cart Items: <span id="cart-count">0</span> | Total: $<span id="cart-total">0.00</span>
    </div>

    <!-- Product Grid Example -->
    <div class="product-card">
        <h3>Premium Widget</h3>
        <p>$19.99</p>
        <button class="add-to-cart-btn" data-product-id="101" data-quantity="1">Add to Cart</button>
    </div>

    <div class="product-card">
        <h3>Super Gadget</h3>
        <p>$29.99</p>
        <button class="add-to-cart-btn" data-product-id="102" data-quantity="1">Add to Cart</button>
    </div>

<?php footer(); ?>
<?php flush(); ?>
