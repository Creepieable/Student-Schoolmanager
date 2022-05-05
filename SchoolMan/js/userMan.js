$(document).on('click','#logout-btn', function () {
    Cookies.remove('__scman_us_t');
    window.location.href = "./index.html";
});