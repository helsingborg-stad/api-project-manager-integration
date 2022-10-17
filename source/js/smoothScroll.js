
import smoothscroll from 'smoothscroll-polyfill';

export default () => {
  smoothscroll.polyfill();
  Object.values(document.querySelectorAll('a[href^="#"]')).forEach(anchor => {
    if (anchor.getAttribute('href') === '#')
      return;
    

    anchor.addEventListener('click', function (e) {
        e.preventDefault();
  
        if (this.getAttribute('href') === '#') {
          return;
        }

        const headerOffset = document.querySelector('html').getAttribute('data-header-offset');
        const element = document.querySelector(this.getAttribute('href'));
        const offset = headerOffset ?? 0;
        const elementPosition = element.getBoundingClientRect().top + window.pageYOffset;
        const offsetPosition = elementPosition - offset;

        window.scrollTo({
          top: offsetPosition,
          behavior: "smooth"
        });
      });
  })
}
  