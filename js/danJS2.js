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
                    const badge = document.getElementById('#cart-badge');
                     badge.classList.remove('active');
                    // document.getElementById('cart-count-badge').innerText = data.cart_count;
                    // document.getElementById('cart-total-display').innerText = '$' + data.cart_total.toFixed(2);

                } catch (error) {
                    const badge = document.getElementById('#cart-badge');
                     badge.classList.add('active');
                    console.error('Fetch Error:', error.message);
                }
            });
        });

 const pageName = window.location.pathname.split("/").pop();
    if(pageName==="cart.php"){
        listenForCheckoutBtn();
    }

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
    