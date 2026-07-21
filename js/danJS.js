document.addEventListener('DOMContentLoaded', () => {
  const cookieName = 'wishlist_state';
  const hearts = Array.from(document.querySelectorAll('.wishheart'));

  const readWishlist = () => {
    const match = document.cookie.match(new RegExp(`(?:^|; )${cookieName}=([^;]*)`));
    if (!match) return [];

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
    const productId = heart.dataset.productId || `product-${index + 1}`;
    heart.dataset.productId = productId;

    if (activeIds.has(productId)) {
      heart.classList.add('active');
    }
  });

  document.addEventListener('click', (event) => {
    const heart = event.target.closest('.wishheart');

    if (!heart) return;

    event.preventDefault();
    event.stopPropagation();

    const productId = heart.dataset.productId;
    heart.classList.toggle('active');

    if (heart.classList.contains('active')) {
      activeIds.add(productId);
    } else {
      activeIds.delete(productId);
    }

    writeWishlist([...activeIds]);
  });
});
