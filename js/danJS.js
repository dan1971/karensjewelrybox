// Select the elements
const toggleButton = document.getElementByClassName('product-wishlist');
const svgPath = document.getElementByClassName('wishHeart');

// Toggle the class on click
toggleButton.addEventListener('click', () => {
  svgPath.classList.toggle('active');
});
