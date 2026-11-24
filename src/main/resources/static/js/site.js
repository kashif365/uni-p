// Reveal a coupon code, copy it to the clipboard, record the use and open the store's website.
document.addEventListener('click', function (event) {
    const button = event.target.closest('.reveal');
    if (!button) return;

    const code = button.dataset.code;
    if (!button.classList.contains('revealed')) {
        button.classList.add('revealed');
        button.textContent = code;
        const csrfToken = document.querySelector('meta[name="_csrf"]').content;
        const csrfHeader = document.querySelector('meta[name="_csrf_header"]').content;
        fetch('/coupons/' + button.dataset.id + '/use', { method: 'POST', headers: { [csrfHeader]: csrfToken } });
        if (button.dataset.url) window.open(button.dataset.url, '_blank', 'noopener');
    }
    if (navigator.clipboard) {
        navigator.clipboard.writeText(code).then(function () {
            button.textContent = code + '  (copied)';
        });
    }
});
