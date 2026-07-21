// Select the elements
const toggleButton = document.getElementById('product-wishlist');
const svgPath = document.getElementById('myPath');

// Toggle the class on click
toggleButton.addEventListener('click', () => {
  svgPath.classList.toggle('active');
});
