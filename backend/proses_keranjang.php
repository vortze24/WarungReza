<?php
    include __DIR__ . '/koneksi.php';
    session_start();
    //MASTIIN LOGIN
    if (!isset($_SESSION['status']) || $_SESSION['status'] != true) {
        header("Location: ../login.php?pesan=belum_login");
        exit();
    }
    //MENGECUALIKAN ADMIN
    if ($_SESSION['role'] == 'admin') {
        header("Location: ../index.php?pesan=admin_tidak_bisa_pesan");
        exit();
    }

    $id_user = $_SESSION['id'];

    $aksi = '';
    if (isset($_POST['aksi'])) {
        $aksi = $_POST['aksi'];
    }
    if (isset($_GET['aksi'])) {
        $aksi = $_GET['aksi'];
    }

    // TAMBAH MENU DI KERANJANG
    if ($aksi == 'tambah') {
        $id_menu = $_POST['id'];
        $qty = $_POST['qty'];
        $catatan = htmlspecialchars(trim($_POST['note']));
        if ($qty < 1) {
            $qty = 1;
        }

        // MENAMBAH JUMLAH KALAU MENU SUDAH DITAMBAH KE KERANJANG SBLMNYA
        $cek = mysqli_query($koneksi, "SELECT * FROM cart WHERE user_id = $id_user AND menu_id = $id_menu");
        if (mysqli_num_rows($cek) > 0) {
            mysqli_query($koneksi, "UPDATE cart SET qty = qty + $qty, note = '$catatan' WHERE user_id = $id_user AND menu_id = $id_menu");
        } 
        //MENAMBAH BARIS BARU KALAU SEBELUMNYA BLM DITAMBAHKAN KE KERANJANG
        else {
            mysqli_query($koneksi, "INSERT INTO cart (user_id, menu_id, qty, note) VALUES ($id_user, $id_menu, $qty, '$catatan')");
        }

        //MEMBATASI JUMLAH PESANAN GA MELEBIHI STOK
        mysqli_query($koneksi, "UPDATE cart k JOIN menus m ON m.id = k.menu_id SET k.qty = m.stock WHERE k.user_id = $id_user AND k.qty > m.stock");

        header("Location: ../index.php?pesan=ditambahkan");
        exit();
    }

    // MENAMBAH JUMLAH MENU DARI KERANJANG
    if ($aksi == 'perbarui') {
        $id_cart = $_POST['id_cart'];
        $qty = $_POST['qty'];
        //KALAU BERJUMLAH 0, DIHAPUS DARI KERANJANG
        if ($qty <= 0) {
            mysqli_query($koneksi, "DELETE FROM cart WHERE id = $id_cart AND user_id = $id_user");
        } 
        //EDIT JUMLAH MENU PESANAN
        else {
            mysqli_query($koneksi, "UPDATE cart SET qty = $qty WHERE id = $id_cart AND user_id = $id_user");
            mysqli_query($koneksi, "UPDATE cart k JOIN menus m ON m.id = k.menu_id SET k.qty = m.stock WHERE k.user_id = $id_user AND k.qty > m.stock");
        }
        header("Location: ../keranjang.php?pesan=keranjang_diperbarui");
        exit();
    }

    // HAPUS SALAH SATU MENU DI KERANJNAG 
    if ($aksi == 'hapus') {
        $id_cart = $_GET['id_cart'];
        mysqli_query($koneksi, "DELETE FROM cart WHERE id = $id_cart AND user_id = $id_user");
        header("Location: ../keranjang.php?pesan=item_dihapus");
        exit();
    }

    header("Location: ../keranjang.php");
?>