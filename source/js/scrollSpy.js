let sectionElementPositions = [];

const setListeners = () => {
    const scrollContainer = document.querySelector('#scroll-spy');
    const scrollItems = scrollContainer.querySelectorAll('.scroll-spy__item');
    let sectionElements = [];

    scrollItems.forEach(item => {
        if (item.getAttribute('href') && item.getAttribute('href').startsWith('#')) {
            sectionElements.push(document.querySelector(item.getAttribute('href')));
        }
        /* item.addEventListener('click', () => {
            handleClasses(item, scrollItems);
        }) */
    });

    if (sectionElements.length > 0) {
        window.addEventListener('resize', debounce(setSectionElementPositions, 2000, sectionElements));
        console.log(sectionElements, sectionElementPositions);

        window.addEventListener('scroll', () => {
            handleScroll(scrollItems, sectionElements);
        });

    }
}

const debounce = (func, delay, sectionElements) => {
    let timer;

    func(sectionElements);

    return () => {
        timer ? clearTimeout(timer) : '';
        timer = setTimeout(() => {
            func(sectionElements);
        }, delay);
    }
}

const setSectionElementPositions = (sectionElements) => {
    console.log(sectionElements[0].getBoundingClientRect());
    const arr = sectionElements.map(function (sectionElement) {
        return ({"position": window.scrollY + sectionElement.getBoundingClientRect().top, "height": sectionElement.getBoundingClientRect().height});
    });
    sectionElementPositions = arr;
    console.log(sectionElementPositions);
}

/* const handleClasses = (item, scrollItems) => {
    console.log(item, scrollItems);
    scrollItems.forEach(element => {
        if (element !== item) {
            element.classList.remove('is-active');
        } else {
            element.classList.add('is-active');
        }
    });
} */

const handleScroll = (scrollItems) => {
    //let reversedArr = sectionElementPositions.reverse()
    let i = 0;
    sectionElementPositions.forEach(item => {
        console.log(i);
        if(window.scrollY > item.position && (item.position + item.height) > window.scrollY) {
            console.log(scrollItems[i], i);
            scrollItems[i].classList.add('is-active');
        } else {
            scrollItems[i].classList.remove('is-active');
        }
        i++;
    });
}
export default () => { setListeners() };