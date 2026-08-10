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

function setupCartPanel() {
    const cartPanel = document.getElementById('slideCartPanel');
    const backdrop = document.getElementById('cartPanelBackdrop');
    if (!cartPanel) return;

    const cartLinks = document.querySelectorAll('.cart-link');
    const closeButton = document.getElementById('closeCartPanel');

    const openPanel = event => {
        event.preventDefault();
        cartPanel.classList.add('open');
        backdrop?.classList.add('visible');
        cartPanel.setAttribute('aria-hidden', 'false');
        backdrop?.setAttribute('aria-hidden', 'false');
    };

    const closePanel = () => {
        cartPanel.classList.remove('open');
        backdrop?.classList.remove('visible');
        cartPanel.setAttribute('aria-hidden', 'true');
        backdrop?.setAttribute('aria-hidden', 'true');
    };

    cartLinks.forEach(link => {
        link.addEventListener('click', openPanel);
    });

    if (closeButton) {
        closeButton.addEventListener('click', closePanel);
    }

    if (backdrop) {
        backdrop.addEventListener('click', closePanel);
    }

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') {
            closePanel();
        }
    });
}

setupCartPanel();



document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', async (e) => {
                const productId = e.target.getAttribute('data-product-id');
                const quantity = e.target.getAttribute('data-quantity');

                try {
                    const response = await fetch('../cart_handler.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            product_id: parseInt(productId),
                            quantity: parseInt(quantity)
                        })
                    });

                    const text = await response.text();
                    let data = {};
                    try {
                        data = text ? JSON.parse(text) : {};
                    } catch (e) {
                        throw new Error(`Server Error (Non-JSON): ${text}`);
                    }

                    if (!response.ok) {
                        throw new Error(data.message || `HTTP Error ${response.status}`);
                    }

                    console.log('Success:', data);
                    const badge = document.querySelector(".cart-badge");
                    badge.classList.remove('active');
                    // document.getElementById('cart-count-badge').innerText = data.cart_count;
                    // document.getElementById('cart-total-display').innerText = '$' + data.cart_total.toFixed(2);

                } catch (error) {
                    const badge = document.querySelector(".cart-badge");
                    badge.classList.add('active');
                    console.error('Fetch Error:', error.message);
                }
            });
        });

//  const pageName = window.location.pathname.split("/").pop();
//     if(pageName==="cart.php"){
//         listenForCheckoutBtn();
//     }

    function listenForCheckoutBtn () {
        document.getElementById('checkout-btn').addEventListener('click', async () => {
        if (!confirm('Are you sure you want to finalize your purchase?')) return;

        try {
            const response = await fetch('checkout_processor.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' }
            });

            const data = await response.json();

            if (data.success) {
                alert(`Success! Your order #${data.order_id} has been placed.`);
                // Reset your frontend UI badge tracking
                document.getElementById('cart-badge').classList.remove('active');
  
            } else {
                alert('Checkout Failed: ' + data.message);
            }
            
        } catch (error) {
            console.error('Checkout error:', error);
            alert('A network connection error prevented your checkout.');
        }
    });

    }
    