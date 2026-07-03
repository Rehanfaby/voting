(function () {
    function pad(n) { return (n < 10 ? '0' : '') + n; }

    function tickEl(el) {
        var raw = el.getAttribute('data-deadline') || '';
        var deadline = new Date(raw.replace(' ', 'T')).getTime();
        if (isNaN(deadline)) return;
        var diff = deadline - Date.now();
        if (diff < 0) diff = 0;
        var d = Math.floor(diff / 86400000);
        var h = Math.floor((diff % 86400000) / 3600000);
        var m = Math.floor((diff % 3600000) / 60000);
        var s = Math.floor((diff % 60000) / 1000);
        var days = el.querySelector('.cd-days');
        var hours = el.querySelector('.cd-hours');
        var mins = el.querySelector('.cd-mins');
        var secs = el.querySelector('.cd-secs');
        if (days) days.textContent = pad(d);
        if (hours) hours.textContent = pad(h);
        if (mins) mins.textContent = pad(m);
        if (secs) secs.textContent = pad(s);
        if (diff === 0) el.classList.add('is-ended');
    }

    function initCountdowns() {
        document.querySelectorAll('.mg-event-countdown[data-deadline]').forEach(tickEl);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCountdowns);
    } else {
        initCountdowns();
    }
    setInterval(function () {
        document.querySelectorAll('.mg-event-countdown[data-deadline]').forEach(tickEl);
    }, 1000);
})();
