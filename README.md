# Multi-Branch WhatsApp Sticky Button

WordPress eklentisi: sitede sol altta sabit WhatsApp butonu; yönetimden birden fazla şube hattı (isim + telefon) tanımlanır, tıklanınca liste açılır ve `wa.me` ile yeni sekmede yönlendirir.

## Gereksinimler

- WordPress 5.8+
- PHP 7.4+

## Kurulum

1. `multi-branch-whatsapp-sticky-button` klasörünü `wp-content/plugins/` altına kopyalayın.
2. WordPress yönetiminde **Eklentiler** üzerinden etkinleştirin.
3. **Ayarlar → WhatsApp Hatları** menüsünden şube adı ve ülke kodlu numara girin (ör. Türkiye için `905551234567`).
4. En az bir geçerli satır kaydedildiğinde ön yüzde buton görünür.

## Teknik notlar

- Ayarlar `wp_options` içinde `mbwsb_branches` anahtarıyla dizi olarak saklanır.
- Ön yüzde jQuery kullanılmaz; CSS ve vanilla JS kullanılır.
- Yönetim formu WordPress Settings API ve `options.php` akışıyla kaydedilir.

## GitHub’dan otomatik güncelleme

Eklenti [Plugin Update Checker](https://github.com/YahnisElsts/plugin-update-checker) (Composer ile `vendor/` içinde) kullanır.

1. Ana dosyada `MBWSB_GITHUB_REPO_URL` sabitini doldur: `https://github.com/kullanici/repo-adı` (son `/` kodda otomatik eklenir).
2. WordPress güncellemesi **GitHub Release** üzerinden gelir; release’te **`multi-branch-whatsapp-sticky-button.zip`** dosyası olmalı (iç yapı: zip → `multi-branch-whatsapp-sticky-button/` → `multi-branch-whatsapp-sticky-button.php`). GitHub’ın “Source code (zip)” arşivi tek başına genelde **yanlış klasör adı** verir; bu yüzden güncelleme **başarısız** olur. PUC bu yüzden release **asset** adını bekler.
3. Özel (private) repo için `MBWSB_GITHUB_TOKEN` içine repo okuma yetkili bir GitHub token yaz (wp-config.php’de tanımlamak daha güvenli).
4. Geliştirme: eklenti klasöründe `composer install`; dağıtımda `vendor` sunucuda da olsun.

### Otomatik Release (GitHub Actions)

`.github/workflows/release-plugin.yml`: **`main`** veya **`master`** dalına push olduğunda (eklenti klasörü değiştiyse) workflow çalışır; `multi-branch-whatsapp-sticky-button.php` içindeki **`* Version: X.Y.Z`** değerini okur, aynı tag’li release yoksa **`multi-branch-whatsapp-sticky-button.zip`** üretip **`vX.Y.Z`** release oluşturur.

- Yeni sürüm: hem `* Version:` hem `define( 'MBWSB_VERSION', ... )` değerini artır → commit → push.
- Aynı sürüm numarası için zaten release varsa workflow zip atlamaz (tekrar release oluşturmaz).
- Elle tetiklemek için: repo → **Actions** → **Release plugin (GitHub)** → **Run workflow**.

### Güncelleme “başarısız” olursa

- Sunucuda `wp-content` yazılabilir mi, disk dolu mu.
- Önbellek / güvenlik eklentisi dış indirmeyi kesiyor mu.
- Release’te **`multi-branch-whatsapp-sticky-button.zip`** gerçekten var mı (Actions ile üretildiyse olur).
- Private repo: `MBWSB_GITHUB_TOKEN` tanımlı mı.

## Lisans

GPL-2.0-or-later
