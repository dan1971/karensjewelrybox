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
                    // Send request to PHP backend
                    const response = await fetch('cart_handler.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            product_id: parseInt(productId),
                            quantity: parseInt(quantity)
                        })
                    });

                    const rawInput = await response.json();
                    const cleanJSON = rawInput.substring(rawInput.indexOf('"') + 1, rawInput.lastIndexOf('"'));

                        // 2. Parse the clean JSON
                    const data = JSON.parse(cleanJSON);
                    console.log("data ", data);
                    if (data.success) {
                        // Dynamically update UI with data returned from server
                        document.getElementById('cart-badge').add('active');
                        alert('Item added successfully!');
                    } else {
                        alert('Error: ' + data.message);
                    }

                } catch (error) {
                    console.error('Fetch Error:', error);
                    alert('Could not update cart.');
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
    