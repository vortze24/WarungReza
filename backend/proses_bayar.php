<?php
    include __DIR__ . '/koneksi.php';
    session_start();

    //MASTIIN SUDAH LOGIN
    if (!isset($_SESSION['status']) || $_SESSION['role'] != 'pelanggan') {
        header("Location: ../login.php?pesan=belum_login");
        exit();
    }
    
    //AMBIL ID
    $id = $_POST['id'];
    $id_user = $_SESSION['id'];

    //SET STATUS SUDAH DIBAYAR KALO UDAH BAYAR
    mysqli_query($koneksi,
        "UPDATE orders SET payment_status = 'Dibayar'
         WHERE id = $id AND user_id = $id_user AND payment_status = 'Menunggu Pembayaran' AND status <> 'dibatalkan'");

    header("Location: ../pesanan.php?pesan=pembayaran_berhasil");
    exit();
?>