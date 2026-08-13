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
//   const overlay = document.querySelector('.menu-overlay');

  // Function to open navigation
  const openNav = () => {
    sideMenu.classList.add('active');

    // overlay.classList.add('active');
    // document.body.style.overflow = 'hidden'; // Prevents background body scrolling
  };

  // Function to close navigation
  const closeNav = () => {
    sideMenu.classList.remove('active');
    // overlay.classList.remove('active');
    // document.body.style.overflow = ''; // Restores background scrolling
  };

  // Populate side-menu from session / cart data
  const populateSideMenu = async () => {
    const productImage = sideMenu.querySelector('.product_image');
    const checkoutTotal = sideMenu.querySelector('.checkOut_total');
    const cartIsEmpty = sideMenu.querySelector('.cart-empty');
    const cartItems = sideMenu.querySelector('.cart-items');
    try {
      const response = await fetch('../cart_handler.php', {
        method: 'GET',
        headers: {
          'Accept': 'application/json'
        }
      });

      if (!response.ok) {
        throw new Error(`Cart fetch failed: ${response.status}`);
      }

      const data = await response.json();
      const imageUrl = data.product_image || '';
      const totalValue = Number(data.cart_total || 0).toFixed(2);
      const cartTotalItems = data.cart_count || 0;

      // Render empty state or item list
      const products = data.products || {};
      const itemsMap = data.cart_items || {};

      if (!cartItems) return;

      if (cartTotalItems === 0) {
        cartItems.innerHTML = `
          <div class="cart-empty">
            <p>Your cart is empty</p>
            <a href="index.php" class="continue-shopping-btn">Continue Shopping</a>
          </div>
        `;
      } else {
        cartItems.innerHTML = Object.keys(itemsMap).map(id => {
          const prod = products[id] || products[String(id)];
          const qty = Number(itemsMap[id]) || 0;
          const name = prod?.name || 'Item';
          const price = Number(prod?.price || 0).toFixed(2);
          const img = prod?.image || imageUrl || 'images/b-ring002.webp';
          const lineTotal = (Number(price) * qty).toFixed(2);

          return `
            <div class="cart-item" data-item-id="${id}">
              <div class="cart-item-image"><img src="${img}" alt="${name}"></div>
              <div class="cart-item-details">
                <h3 class="product-title">${name}</h3>
                <div class="cart-item-qty">Qty: ${qty}</div>
                <div class="cart-item-price">$${price}</div>
              </div>
              <div class="cart-item-total">$${lineTotal}</div>
              <div>
                <button class="cart-item-remove" data-product-id="${id}" aria-label="Remove">Remove</button>
              </div>
            </div>
          `;
        }).join('');
        // Wire remove buttons
        cartItems.querySelectorAll('.cart-item-remove').forEach(btn => {
          btn.addEventListener('click', async (e) => {
            const pid = btn.dataset.productId;
            const itemEl = btn.closest('.cart-item');
            try {
              const resp = await fetch('../cart_handler.php', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ product_id: parseInt(pid, 10) })
              });
              const json = await resp.json();
              if (!json || json.status !== 'success') throw new Error(json?.message || 'Delete failed');

              // Animate then refresh
              if (itemEl) {
                itemEl.classList.add('remove-anim');
                itemEl.addEventListener('animationend', async () => {
                  await populateSideMenu();
                }, { once: true });
              } else {
                await populateSideMenu();
              }

              const badge = document.querySelector('.cart-badge');
              if (badge && json.cart_count > 0) badge.classList.add('active');
              if (badge && json.cart_count == 0) badge.classList.remove('active');
            } catch (err) {
              console.error('Remove failed', err);
            }
          });
        });
      }

      if (productImage && imageUrl) {
        productImage.src = imageUrl;
      }
      if (checkoutTotal) {
        checkoutTotal.textContent = `$${totalValue}`;
      }
    } catch (error) {
      console.error('Unable to load cart menu data:', error);
    }
  };

  // Attach Event Listeners
  menuToggle.addEventListener('click', async () => {
    openNav();
    await populateSideMenu();
  });
  menuClose.addEventListener('click', closeNav);
//   overlay.addEventListener('click', closeNav); // Closes menu if user clicks outside of it
});



        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', async (e) => {
                const productId = e.target.getAttribute('data-product-id');
                const product_quantity = e.target.getAttribute('data-quantity');
                const product_image = e.target.getAttribute('data-image');

                const body= JSON.stringify({
                            product_id: parseInt(productId, 10),
                            quantity: parseInt(product_quantity, 10),
                            image: product_image || null,
                        });
                try {
                    const response = await fetch('../cart_handler.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        }, body });
            
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
                    const badge = document.querySelector('.cart-badge');
                    if (badge) badge.classList.add('active');

                    const totalElement = document.querySelector('.checkOut_total');

                    if (totalElement) {
                        totalElement.textContent = '$' + Number(data.cart_total || 0).toFixed(2);
                    }

                } catch (error) {
                    const badge = document.querySelector('.cart-badge');
                    if (badge) badge.classList.add('active');
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
    