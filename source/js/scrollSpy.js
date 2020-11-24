
import ScrollSpy from './vendor/ScrollSpy';

function throttle(func, wait = 100) {
    let timer = null;
    return function(...args) {
      if (timer === null) {
        timer = setTimeout(() => {
          func.apply(this, args);
          timer = null;
        }, wait); 
      }
    };
}

export default () => {
    const headerOffset = document.querySelector('html').getAttribute('data-header-offset');
    const options = {
        sectionSelector: '.js-scroll-spy-section',
        targetSelector: '[data-spy-target]',
        offset: headerOffset ? Number(headerOffset) + 20 : 1,
        hrefAttribute: 'data-spy-target',
        activeClass: 'is-active',
    };

    const scrollSpyMenu = document.querySelector('.js-scroll-spy');
    const scrollSpySections = document.querySelectorAll('.js-scroll-spy-section');

    if (scrollSpyMenu && scrollSpySections && scrollSpySections.length > 0) {
        const ScrollSpyInstance = new ScrollSpy(scrollSpyMenu, options);

        // Fire scroll events only when spy menu is visible
        let isVisible = true;

        const isMenuVisible = () => {
            isVisible = scrollSpyMenu.offsetWidth > 0 && scrollSpyMenu.offsetHeight > 0;
        };

        const handleScroll = () => {
            if (isVisible) {
                ScrollSpyInstance.onScroll();
            }
        };


        window.onload = handleScroll;
        window.onresize = throttle(isMenuVisible, 400);

        window.addEventListener('scroll', throttle(handleScroll, 16)); //throttle to rough 60fps
    }
};
