<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    echo "Yetkisiz erişim.";
    exit();
}

$jsonFile = "uploads.json";
$data = [];

if (file_exists($jsonFile)) {
    $content = file_get_contents($jsonFile);
    $data = json_decode($content, true) ?? [];
}

// Kullanıcının dosyalarını filtrele
$userFiles = array_filter($data, function($item) {
    return $item['username'] === $_SESSION['username'];
});
?>

<table class="file-table" id="fileTable">
    <thead>
        <tr>
            <th data-sort="filename">
                Dosya Adı <span class="sort-icon" data-sort="filename">▲▼</span>
            </th>
            <th data-sort="uploaded_at">
                Yüklenme Tarihi <span class="sort-icon" data-sort="uploaded_at">▲▼</span>
            </th>
            <th data-sort="size">
                Boyut (KB) <span class="sort-icon" data-sort="size">▲▼</span>
            </th>
            <th>İşlem</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($userFiles as $file): ?>
        <tr>
            <td>
                <a href="uploads/<?= htmlspecialchars($file['filename']) ?>" target="_blank">
                    <?= htmlspecialchars($file['filename']) ?>
                </a>
            </td>
            <td><?= htmlspecialchars($file['uploaded_at']) ?></td>
            <td><?= round($file['size'] / 1024, 2) ?></td>
            <td>
                <a style="color:white" href="uploads/<?= htmlspecialchars($file['filename']) ?>" download class="btn-download"><i class="fas fa-download"></i> İndir</a>
                <button class="btn-delete" data-filename="<?= htmlspecialchars($file['filename']) ?>"> <i class="fas fa-trash-alt"></i> Sil</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
  // Silme butonlarına tıklama işlemi
  document.querySelectorAll('.btn-delete').forEach(button => {
    button.addEventListener('click', () => {
      const filename = button.getAttribute('data-filename');
      if (confirm(`"${filename}" dosyasını silmek istediğinizden emin misiniz?`)) {
        fetch('delete_file.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ filename })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Dosya başarıyla silindi.');
            location.reload(); // Sayfayı yeniden yükle
          } else {
            alert('Dosya silinirken bir hata oluştu.');
          }
        })
        .catch(() => alert('Dosya silinirken bir hata oluştu.'));
      }
    });
  });

  // Tablo sıralama işlevi
  const table = document.getElementById('fileTable');
  const headers = table.querySelectorAll('th[data-sort]');
  const tbody = table.querySelector('tbody');

  let currentSort = { key: null, order: 'asc' }; // Mevcut sıralama durumu

  headers.forEach(header => {
    header.addEventListener('click', () => {
      const sortKey = header.getAttribute('data-sort');
      const rows = Array.from(tbody.querySelectorAll('tr'));

      // Sıralama yönünü belirle
      if (currentSort.key === sortKey) {
        currentSort.order = currentSort.order === 'asc' ? 'desc' : 'asc';
      } else {
        currentSort.key = sortKey;
        currentSort.order = 'asc';
      }

      // Sıralama işlemi
      rows.sort((a, b) => {
        const aValue = a.querySelector(`td:nth-child(${header.cellIndex + 1})`).textContent.trim();
        const bValue = b.querySelector(`td:nth-child(${header.cellIndex + 1})`).textContent.trim();

        if (sortKey === 'size') {
          return currentSort.order === 'asc'
            ? parseFloat(aValue) - parseFloat(bValue)
            : parseFloat(bValue) - parseFloat(aValue);
        } else if (sortKey === 'uploaded_at') {
          return currentSort.order === 'asc'
            ? new Date(aValue) - new Date(bValue)
            : new Date(bValue) - new Date(aValue);
        } else {
          return currentSort.order === 'asc'
            ? aValue.localeCompare(bValue)
            : bValue.localeCompare(aValue);
        }
      });

      // Sıralanmış satırları tabloya yeniden ekle
      rows.forEach(row => tbody.appendChild(row));

      // İkonları güncelle
      document.querySelectorAll('.sort-icon').forEach(icon => {
        icon.textContent = '▲▼'; // Varsayılan ikon
      });
      const icon = header.querySelector('.sort-icon');
      icon.textContent = currentSort.order === 'asc' ? '▲' : '▼';
    });
  });
</script>