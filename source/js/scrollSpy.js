let sectionElementPositions = [];

const setListeners = () => {
    const scrollContainer = document.querySelector('#scroll-spy');
    const scrollItems = scrollContainer.querySelectorAll('.scroll-spy__item');
    let sectionElements = [];

    scrollItems.forEach(item => {
        if (item.getAttribute('href') && item.getAttribute('href').startsWith('#')) {
            if (document.querySelector(item.getAttribute('href'))) {
                sectionElements.push(document.querySelector(item.getAttribute('href')));
            } else {
                item.remove();
            }
        }
    });

    if (sectionElements.length > 0) {
        window.addEventListener('resize', debounce(setSectionElementPositions, 2000, sectionElements));

        let currentScroll = 0;
        window.addEventListener('scroll', () => {
            let scrollTop = window.scrollY;
            if(Math.abs(currentScroll - scrollTop > 30 || currentScroll - scrollTop < -30)) {
                handleScroll(scrollItems);
                currentScroll = scrollTop;
            }
        });

    }
}

const debounce = (func, delay, sectionElements) => {
    let timer;

    func(sectionElements);

    return () => {
        timer ? clearTimeout(timer) : (() => {})();
        timer = setTimeout(() => {
            func(sectionElements);
        }, delay);
    }
}

const setSectionElementPositions = (sectionElements) => {
    const arr = sectionElements.map(function (sectionElement) {
        return ({"position": window.scrollY + sectionElement.getBoundingClientRect().top, "height": sectionElement.getBoundingClientRect().height});
    });
    sectionElementPositions = arr;
}

const handleScroll = (scrollItems) => {
    let i = 0;
    sectionElementPositions.forEach(item => {
        if(window.scrollY > item.position - 190 && (item.position + item.height - 170) > window.scrollY) {
            console.log("hej");
            scrollItems[i].classList.add('is-active');
        } else {
            scrollItems[i].classList.remove('is-active');
        } 
        i++;
    });
}
export default () => { setListeners() };