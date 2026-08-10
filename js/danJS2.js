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

const checkoutButton = document.querySelector('.cart-link');

document.addEventListener('DOMContentLoaded', () => {
  // Select DOM Elements
  const menuToggle = document.querySelector('.menu-toggle');
  const menuClose = document.querySelector('.menu-close');
  const sideMenu = document.querySelector('.side-menu');
  const overlay = document.querySelector('.menu-overlay');

  // Function to open navigation
  const openNav = () => {
    sideMenu.classList.add('active');
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden'; // Prevents background body scrolling
  };

  // Function to close navigation
  const closeNav = () => {
    sideMenu.classList.remove('active');
    overlay.classList.remove('active');
    document.body.style.overflow = ''; // Restores background scrolling
  };

  // Attach Event Listeners
  menuToggle.addEventListener("click", function(event){
  event.preventDefault()
  openNav
});
//   menuToggle.addEventListener('click', openNav );
  menuClose.addEventListener('click', closeNav);
  overlay.addEventListener('click', closeNav); // Closes menu if user clicks outside of it
});



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
    