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

                    const data = await response.json();

                    if (data.success) {
                        // Dynamically update UI with data returned from server
                        document.getElementById('cart-count').textContent = data.cart_count;
                        document.getElementById('cart-total').textContent = data.cart_total.toFixed(2);
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
    