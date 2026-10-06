/**
 * Image upload widget for admin editors.
 *
 * Usage:
 *   createImageUpload(container, {
 *     name: 'hero_image',
 *     value: 'images/lg-P06-maison-nuit',
 *     label: 'Image de fond',
 *   })
 */
function createImageUpload(container, opts) {
  const wrapper = document.createElement('div');
  wrapper.className = 'img-upload';

  // Normalize path: strip leading /, strip -sm/-md/-lg suffix and .webp/.jpg extension
  let currentPath = (opts.value || '').replace(/^\//, '').replace(/-(sm|md|lg)\.(webp|jpg|jpeg|png)$/i, '').replace(/\.(webp|jpg|jpeg|png)$/i, '');
  const previewSrc = currentPath ? ('/' + currentPath + '-md.webp') : '';

  wrapper.innerHTML = `
    <label class="img-upload-label">${opts.label || 'Image'}</label>
    <div class="img-upload-zone" data-name="${opts.name}">
      <div class="img-upload-preview" style="${previewSrc ? '' : 'display:none'}">
        <img src="${previewSrc}" alt="Preview">
        <button type="button" class="img-upload-change">Changer / Change</button>
      </div>
      <div class="img-upload-placeholder" style="${previewSrc ? 'display:none' : ''}">
        <div class="img-upload-icon">📷</div>
        <p>Glisser-déposer une image ici</p>
        <p class="img-upload-hint">ou cliquer pour parcourir — JPG, PNG, WebP (max 20MB)</p>
      </div>
      <input type="file" class="img-upload-input" accept="image/jpeg,image/png,image/webp,image/gif" style="display:none">
      <input type="hidden" name="${opts.name}" value="${currentPath}">
      <div class="img-upload-progress" style="display:none">
        <div class="img-upload-bar"><div class="img-upload-bar-fill"></div></div>
        <span class="img-upload-status">Upload en cours...</span>
      </div>
      <div class="img-upload-error" style="display:none"></div>
    </div>
  `;

  container.appendChild(wrapper);

  const zone = wrapper.querySelector('.img-upload-zone');
  const fileInput = wrapper.querySelector('.img-upload-input');
  const hiddenInput = wrapper.querySelector('input[type="hidden"]');
  const preview = wrapper.querySelector('.img-upload-preview');
  const previewImg = wrapper.querySelector('.img-upload-preview img');
  const placeholder = wrapper.querySelector('.img-upload-placeholder');
  const changeBtn = wrapper.querySelector('.img-upload-change');
  const progress = wrapper.querySelector('.img-upload-progress');
  const barFill = wrapper.querySelector('.img-upload-bar-fill');
  const status = wrapper.querySelector('.img-upload-status');
  const errorEl = wrapper.querySelector('.img-upload-error');

  function uploadFile(file) {
    errorEl.style.display = 'none';
    progress.style.display = 'flex';
    placeholder.style.display = 'none';
    preview.style.display = 'none';
    barFill.style.width = '0%';
    status.textContent = 'Upload en cours...';

    const formData = new FormData();
    formData.append('image', file);
    formData.append('current', hiddenInput.value);
    formData.append('field', opts.name);
    const csrfInput = document.querySelector('input[name="csrf_token"]');
    if (csrfInput && csrfInput.value) {
      formData.append('csrf_token', csrfInput.value);
    }

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'upload.php', true);

    xhr.upload.onprogress = function(e) {
      if (e.lengthComputable) {
        const pct = Math.round((e.loaded / e.total) * 100);
        barFill.style.width = pct + '%';
        status.textContent = pct < 100 ? 'Upload... ' + pct + '%' : 'Conversion WebP...';
      }
    };

    xhr.onload = function() {
      progress.style.display = 'none';
      try {
        const res = JSON.parse(xhr.responseText);
        if (res.error) {
          errorEl.textContent = res.error;
          errorEl.style.display = 'block';
          placeholder.style.display = currentPath ? 'none' : '';
          preview.style.display = currentPath ? 'flex' : 'none';
          return;
        }
        hiddenInput.value = res.base_path;
        previewImg.src = '/' + res.preview + '?t=' + Date.now();
        preview.style.display = 'flex';
      } catch (e) {
        errorEl.textContent = 'Erreur serveur';
        errorEl.style.display = 'block';
      }
    };

    xhr.onerror = function() {
      progress.style.display = 'none';
      errorEl.textContent = 'Upload échoué';
      errorEl.style.display = 'block';
    };

    xhr.send(formData);
  }

  placeholder.addEventListener('click', () => fileInput.click());
  changeBtn.addEventListener('click', () => fileInput.click());

  fileInput.addEventListener('change', function() {
    if (this.files[0]) uploadFile(this.files[0]);
  });

  zone.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('img-upload-dragover');
  });

  zone.addEventListener('dragleave', function() {
    this.classList.remove('img-upload-dragover');
  });

  zone.addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('img-upload-dragover');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
      uploadFile(file);
    }
  });
}

(function() {
  if (document.getElementById('img-upload-styles')) return;
  const style = document.createElement('style');
  style.id = 'img-upload-styles';
  style.textContent = `
    .img-upload { margin-bottom: 1rem; }
    .img-upload-label {
      display: block; font-size: 0.75rem; font-weight: 500;
      margin-bottom: 0.3rem; color: #6B5F55;
    }
    .img-upload-zone {
      border: 2px dashed #ddd; border-radius: 8px;
      padding: 1rem; text-align: center; cursor: pointer;
      transition: border-color 0.2s, background 0.2s;
      position: relative; min-height: 120px;
      display: flex; align-items: center; justify-content: center;
      flex-direction: column;
    }
    .img-upload-zone.img-upload-dragover {
      border-color: #92A17F; background: #f0f5ed;
    }
    .img-upload-placeholder { cursor: pointer; padding: 1rem; }
    .img-upload-icon { font-size: 2rem; margin-bottom: 0.5rem; }
    .img-upload-placeholder p {
      font-size: 0.85rem; color: #6B5F55; margin: 0.2rem 0;
    }
    .img-upload-hint { font-size: 0.75rem !important; color: #999 !important; }
    .img-upload-preview {
      display: flex; align-items: center; gap: 1rem;
      width: 100%; padding: 0.5rem;
    }
    .img-upload-preview img {
      max-width: 200px; max-height: 120px; border-radius: 6px;
      object-fit: cover; box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .img-upload-change {
      background: none; border: 1px solid #ddd; padding: 6px 14px;
      border-radius: 4px; cursor: pointer; font-family: 'Poppins', sans-serif;
      font-size: 0.8rem; color: #6B5F55;
    }
    .img-upload-change:hover { border-color: #92A17F; color: #778A65; }
    .img-upload-progress {
      display: flex; align-items: center; gap: 0.75rem; width: 100%; padding: 0.5rem;
    }
    .img-upload-bar {
      flex: 1; height: 6px; background: #eee; border-radius: 3px; overflow: hidden;
    }
    .img-upload-bar-fill {
      height: 100%; background: #92A17F; border-radius: 3px;
      transition: width 0.2s;
    }
    .img-upload-status { font-size: 0.8rem; color: #6B5F55; white-space: nowrap; }
    .img-upload-error {
      color: #B5622E; font-size: 0.8rem; margin-top: 0.5rem; text-align: center;
    }
  `;
  document.head.appendChild(style);
})();
