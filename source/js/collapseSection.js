function collapseSection(element) {
    const sectionHeight = element.scrollHeight;
    const elementTransition = element.style.transition;
    element.style.transition = '';

    requestAnimationFrame(function(e) {
        element.style.height = sectionHeight + 'px';
        element.style.transition = elementTransition;

        requestAnimationFrame(function() {
            element.style.height = 0 + 'px';
        });
    });

    element.setAttribute('data-collapsed', 'true');
}
  
function expandSection(element) {
    const sectionHeight = element.scrollHeight;
    element.style.height = sectionHeight + 'px';

    const transitionendHandler = function(e) {
        element.removeEventListener('transitionend', transitionendHandler);
        element.style.height = null;
    }
    element.addEventListener('transitionend', transitionendHandler);
    element.setAttribute('data-collapsed', 'false');
}
  

export default function() {
    document.querySelector('.js-collapsible-toggle').addEventListener('click', function(e) {
        const targetSelector = e.currentTarget.getAttribute('data-collapsible-target');
        const targetElement = document.querySelector(targetSelector);
        const isCollapsed = targetElement.getAttribute('data-collapsed') === 'true';

        if (isCollapsed) {
          expandSection(targetElement)
          targetElement.setAttribute('data-collapsed', 'false');
          e.currentTarget.classList.add('is-active');
        } else {
            collapseSection(targetElement);
            e.currentTarget.classList.remove('is-active');
        }
    });
}

