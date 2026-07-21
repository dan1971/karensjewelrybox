const elements = document.querySelectorAll('.wishHeart');

// elements.forEach(element => {
//   element.addEventListener('click', (event) => {
//     element.classList.toggle('.wishHeart-active'); 
//     console.log('Div clicked!', event.target);
//   });
// });

// Select the elements
const toggleButton = document.getElementById('product-wishlist');
const svgPath = document.getElementById('myPath');
// const svgPath = document.getElementById('myPath');

// Toggle the class on click
toggleButton.addEventListener('click', () => {
  svgPath.classList.toggle('active');
});
