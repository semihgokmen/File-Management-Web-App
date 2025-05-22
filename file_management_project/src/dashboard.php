<?php
require_once 'helpers.php';
checkSession();

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <title>Ana Sayfa</title>
  <link rel="stylesheet" href="dashboard.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>

  <div class="header">
    <h2>Hoş geldin, <?php echo htmlspecialchars($username); ?>!</h2>
    <div class="header-buttons">
      <button id="toggleUploadBtn" class="btn-toggle-upload">
        <i class="fas fa-upload"></i> Dosya Yükle
      </button>
      <button class="btn-logout" onclick="window.location.href='logout_process.php'"> <i class="fas fa-sign-out-alt"></i>Çıkış Yap</button>
    </div>
  </div>

  <div id="uploadArea" class="upload-area" style="display:none;">
    <p>
      <i class="fas fa-cloud-upload-alt"></i> Dosyaları buraya sürükle <br> veya <br>
      <button type="button" id="chooseFileBtn" class="btn-choose">
        <i class="fas fa-file-upload"></i> Dosya Seç
      </button>
    </p>
    
    <form id="uploadForm" style="margin-top: 10px;">
      <input type="file" id="fileInput" name="files[]" accept=".pdf,.png,.jpg,.jpeg" multiple style="display:none;">
      <div id="fileList" class="file-list"></div>
      <button type="button" id="uploadBtn" class="btn-upload">
        <i class="fas fa-paper-plane"></i> Yükle
      </button>
    </form>
    <div id="uploadMessage" class="message" style="display:none;"></div>
  </div>

  <div class="file-list-container">
    <?php require_once 'file_list.php'; ?>
  </div>
  
<script>
  const toggleUploadBtn = document.getElementById('toggleUploadBtn');
  const uploadArea = document.getElementById('uploadArea');
  const chooseFileBtn = document.getElementById('chooseFileBtn');
  const fileInput = document.getElementById('fileInput');
  const fileList = document.getElementById('fileList');
  const uploadBtn = document.getElementById('uploadBtn');
  const uploadMessage = document.getElementById('uploadMessage');
  const fileListContainer = document.querySelector('.file-list-container'); // Dosya listesini içeren kapsayıcı

  let selectedFiles = [];

  // Dosya yükleme alanını aç/kapat
  toggleUploadBtn.addEventListener('click', () => {
    uploadArea.style.display = (uploadArea.style.display === 'none') ? 'block' : 'none';
  });

  // Dosya seçme butonuna tıklama
  chooseFileBtn.addEventListener('click', () => {
    fileInput.click();
  });

  // Dosya seçildiğinde
  fileInput.addEventListener('change', e => {
    const newFiles = Array.from(e.target.files);
    newFiles.forEach(f => {
      if (!selectedFiles.some(sf => sf.name === f.name && sf.size === f.size && sf.lastModified === f.lastModified)) {
        selectedFiles.push(f);
      }
    });
    updateFileList();
  });

  // Sürükleme olaylarını ele al
  uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault(); // Varsayılan davranışı engelle
    uploadArea.classList.add('dragover'); // Görsel geri bildirim için sınıf ekle
  });

  uploadArea.addEventListener('dragleave', () => {
    uploadArea.classList.remove('dragover'); // Görsel geri bildirimi kaldır
  });

  uploadArea.addEventListener('drop', (e) => {
    e.preventDefault(); // Varsayılan davranışı engelle
    uploadArea.classList.remove('dragover'); // Görsel geri bildirimi kaldır

    const droppedFiles = Array.from(e.dataTransfer.files); // Sürüklenen dosyaları al
    droppedFiles.forEach(f => {
      if (!selectedFiles.some(sf => sf.name === f.name && sf.size === f.size && sf.lastModified === f.lastModified)) {
        selectedFiles.push(f);
      }
    });
    updateFileList(); // Dosya listesini güncelle
  });

  // Dosya listesini güncelle
  function updateFileList() {
    fileList.innerHTML = '';
    selectedFiles.forEach((file, idx) => {
      const div = document.createElement('div');
      div.classList.add('file-item');
      div.innerHTML = `
        <span><i class="fas fa-file"></i> ${file.name}</span>
        <button type="button" class="btn-remove" data-index="${idx}" title="Dosyayı Sil">
          <i class="fas fa-trash-alt"></i>
        </button>
      `;
      fileList.appendChild(div);
    });

    // Silme butonları
    document.querySelectorAll('.btn-remove').forEach(btn => {
      btn.onclick = () => {
        const index = parseInt(btn.getAttribute('data-index'));
        selectedFiles.splice(index, 1);
        updateFileList();
      };
    });
  }

  // Dosya yükleme işlemi
  uploadBtn.addEventListener('click', () => {
    if (selectedFiles.length === 0) {
      showMessage('Lütfen en az bir dosya seçin.', 'error');
      return;
    }

    const formData = new FormData();
    selectedFiles.forEach(file => formData.append('files[]', file));

    fetch('upload_process.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showMessage('Dosyalar başarıyla yüklendi.', 'success');
        selectedFiles = [];
        updateFileList();
        window.location.reload(); // Sayfayı yenile
      } else {
        showMessage('Dosya yükleme sırasında hata oluştu.', 'error');
      }
    })
    .catch(() => {
      showMessage('Dosya yükleme sırasında hata oluştu.', 'error');
    });
  });

  // Mesaj gösterme
  function showMessage(message, type) {
    uploadMessage.textContent = message;
    uploadMessage.className = `message ${type}`;
    uploadMessage.style.display = 'block';
    setTimeout(() => {
      uploadMessage.style.display = 'none';
    }, 5000);
  }
</script>

</body>
</html>