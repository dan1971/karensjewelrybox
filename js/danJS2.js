


 // <<<<<<<<<<<<<  HEADER SCROLL SHRINK >>>>>>>>>>>>>
function setupHeaderScroll() {
    updateHeaderShrink();
    window.addEventListener('scroll', updateHeaderShrink, { passive: true });
}

 // <<<<<<<<<<<<<  HEADER SCROLL SHRINK >>>>>>>>>>>>>
function updateHeaderShrink() {
    const header = document.querySelector('.site-header');
    if (!header) return;
    if (window.scrollY > 24) {
        header.classList.add('shrink');
    } else {
        header.classList.remove('shrink');
    }
}

 // <<<<<<<  DOM ON-LOAD CART PANEL VARIALBLE SETUP  >>>>>>
document.addEventListener('DOMContentLoaded', () => {
  const menuToggle = document.querySelector('.menu-toggle');
  const menuClose = document.querySelector('.menu-close');
  const sideMenu = document.querySelector('.side-menu');

  // <<<<<<<<<<<<<<<  OPEN CART PANEL FUNCTION  >>>>>>>>>>>>>>>>
  const openCartPanel = () => {
    sideMenu.classList.add('active');
  };

  // <<<<<<<<<<<<<<<  CLOSE CART PANEL FUNCTION  >>>>>>>>>>>>>>>>
  const closeCartPanel = () => {
    sideMenu.classList.remove('active');
  };

  // <<<<<<<<<< POPULATE CART PANEL- menuToggle CALLS THIS FUNCTION  >>>>>>>>>>>>>>>
    const populateSideMenu = async () => {
      const cartIsEmpty = sideMenu.querySelector('.cart-empty');
      const productImage = sideMenu.querySelector('.cart-item-image');
      const productTitle = sideMenu.querySelector('.product-title');
      const productDesc = sideMenu.querySelector('.cart-item-description');
      const productPrice = sideMenu.querySelector('.cart-item-price');
      const productQty = sideMenu.querySelector('.addItemsToOrder');
      const checkoutTotal = sideMenu.querySelector('.cart-item-total');
      const cartItems = sideMenu.querySelector('.cart-items');
      const makeCartChildren;

};

// console.log("Log #1- productImage, productTitle, productDesc, productPrice, productQty, checkoutTotal= ", " " ,checkoutTotal.textContent, " " , cartIsEmpty.textContent, " " , cartItems.textContent);

  // <<<< CALL CART HANDLER- GET CART ITEMS SCRIPT >>>>>>>>>>>>>>>>
    try {
        const response = await fetch('../cart_handler.php', {
        method: 'GET',
        headers: { 'Accept': 'application/json' }
      });

   // <<<<<<<<<<<<< CART HANDLER FAILURE RESPONSE >>>>>>>>>
      if (!response.ok) {
        throw new Error(`Cart fetch failed: ${response.status}`);
console.log("Log #2- CART HANDLER GET script FAILURE RESPONSE= ", response.status);
      }

   // <<<<<<<<<<<<<<<  CART HANDLER SUCCESS RESPONSE >>>>>>>>>
      const data = await response.json();
      const imageUrl = data.product_image || '';
      const totalValue = Number(data.cart_total || 0).toFixed(2);
      const cartTotalItems = data.cart_count || 0;

console.log("Log #3- CART HANDLER.php GET SUCCESS RESPONSE data, imageUrl, totalValue, cartTotalItems= ", data, " ", imageUrl, " ", totalValue, " ", cartTotalItems);

   // <<<<<<<<<<<<<<<  CART STATE EMPTY OR LIST >>>>>>>>>>>>>
      const products = data.products || {};
      const itemsMap = data.cart_items || {};
console.log("Log #4- products= ", products, " itemsMap= ", itemsMap);

   // <<<<<<<<<<<<<<<  CART STATE EMPTY >>>>>>>>>>>>>
      if (!cartItems){
        cartItems.innerHTML = `
          <div class="cart-empty">
            <p>Your cart is empty</p>
            <a href="index.php" class="continue-shopping-btn">Continue Shopping</a>
          </div>`;
console.log("Log #5- cartItems div not truthy= ", cartItems);
        return;

 // <<<<<<<<<<<<<<<  CART HAS ITEMS IN IT >>>>>>>>>>>>>
      } else {
        const makeCartChildren = Object.keys(itemsMap).map(id => {
          const prod = products[id] || products[String(id)];
          const qty = Number(itemsMap[id]) || 0;
          const name = prod?.name || 'Item';

          const price = Number(prod?.price || 0).toFixed(2);
          const img = prod?.image || imageUrl || 'images/b-ring002.webp';
          const lineTotal = (Number(price) * qty).toFixed(2);
// console.log("Log #6- cartItems div IS truthy- prod, qty, name, price, img, lineTotal= ", prod, " ", qty, " ", name, " ", price, " ", img, " ", lineTotal );

// <<<<<<<<<<<<<<<  CART POPULATE PANEL  >>>>>>>>>>>>>>>>>>>>>

            `<div class="cart-item" data-item-id="${prod}">
              <div class="cart-item-image">
              <img src="${img}" alt="${name}">
              </div>
              <div class="cart-item-details">
                <h1 class="product-title">${name}</h1>
                <p class="cart-item-description">
                ${name}</p>
                <div class="cart-item-quantity">
                  <label for="quantity${id}">Qty:</label>
                  <input class="addItemsToOrder" type="number" id="quantity${id}" data-item-quantity-index="${id}" value=${qty} min="0" max="99">
                </div>       
                <div class="cart-item-price">$${price}</div>
              </div>
              <div></div>
              <div></div>
              <div class="cart-item-total">$${lineTotal}</div>
              <div>
                <button class="cart-item-remove" data-product-id="${id}" aria-label="Remove">Remove</button>
              </div>
            </div>
          `;

        cartItems.appendChild(makeCartChildren);
        }).join('');

// <<<<<<<<<<<<<<<  CART REMOVE ITEM FROM CART  >>>>>>>>>>>>>>>>>>>>>
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

// <<<<<<<<<<<<<<  CART ANIMATE REMOVE ITEM >>>>>>>>>>>>>>>>>>>
              if (itemEl) {
                itemEl.classList.add('remove-anim');
                itemEl.addEventListener('animationend', async () => {
                  await populateSideMenu();
                }, { once: true });
              } else {
                await populateSideMenu();
              }

// <<<<<<<<<<<<<<  CART BADGE REFRESH  >>>>>>>>>>>>>>>>>>>
              const badge = document.querySelector('.cart-badge');
              if (badge && json.cart_count > 0) badge.classList.add('active');
              if (badge && json.cart_count == 0) badge.classList.remove('active');
            } catch (err) {
              console.error('LOG #8- Remove failed', err);
            }
          });
        });
      }

      if (productImage && imageUrl) {
        const imgElement = productImage.querySelector('img');
        if (imgElement) {
          imgElement.src = imageUrl;
        }
      }
      if (checkoutTotal) {
        checkoutTotal.textContent = `$${totalValue}`;
      }
    } catch (error) {
      console.error(error);
    }
  };

// <<<<<<<<<<<<<<  OPEN CART PANEL  >>>>>>>>>>>>>>>>>>>
  menuToggle.addEventListener('click', async () => {
    openCartPanel();
    await populateSideMenu();
  });

// <<<<<<<<<<<<<<  CLOSE CART PANEL  >>>>>>>>>>>>>>>>>>>
  menuClose.addEventListener('click', closeCartPanel);
});

// <<<<<<<   CLICK ADD-TO-CART- SEND PRODUCT INFO >>>>>>>>>>>>
  document.querySelectorAll('.add-to-cart-btn').forEach(button => {
    button.addEventListener('click', async (e) => {
console.log("LOG #10.1- ADD TO CART CLICK");

    const productId = e.target.getAttribute('data-product-id');
    const product_quantity = e.target.getAttribute('data-quantity');
    const product_image = e.target.getAttribute('data-image');
    const body= JSON.stringify({
                product_id: parseInt(productId, 10),
                quantity: parseInt(product_quantity, 10),
                image: product_image || null,
                });
console.log("LOG #10.2- ADD TO CART CLICK capture product info- info body:", body);

// <<<<<<<  ADD-TO-CART- SEND PRODUCT INFO TO CART HANDLER  >>>>>>
    try { const response = await fetch('../cart_handler.php', {
                          method: 'POST',
                          headers: {'Content-Type': 'application/json'},
                          body 
                        });
                    const text = await response.text();
                    let data = {};

// <<<<<<<  ADD-TO-CART-CART HANDLER DATA RETURN >>>>>>>>>>
                    try {

// <<<<<<<  ADD-TO-CART-CART HANDLER JSON ERROR  >>>>>>>>>>
                        data = text ? JSON.parse(text) : {};
                        } catch (e) {
console.log('LOG #11- CART HANDLER DATA Error return: ', `Server Error (Non-JSON): ${text}`);
                          throw new Error(`Server Error (Non-JSON): ${text}`);
                        }

// <<<<<<<  ADD-TO-CART-CART HANDLER HTTP ERROR   >>>>>>>>>>
                    if (!response.ok) {
console.log('LOG #12- CART HANDLER HTTP Error return: ', `data.message || HTTP Error ${response.status}`);
                        throw new Error(data.message || `HTTP Error ${response.status}`);
                    }

// <<<<<<<  ADD-TO-CART- POPULATE CART TOTAL AMOUNT >>>>>>>>>>
                const totalElement = document.querySelector('.checkOut_total');
                if (totalElement) {
                        totalElement.textContent = '$' + Number(data.cart_total || 0).toFixed(2);
                    }

// <<<<<<<  ADD-TO-CART- CART FAILS- REPLACE BADGE >>>>>>>>>>
                } catch (error) {
                    const badge = document.querySelector('.cart-badge');
                    if (badge) badge.classList.add('active');
                    console.error('LOG #13- Fetch Error:', error.message);
                }
            });
        });

// <<<<<<<<<<<<<<<<<<  CHECK OUT BUTTON CLICK >>>>>>>>>>>>>>>>>>>>
    function listenForCheckoutBtn () {
        document.getElementById('checkout-btn').addEventListener('click', async () => {
        if (!confirm('Are you sure you want to finalize your purchase?')) return;

// <<<<<<<<<<<<  CHECK OUT- CALL CHECKOUT PROCESSOR SCRIPT >>>>>>>>>>
        try {
            const response = await fetch('checkout_processor.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' }
            });
            const data = await response.json();

// <<<<<<<<<<<<  CHECK OUT- PROCESSOR SCRIPT SUCCESS >>>>>>>>>>
            if (data.success) {
                alert(`Success! Your order #${data.order_id} has been placed.`);
                // Reset your frontend UI badge tracking
                document.getElementById('cart-badge').classList.remove('active');

// <<<<<<<<<<<<  CHECK OUT- PROCESSOR SCRIPT FAILURE >>>>>>>>>>
            } else {
                alert('Checkout Failed: ' + data.message);
            }
// <<<<<<<<<<<<  CHECK OUT- PROCESSOR SCRIPT FAILURE >>>>>>>>>>
        } catch (error) {
            console.error('Checkout error:', error);
            alert('A network connection error prevented your checkout.');
        }
    });

    }
    