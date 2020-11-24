import archiveFilter from './archiveFilter';
import collapseSection from './collapseSection';
import scrollSpy from './scrollSpy';

document.addEventListener('DOMContentLoaded', function(event) {
    // Do stuff
    scrollSpy();
    archiveFilter();
    collapseSection();
});