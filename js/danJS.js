const elements = document.querySelectorAll('.wishHeart');

elements.forEach(element => {
  element.addEventListener('click', (event) => {
    element.classList.toggle('.wishHeart-active'); 
    console.log('Div clicked!', event.target);
  });
});

// Select the elements
const toggleButton = document.getElementById('product-wishlist');
const svgPath = document.getElementById('myPath');

// Toggle the class on click
toggleButton.addEventListener('click', () => {
  svgPath.classList.toggle('active');
});

/*
document.addEventListener('DOMContentLoaded', () => {
  const hearts = document.querySelectorAll('.wishheart');
  const cookieName = 'wishlist_state';

  const readWishlist = () => {
    const match = document.cookie.match(new RegExp(`(?:^|; )${cookieName}=([^;]*)`));
    if (!match) {
      return [];
    }

    try {
      return JSON.parse(decodeURIComponent(match[1]));
    } catch (error) {
      return [];
    }
  };

  const writeWishlist = (ids) => {
    document.cookie = `${cookieName}=${encodeURIComponent(JSON.stringify(ids))}; path=/; SameSite=Lax`;
  };

  const activeIds = new Set(readWishlist());

  hearts.forEach((heart, index) => {
    const productId = `product-${index + 1}`;
    heart.dataset.wishlistId = productId;

    if (activeIds.has(productId)) {
      heart.classList.add('active');
    }

    heart.addEventListener('click', (event) => {
      event.preventDefault();
      event.stopPropagation();

      heart.classList.toggle('active');

      if (heart.classList.contains('active')) {
        activeIds.add(productId);
      } else {
        activeIds.delete(productId);
      }

      writeWishlist([...activeIds]);
    });
  });
});
*/