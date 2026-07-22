// Select the path element using its data attribute
const pathElement = document.querySelector('.wishheart');
pathElement.addEventListener('click', () => {
  // Toggle the active class on the path
  pathElement.classList.toggle('wishheart.active');
});