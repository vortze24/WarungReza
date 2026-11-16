<?php
    include __DIR__ . '/koneksi.php';
    session_start();

    //MASTIIN SDH LOGIN DAN ROLE DIKHUSUSKAN BUAT ADMIN
    if (!isset($_SESSION['status']) || $_SESSION['role'] != 'admin') {
        header("Location: ../login.php?pesan=belum_login");
        exit();
    }

    $id = $_POST['id'];
    $query = mysqli_query($koneksi, "SELECT status FROM orders WHERE id = $id");
    $o = mysqli_fetch_assoc($query);

    $status_baru = '';
    if ($o['status'] == 'baru') {
        $status_baru = 'diproses';
    } else if ($o['status'] == 'diproses') {
        $status_baru = 'siap';
    } else if ($o['status'] == 'siap') {
        $status_baru = 'selesai';
    }

    if ($status_baru != '') {
        mysqli_query($koneksi, "UPDATE orders SET status = '$status_baru' WHERE id = $id");
    }

    header("Location: ../admin_pesanan.php?pesan=status_diubah");
    exit();
?>