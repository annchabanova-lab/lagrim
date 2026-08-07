document.addEventListener('DOMContentLoaded', function () {
  var overlay = document.createElement('div');
  overlay.className = 'lightbox-overlay';
  overlay.innerHTML = '<button class="lightbox-close" aria-label="Close">&times;</button><img src="" alt="">';
  document.body.appendChild(overlay);

  var lbImg = overlay.querySelector('img');
  var lbClose = overlay.querySelector('.lightbox-close');

  document.querySelectorAll('[data-lightbox]').forEach(function (el) {
    el.style.cursor = 'pointer';
    el.addEventListener('click', function () {
      var src = this.getAttribute('data-lightbox') || this.querySelector('img')?.src || '';
      var alt = this.querySelector('img')?.alt || '';
      if (src) {
        lbImg.src = src;
        lbImg.alt = alt;
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
      }
    });
  });

  function closeLightbox() {
    overlay.classList.remove('active');
    document.body.style.overflow = '';
    lbImg.src = '';
  }

  overlay.addEventListener('click', function (e) {
    if (e.target === overlay || e.target === lbClose) {
      closeLightbox();
    }
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeLightbox();
  });
});
