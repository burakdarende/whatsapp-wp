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

1. Ana dosyada `MBWSB_GITHUB_REPO_URL` sabitini doldur: `https://github.com/kullanici/repo-adı/` (sonunda `/` olsun).
2. **Sadece `git push` güncelleme göstermez.** WordPress, GitHub’daki **sürümü** (Release veya tag) kontrol eder. Yeni sürüm için:
   - Plugin başlığındaki `Version:` değerini artır (ör. `1.0.1`).
   - GitHub’da **Release** oluştur; tag adı genelde `v1.0.1` veya `1.0.1` olabilir.
   - Bu repoda eklenti `multi-branch-whatsapp-sticky-button/` alt klasöründe olduğu için, Release’e **`multi-branch-whatsapp-sticky-button.zip`** ekle: zip açılınca içinde `multi-branch-whatsapp-sticky-button/` klasörü ve içinde ana PHP dosyası olsun (WordPress eklenti zip yapısı).
3. Özel (private) repo için `MBWSB_GITHUB_TOKEN` içine yalnızca repo okuma yetkili bir GitHub token yaz.
4. Geliştirme: eklenti klasöründe `composer install` (veya `composer update`) ile `vendor` oluşturulur; dağıtımda `vendor` klasörünü sunucuya da at.

## Lisans

GPL-2.0-or-later
