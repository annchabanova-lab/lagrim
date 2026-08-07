document.addEventListener('DOMContentLoaded', function () {
  var langLinks = document.querySelectorAll('.lang-switch a');
  var currentLang = document.documentElement.lang || 'fr';

  function setCookie(name, value, days) {
    var d = new Date();
    d.setTime(d.getTime() + days * 24 * 60 * 60 * 1000);
    document.cookie = name + '=' + value + ';expires=' + d.toUTCString() + ';path=/;SameSite=Lax';
  }

  setCookie('lg_lang', currentLang, 365);

  var saved = sessionStorage.getItem('lg_scroll');
  if (saved !== null) {
    sessionStorage.removeItem('lg_scroll');
    window.scrollTo(0, parseInt(saved, 10));
  }

  langLinks.forEach(function (link) {
    if (link.getAttribute('data-lang') === currentLang) {
      link.classList.add('active');
    }
    link.addEventListener('click', function (e) {
      setCookie('lg_lang', this.getAttribute('data-lang'), 365);
      sessionStorage.setItem('lg_scroll', window.scrollY);
    });
  });
});
