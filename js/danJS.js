

// const words = ["Sugar", "Buddy", "Chimera", "Shrimps", "Lollypop", "Bubbles", "Twinkle", "Sparkle", "Doodle", "Noodle", "Wiggle", "Jellybean"];
// const el = document.querySelector(".product-grid");

// for (const child of el.children) {
//     const randomWord = words[Math.floor(Math.random() * words.length)];
//     let wordPulledIndx = words.indexOf(randomWord);
//     if(wordPulledIndx > -1) {
//         words.splice(wordPulledIndx, 1);
//     }
//     child.setAttribute("data-word", randomWord);
//     console.log("Assigned word " + randomWord + " to product card.");
// }

const storageKeys = {
    cart: 'kjb_cart',
    wishlist: 'kjb_wishlist'
};

const selectors = {
    cartButton: '#cart-button',
    cartIconsContainer: '.cart-icons',
    cartbadge: '#cart-badge',
    cartOverlay: '#cart-modal-overlay',
    paySection: '#payment-section',
    quantity: '#quantity',
    orderTotal: '#order-total',
    orderDisplayPrice: '#order-display-price',
    payAmount: '#pay-amount',
    addToCartButtons: '[data-imagePath][data-product][data-price]',
    productCards: '.product-card'
};
// const element = document.querySelector(selectors.cartbadge);
// const opacity = window.getComputedStyle(element).opacity;
// console.log('Selectors:', opacity);

window.addEventListener('DOMContentLoaded', () => {
    initializeCartState();
    initializeWishlistState();
    setupGlobalInteractions();
    setupPurchasePage();
    setupHeaderScroll();
});

function setupHeaderScroll() {
    updateHeaderShrink();
    window.addEventListener('scroll', updateHeaderShrink, { passive: true });
}

function updateHeaderShrink() {
    const header = document.querySelector('.site-header');
    if (!header) return;
    if (window.scrollY > 24) {
        header.classList.add('shrink');
    } else {
        header.classList.remove('shrink');
    }
}

function getStoredData(key) {
    return JSON.parse(localStorage.getItem(key) || 'null');
}

function setStoredData(key, value) {
    localStorage.setItem(key, JSON.stringify(value));
}

function getCart() {
    return getStoredData(storageKeys.cart) || [];
}

function setCart(cart) {
    setStoredData(storageKeys.cart, cart);
}

function getWishlist() {
    return getStoredData(storageKeys.wishlist) || [];
}

function setWishlist(wishlist) {
    setStoredData(storageKeys.wishlist, wishlist);
}

function initializeCartState() {
    // createCartBadge();
    renderCartBadge();
    createCartModal();
}

// function createCartBadge() {
//     const cartBadge = document.querySelector(selectors.cartBadge);
//     const cartBadgeActive = cartBadge?.classList.contains('active');
//     if (!cartBadgeActive) return;

//     if (cartBadgeActive) {
//         cartBadge.setAttribute('opacity', '1');
//     }
// }

function renderCartBadge() {
    const badge = document.querySelector(selectors.cartbadge);
    const cartBadgeActive = badge?.classList.contains('active');
    const itemCount = getCart().reduce((sum, item) => sum + item.quantity, 0);
    console.log('Cart item count:', itemCount + ' | Badge active:', cartBadgeActive);
    if (itemCount > 0) {
        badge.classList.add('active');

    } else {
        badge.classList.remove('active');
    }
}

function createCartModal() {
    if (document.getElementById('cart-modal-overlay')) return;

    const overlay = document.createElement('div');
    overlay.id = 'cart-modal-overlay';
    overlay.className = 'cart-overlay';
    overlay.innerHTML = `
        <div class="cart-modal" role="dialog" aria-modal="true" aria-labelledby="cart-modal-title">
            <button class="cart-close" type="button" aria-label="Close cart">×</button>
            <h2 id="cart-modal-title">Your Cart</h2>
            <div class="cart-items"></div>
            <div class="cart-total"></div>
            <div class="cart-actions">
                <button class="button button-light" type="button" id="cart-close-button">Close</button>
            </div>
        </div>
    `;
    document.body.appendChild(overlay);
    overlay.addEventListener('click', (event) => {
        if (event.target === overlay || event.target.closest('.cart-close')) {
            toggleCartModal(false);
        }
    });
}

function toggleCartModal(show = true) {
    const overlay = document.getElementById('cart-modal-overlay');
    if (!overlay) return;
    overlay.classList.toggle('active', show);
    if (show) {
        renderCartModal();
    }
}

function renderCartModal() {
    const overlay = document.getElementById('cart-modal-overlay');
    if (!overlay) return;
    const cartItems = overlay.querySelector('.cart-items');
    const cartTotal = overlay.querySelector('.cart-total');
    const cart = getCart();
    if (!cart.length) {
        cartItems.innerHTML = '<p>Your cart is empty.</p>';
        cartTotal.textContent = '';
        return;
    }

    cartItems.innerHTML = cart.map((item, index) => {
        return `
            <div class="cart-item" data-item-index="${index}">
                <div>
                    <div><img src="${item.imagePath}" alt="${item.product}" style="max-width: 100px; height: auto;"></div>
                    <div><strong>${item.product}</strong></div>
                    <div class="cart-item-quantity">Qty: ${item.quantity}</div>
                    <div>$${(item.price * item.quantity).toFixed(2)}</div>
                </div>
                <button class="cart-item-remove" type="button" data-item-index="${index}">Remove</button>
            </div>
        `;
    }).join('');

    const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    cartTotal.textContent = `Total: $${total.toFixed(2)}`;
}

function setupGlobalInteractions() {
    document.body.addEventListener('click', (event) => {
        const addButton = event.target.closest('[data-imagePath][data-product][data-price]');
        console.log('Clicked add to cart button:', addButton);
        if (addButton) {
            const text = addButton.textContent.trim().toLowerCase();
            const imagePath = addButton.dataset.imagePath;
            const product = addButton.dataset.product;
            const price = Number(addButton.dataset.price);
            if (product && price) {
                event.preventDefault();
                const quantitySelect = document.querySelector(selectors.quantity);
                const quantity = quantitySelect ? Number(quantitySelect.value) : 1;
                if (text.includes('add to cart')) {
                    addToCart(imagePath, product, price, quantity);
                    return;
                }
                if (text.includes('buy now')) {
                    addToCart(imagePath, product, price, quantity);
                    const paymentSection = document.getElementById('payment-section');
                    if (paymentSection) {
                        updatePurchaseTotal();
                        paymentSection.scrollIntoView({ behavior: 'smooth' });
                        showToast('Ready to pay with Square.');
                    }
                    return;
                }
            }
        }

        if (event.target.closest('.cart-item-remove')) {
            const removeButton = event.target.closest('.cart-item-remove');
            const index = Number(removeButton.dataset.itemIndex);
            removeCartItem(index);
            return;
        }
    });

    const cartButton = document.querySelector(selectors.cartButton);
    if (cartButton) {
        cartButton.addEventListener('click', (event) => {
            event.preventDefault();
            toggleCartModal(true);
        });
    }
}

function setupPurchasePage() {
    initializeWishlistState();
    updatePurchaseTotal();

    const quantitySelect = document.querySelector(selectors.quantity);
    if (quantitySelect) {
        quantitySelect.addEventListener('change', () => {
            updatePurchaseTotal();
        });
    }

    const paymentSection = document.querySelector(selectors.paySection);
    if (paymentSection) {
        loadSquareSdk().then(initializeSquarePayment).catch((error) => {
            console.warn('Square SDK could not load:', error);
            setPaymentStatus('Unable to load Square Payments. Make sure you are online and your script is available.');
        });
    }
}

function updatePurchaseTotal() {
    const orderCard = document.getElementById('order-card');
    if (!orderCard) return;
    const unitPrice = Number(orderCard.dataset.unitPrice) || 0;
    const quantitySelect = document.querySelector(selectors.quantity);
    const quantity = quantitySelect ? Number(quantitySelect.value) : 1;
    const total = unitPrice * quantity;
    const orderTotal = document.querySelector(selectors.orderTotal);
    const payAmount = document.querySelector(selectors.payAmount);
    const displayPrice = document.querySelector(selectors.orderDisplayPrice);
    if (orderTotal) {
        orderTotal.textContent = `$${total.toFixed(2)}`;
    }
    if (payAmount) {
        payAmount.textContent = total.toFixed(2);
    }
    if (displayPrice) {
        displayPrice.textContent = `$${total.toFixed(2)}`;
    }
}

function addToCart(imagePath, product, price, quantity = 1) {
    const cart = getCart();
    const existing = cart.find((item) => item.product === product && item.price === price);
    if (existing) {
        existing.quantity += quantity;
    } else {
        cart.push({ product, price, quantity });
    }
    setCart(cart);
    renderCartBadge();
    renderCartModal();
    showToast(`${product} added to cart`);
}

function removeCartItem(index) {
    const cart = getCart();
    if (index >= 0 && index < cart.length) {
        cart.splice(index, 1);
        setCart(cart);
        renderCartBadge();
        renderCartModal();
    }
}

function clearCart() {
    setCart([]);
    renderCartBadge();
    renderCartModal();
}

function initializeWishlistState() {
    const wishlist = getWishlist();
    document.querySelectorAll('.wishheart').forEach((path) => {
        const productCard = path.closest('.product-card');
        const productName = productCard?.querySelector('.product-name')?.textContent?.trim();
        if (productName && wishlist.includes(productName)) {
            path.classList.add('active');
        }
    });

    document.body.addEventListener('click', (event) => {
        const heart = event.target.closest('.wishheart');
        if (!heart) return;
        const productCard = heart.closest('.product-card');
        const productName = productCard?.querySelector('.product-name')?.textContent?.trim();
        if (!productName) return;
        let wishlistItems = getWishlist();
        const index = wishlistItems.indexOf(productName);
        const isActive = heart.classList.contains('active');
        if (isActive) {
            heart.classList.remove('active');
            if (index !== -1) {
                wishlistItems.splice(index, 1);
            }
            showToast(`${productName} removed from wishlist`);
        } else {
            heart.classList.add('active');
            if (index === -1) {
                wishlistItems.push(productName);
            }
            showToast(`${productName} added to wishlist`);
        }
        setWishlist(wishlistItems);
    });
}

function loadSquareSdk() {
    return new Promise((resolve, reject) => {
        if (window.Square) {
            resolve();
            return;
        }
        const existingScript = document.querySelector('script[src*="square.js"]');
        if (existingScript) {
            existingScript.addEventListener('load', resolve);
            existingScript.addEventListener('error', reject);
            return;
        }
        const script = document.createElement('script');
        script.src = 'https://sandbox.web.squarecdn.com/v1/square.js';
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
    });
}

async function initializeSquarePayment() {
    const paymentSection = document.getElementById('payment-section');
    const status = document.getElementById('payment-status');
    if (!paymentSection || !window.Square) return;
    const applicationId = paymentSection.dataset.squareAppId;
    const locationId = paymentSection.dataset.squareLocationId;
    if (!applicationId || !locationId || applicationId.includes('REPLACE') || locationId.includes('REPLACE')) {
        setPaymentStatus('Square Payments is not configured. Enter your Square application and location IDs in the purchase page markup.');
        return;
    }

    try {
        const payments = Square.payments(applicationId, locationId);
        const card = await payments.card();
        await card.attach('#card-container');
        setPaymentStatus('Enter your card details and click Pay to create a Square payment token.');
        document.getElementById('pay-button')?.addEventListener('click', async () => {
            setPaymentStatus('Processing payment...');
            const tokenResult = await card.tokenize();
            if (tokenResult.status === 'OK') {
                setPaymentStatus('Payment token created successfully. Send this token to your server to complete checkout.');
                clearCart();
                showToast('Payment token created successfully.');
            } else {
                const message = tokenResult.errors?.map((error) => error.message).join(' ') || 'Card information is incomplete or invalid.';
                setPaymentStatus(message);
            }
        });
    } catch (error) {
        setPaymentStatus(`Square initialization failed: ${error.message}`);
        console.error(error);
    }
}

function setPaymentStatus(message) {
    const status = document.getElementById('payment-status');
    if (status) {
        status.textContent = message;
    }
}

function showToast(message) {
    let toast = document.querySelector('.toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.className = 'toast';
        document.body.appendChild(toast);
    }
    toast.textContent = message;
    toast.classList.add('show');
    clearTimeout(window.__cartToastTimeout);
    window.__cartToastTimeout = setTimeout(() => {
        toast.classList.remove('show');
    }, 2500);
}
