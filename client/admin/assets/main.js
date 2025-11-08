const tabs = document.querySelectorAll('[data-tab-target]');
const tabContents = document.querySelectorAll('[data-tab-content]');

tabs.forEach(tab => {
    tab.addEventListener('click', () => {
        const target = document.querySelector(tab.dataset.tabTarget)

        tabs.forEach(t => t.classList.remove('active-tab'));

        tabContents.forEach(content => {
            content.classList.remove('active');
        });

        tab.classList.add('active-tab');

        target.classList.add('active');
    })
});