<?php function head_top_menu() { ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karen's Jewelry Box</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Delius+Swash+Caps&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/normalize.css">
</head>

<body>
    <header class="site-header">
        <div class="header-logo-div">
            <div class="logo-sup">
                <h1>Karen's Jewelry</h1>
            </div>
            <div class="logo-sub">
                <h1>B<sup>ox</sup></h1>
            </div>
        </div>

        <nav class="main-nav links" aria-label="Main navigation">
            <div class="layer" data-text="Shop"><a href="#">Shop</a></div>
            <div class="layer" data-text="New"><a href="#">New</a></div>
            <div class="layer" data-text="Gift"><a href="#">Gift</a></div>
            <div class="layer" data-text="Collections"><a href="#">Collections</a></div>
            <div class="layer" data-text="About"><a href="#">About</a></div>
        </nav>
        <div class="cart-icons">
            <a class="cart-link links" href="#" aria-label="View cart"></a>
            <!-- Removed SEARCH menu item -->
            <!-- <a href="#"><span class="icon-account"></span>ACCOUNT</a> -->
            <a href="#">
                <svg id="cart-icon" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 100 100">
                    <path class="st1" class="cart-path" fill-rule="evenodd" clip-rule="evenodd"
                        d="M86.6,8v45.4H27.3L15.7,8h70.9M94.6,0H5.4l15.7,61.4h73.5V0h0Z" />
                    <g id="Layer_7">
                        <line class="st1" fill-rule="evenodd" clip-rule="evenodd" x1="41.7" y1="6.6" x2="41.7"
                            y2="58.7" />
                        <line class="st1" fill-rule="evenodd" clip-rule="evenodd" x1="67" y1="4.7" x2="67" y2="56.7" />
                        <line class="st1" fill-rule="evenodd" clip-rule="evenodd" x1="88.3" y1="38.2" x2="18.2"
                            y2="38.2" />
                        <line class="st1" fill-rule="evenodd" clip-rule="evenodd" x1="88.3" y1="21.1" x2="18.2"
                            y2="21.1" />
                        <circle class="st1" cx="31.6" cy="85.5" r="7.6" />
                        <circle class="st1" cx="73.1" cy="85.5" r="7.6" />
                        <line class="st1" fill-rule="evenodd" clip-rule="evenodd" x1="39.3" y1="85.5" x2="67"
                            y2="85.5" />
                        <path class="st1" fill-rule="evenodd" clip-rule="evenodd"
                            d="M79,85.5c9.1-1.3,12.7-10.9,11.9-26.8" />
                    </g>
                </svg>CART
            </a>
        </div>
        <!-- <span aria-hidden="true">Cart</span> -->
    </header>
    <?php } ?>
    <?php function footer() { ?>
    <section class="footer-badges" aria-label="Store benefits">
        <div class="badge truck">
            <strong>Free Shipping</strong>
            <span>On orders over $75</span>
        </div>
        <div class="badge lock">
            <strong>Secure Checkout</strong>
            <span>Safe and encrypted payments</span>
        </div>
        <div class="badge plant">
            <strong>Support Small Business</strong>
            <span>Thank you for supporting handmade</span>
        </div>
    </section>
    </div>
    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-content">
            <div class="footer-logo">
                <!-- Optional: Add your logo here -->
                <!-- <img src="./images/logo.png" alt="KAREN Jewelry Logo"> -->

            </div>
            <div class="footer-links">
                <a href="#">SHOP</a>
                <a href="#">NEW</a>
                <a href="#">GIFTS</a>
                <a href="#">COLLECTIONS</a>
                <a href="#">ABOUT ME</a>
            </div>
            <div class="footer-social">
                <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" aria-label="Pinterest"><i class="fab fa-pinterest"></i></a>
            </div>
            <div class="footer-logo">
                <h1>Jewelry <span class="by">by</span> Karen</h1>
                <div class="footer-copy">&copy; 2026 All rights reserved.</div>
            </div>

        </div>
    </footer>

    <script src="JS/danJS.js"></script> -->
</body>

</html>
<!-- footer end -->
<?php } ?>
<?php flush(); ?>