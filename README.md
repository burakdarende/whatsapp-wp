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

## Lisans

GPL-2.0-or-later
