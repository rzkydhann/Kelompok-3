<?php

// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "teknisi");

// Fungsi untuk memeriksa koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Fungsi login admin (sudah ada, hanya dipertahankan)
function loginAdmin($data) {
    global $conn;
    $username = mysqli_real_escape_string($conn, $data['username']);
    $password = $data['password'];
    
    $stmt = mysqli_prepare($conn, "SELECT * FROM admin WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row["password"])) {
            session_start();
            $_SESSION['loginadmin'] = true;
            $_SESSION['useradmin'] = $row['nama'];
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_username'] = $row['username'];
            if (isset($row['level'])) {
                $_SESSION['admin_level'] = $row['level'];
            }
            return true;
        }
    }
    return false;
}

// Fungsi registrasi admin (sudah ada, dipertahankan)
function registrasiAdmin($data) {
    global $conn;
    $nama = htmlspecialchars($data['nama']);
    $username = strtolower(stripcslashes($data["username"]));
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $level = isset($data['level']) ? htmlspecialchars($data['level']) : 'admin';
    
    $result = mysqli_query($conn, "SELECT username FROM admin WHERE username = '$username'");
    if (mysqli_fetch_assoc($result)) {
        echo "<script>alert('Username admin sudah terdaftar!');</script>";
        return false;
    }
    
    $password = password_hash($password, PASSWORD_DEFAULT);
    mysqli_query($conn, "INSERT INTO admin VALUES('','$nama','$username','$password','$level')");
    return mysqli_affected_rows($conn);
}

// Fungsi registrasi pelanggan (sudah ada, dipertahankan)
function registrasiP($data) {
    global $conn;
    $nama = htmlspecialchars($data['nama']);
    $username = strtolower(stripcslashes($data["username"]));
    $hp = htmlspecialchars($data['hp']);
    $email = htmlspecialchars($data['email']);
    $password = mysqli_real_escape_string($conn, $data["password"]);

    $result = mysqli_query($conn, "SELECT username FROM pelanggan WHERE username = '$username'");
    if (mysqli_fetch_assoc($result)) {
        echo "<script>alert('Username sudah terdaftar!');</script>";
        return false;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);
    mysqli_query($conn, "INSERT INTO pelanggan VALUES('','$nama','$username','$hp','$email','$password')");
    return mysqli_affected_rows($conn);
}

// Fungsi registrasi teknisi (sudah ada, dipertahankan)
function registrasiT($data) {
    global $conn;
    $nama = htmlspecialchars($data['nama']);
    $username = strtolower(stripcslashes($data["username"]));
    $hp = htmlspecialchars($data['hp']);
    $email = htmlspecialchars($data['email']);
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $keahlian = htmlspecialchars($data['keahlian']);
    $alamat = htmlspecialchars($data['alamat']);

    $result = mysqli_query($conn, "SELECT username FROM teknisi WHERE username = '$username'");
    if (mysqli_fetch_assoc($result)) {
        echo "<script>alert('Username sudah terdaftar!');</script>";
        return false;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);
    mysqli_query($conn, "INSERT INTO teknisi VALUES('','$nama','$username','$hp','$email','$password','$keahlian','$alamat')");
    return mysqli_affected_rows($conn);
}

// Fungsi order (diperbarui dengan prepared statement dan kolom eksplisit)
function order($data) {
    global $conn;
    $nama = htmlspecialchars($data['nama']);
    $hp = htmlspecialchars($data['hp']);
    $layananPerbaikan = htmlspecialchars($data['layananPerbaikan']);
    $merk = htmlspecialchars($data['merk']);
    $jenisPerbaikan = htmlspecialchars($data['jenisPerbaikan']);
    $tanggal = htmlspecialchars($data['tanggal']);
    $waktu = htmlspecialchars($data['waktu']);
    $alamat = htmlspecialchars($data['alamat']);
    $status = htmlspecialchars($data['status']);
    $teknisi = htmlspecialchars($data['teknisi']);
    $catatan_admin = ''; // Nilai default untuk catatan_admin

    $query = "INSERT INTO orderperbaikan (nama, hp, layananPerbaikan, merk, jenisPerbaikan, tanggal, waktu, alamat, status, teknisi, catatan_admin)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssssssssss", $nama, $hp, $layananPerbaikan, $merk, $jenisPerbaikan, $tanggal, $waktu, $alamat, $status, $teknisi, $catatan_admin);
    $success = mysqli_stmt_execute($stmt);
    $affected_rows = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);

    return $affected_rows;
}

// Fungsi query (dipertahankan)
function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// Fungsi completed order (diperbarui dengan prepared statement)
function completed($id) {
    global $conn;
    $stmt = mysqli_prepare($conn, "UPDATE orderperbaikan SET status = 'Completed' WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $affected_rows = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    return $affected_rows;
}

// Fungsi cancel order (diperbarui dengan prepared statement)
function canceled($id) {
    global $conn;
    $stmt = mysqli_prepare($conn, "UPDATE orderperbaikan SET status = 'Canceled' WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $affected_rows = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    return $affected_rows;
}

// Fungsi ambil order (diperbarui dengan prepared statement)
function ambil($id) {
    session_start();
    $user = $_SESSION['userteknisi'];
    global $conn;
    $stmt = mysqli_prepare($conn, "UPDATE orderperbaikan SET status = 'Dalam Penanganan', teknisi = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "si", $user, $id);
    mysqli_stmt_execute($stmt);
    $affected_rows = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    return $affected_rows;
}

// Fungsi baru: update status order (untuk dashboard_admin.php)
function updateStatus($id, $new_status) {
    global $conn;
    $allowed_statuses = ['Pending', 'Dalam Penanganan', 'Completed', 'Canceled', 'Menunggu Konfirmasi', 'Diproses'];
    if (in_array($new_status, $allowed_statuses)) {
        $stmt = mysqli_prepare($conn, "UPDATE orderperbaikan SET status = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "si", $new_status, $id);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_affected_rows($stmt);
    }
    return 0;
}

// Fungsi baru: update catatan admin (untuk dashboard_admin.php)
function updateCatatan($id, $catatan_admin) {
    global $conn;
    $stmt = mysqli_prepare($conn, "UPDATE orderperbaikan SET catatan_admin = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "si", $catatan_admin, $id);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_affected_rows($stmt);
}

// Fungsi baru: hapus order (untuk dashboard_admin.php)
function deleteOrder($id) {
    global $conn;
    $stmt = mysqli_prepare($conn, "DELETE FROM orderperbaikan WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_affected_rows($stmt);
}

?>