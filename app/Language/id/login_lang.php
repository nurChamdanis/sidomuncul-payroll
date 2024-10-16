<?php

/**
 * Screen Login Screen
 */
$value['title'] = 'Masuk';
$value['user_name'] = 'Nomor Karyawan';
$value['user_password'] = 'Kata Sandi';

$value['btn_submit'] = 'Masuk';
$value['forgot_password'] = 'Lupa Kata Sandi';
$value['reset_password'] = 'Atur Kata Sandi';

$value['failed'] = '<strong>Masuk Gagal.</strong> <br/>' . $value['user_name'] . ' atau ' . $value['user_password'] . ' salah.';
$value['misscode'] = '<strong>Konfirmasi Gagal.</strong> <br/>Kode Konfirmasi Pengguna tidak terdaftar.';
$value['attempt'] = 'Percobaan terlalu banyak. Coba setelah $diff menit lagi.';
$value['redirect_uri'] = '<strong>Perhatian.</strong> <br/><span class="text-muted">Anda harus login terlebih dahulu untuk mengakses halaman tersebut</span>';

/**
 * Screen Forgot Password
 */
$value['forgot_password_body'] = 'Masukan alamat email Anda dan Kami akan mengirim tautan untuk mengubah Kata Sandi';
$value['forgot_password_footer'] = 'Masuk jika Anda memiliki akun';
$value['btn_send'] = 'Kirim';
$value['email_not_registered'] = '<strong>Gagal.</strong> <br/>Email anda tidak terdaftar.';
$value['email_not_confirmed'] = '<strong>Gagal.</strong> <br/>Email Anda belum dikonfirmasi.';
$value['email_not_sent'] = '<strong>Gagal.</strong> <br/>Terjadi kesalahan saat mengirimkan email, coba beberapa saat lagi.';
$value['email_send_success'] = '<strong>Berhasil. </strong> <br/>Tautan untuk ubah Kata Sandi telah dikirim ke e-mail.';
$value['reset_password_code_not_found'] = '<strong>Gagal. </strong> <br/>Kode untuk ubah kata sandi anda sudah tidak valid.';

/**
 * Screen Reset Password
 */
$value['reset_password_failed'] = '<strong>Gagal. </strong> <br/>Kata Sandi anda gagal diubah. <br/>Kode untuk ubah kata sandi anda sudah tidak valid. <br/>Untuk Kembali ke halaman lupa kata sandi klik <a href="$link">disini</a>';
$value['reset_password_success'] = '<strong>Berhasil. </strong> <br/>Kata Sandi anda berhasil diubah. <br/>Sekarang anda dapat menggunakan akun anda kembali. <br/>Klik <a href="$link">disini</a> untuk masuk ke halaman login.';
$value['reset_password_body'] = 'Masukan kata sandi yang baru untuk Akun Email anda, ';
$value['new_password'] = 'Kata Sandi';
$value['confirm_password'] = 'Tulis Ulang Kata Sandi';
$value['password_length_char'] = 'Minimal 8 Karakter';
$value['password_special_char'] = 'Kombinasi Spesial Karakter';
$value['password_uppercase_char'] = 'Kombinasi Huruf Besar dan Kecil';
$value['btn_continue'] = 'Simpan';

/**
 * ----------------------------------------------------------------
 * Set root key for language
 * ----------------------------------------------------------------
 */
$lang['Login'] = $value;

return $lang;