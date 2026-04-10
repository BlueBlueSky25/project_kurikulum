import './bootstrap';

// ✅ PWA - INSTALL PROMPT HANDLER (PENTING!)
let deferredPrompt;
window.addEventListener('beforeinstallprompt', (e) => {
  console.log('✅ beforeinstallprompt event fired');
  // Prevent the mini-infobar dari browser
  e.preventDefault();
  // Simpan event untuk dipicu nanti
  deferredPrompt = e;
  // Tampilkan tombol install custom
  showInstallPrompt();
});

// ✅ PWA - Service Worker Registration
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/sw.js')
      .then(reg => {
        console.log('✅ Service Worker registered:', reg);
        
        // Check for updates setiap 1 menit
        setInterval(() => {
          reg.update();
        }, 60000);
      })
      .catch(err => console.log('❌ SW registration failed:', err));
  });
}

// ✅ Handle online/offline
window.addEventListener('online', () => {
  console.log('📡 Online - syncing data...');
  if ('serviceWorker' in navigator && 'SyncManager' in window) {
    navigator.serviceWorker.ready.then(reg => {
      reg.sync.register('sync-peminjaman');
    });
  }
});

window.addEventListener('offline', () => {
  console.log('📴 Offline - data akan disimpan lokal');
});

// ✅ Custom Install Button Handler
function showInstallPrompt() {
  // Cari atau buat tombol install
  let installBtn = document.getElementById('installButton');
  
  if (!installBtn) {
    // Kalo belum ada, buat button di header
    installBtn = document.createElement('button');
    installBtn.id = 'installButton';
    installBtn.className = 'bg-espresso text-paper px-4 py-2 rounded font-semibold hover:bg-ink transition';
    installBtn.textContent = '⬇️ Install App';
    installBtn.style.display = 'none'; // Hidden sampai ready
    
    const header = document.querySelector('header');
    if (header) {
      header.appendChild(installBtn);
    }
  }
  
  // Tampilkan button kalo deferredPrompt ada
  if (deferredPrompt) {
    installBtn.style.display = 'inline-block';
    
    installBtn.addEventListener('click', async () => {
      deferredPrompt.prompt();
      const { outcome } = await deferredPrompt.userChoice;
      console.log(`User response: ${outcome}`);
      deferredPrompt = null;
      installBtn.style.display = 'none';
    });
  }
}

// Handle appinstalled event
window.addEventListener('appinstalled', () => {
  console.log('✅ PWA was installed!');
  deferredPrompt = null;
  // Sembunyikan install button
  const installBtn = document.getElementById('installButton');
  if (installBtn) installBtn.style.display = 'none';
});