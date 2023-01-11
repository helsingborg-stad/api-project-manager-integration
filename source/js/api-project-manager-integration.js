import archiveFilter from './archiveFilter';
import collapseSection from './collapseSection';
import scrollSpy from './scrollSpy';
import smoothScroll from './smoothScroll';

document.addEventListener('DOMContentLoaded', function(event) {
    // Do stuff
    archiveFilter();
    scrollSpy();
    collapseSection();
    smoothScroll();
});