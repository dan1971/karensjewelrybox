// Select the path element using its data attribute
const pathElement = document.querySelector('[data-toggle-class="wishheart"]');

pathElement.addEventListener('click', () => {
  // Toggle the active class on the path
  pathElement.classList.toggle('active');
});