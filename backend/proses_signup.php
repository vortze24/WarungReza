<?php
    include __DIR__ . '/koneksi.php';

    $nama = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    
    //CEK KALO GA DI ISI
    if (empty($nama) || empty($email) || empty($password)) {
        header("Location: ../sign_up.php?pesan=data_tidak_valid");
        exit();
    }
    //MENGHINDARI EMAIL YANG SAMA SIGN UP LAGI, JADI EMAIL YANG SAMA TIDAK BISA TERPAKAI LAGI
    $cek_query = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$email'");

    if (mysqli_num_rows($cek_query) > 0) {
        header("Location: ../sign_up.php?pesan=email_terpakai");
    } else {
        // AKUN BARU DIBUAT ROLENYA HANYA PELANGGAN, ADMIN CUMA SATU
        $query = mysqli_query($koneksi,
            "INSERT INTO users (name, email, password, role) VALUES ('$nama', '$email', '$password', 'pelanggan')");

        //AKUN BERHASIL DIDAFTARKAN, LANJUT LOGIN 
        if ($query) {
            header("Location: ../login.php?pesan=akun_dibuat");
        }
    }
?>