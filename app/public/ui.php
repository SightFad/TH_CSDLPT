<?php
function fetchApi($url) {
  try {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5); // tránh treo
    $json = curl_exec($ch);
    if ($json === false) {
      $error = curl_error($ch);
      curl_close($ch);
      return ['error' => "cURL error: $error"];
    }
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($status !== 200) {
      return ['error' => "HTTP status $status"];
    }
    return json_decode($json, true);
  } catch (Exception $e) {
    return ['error' => $e->getMessage()];
  }
}

function fetchApiPost($url, $data) {
  try {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $json = curl_exec($ch);
    if ($json === false) {
      $error = curl_error($ch);
      curl_close($ch);
      return ['error' => "cURL POST error: $error"];
    }
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($status !== 200 && $status !== 201) {
      return ['error' => "HTTP status $status"];
    }
    return json_decode($json, true);
  } catch (Exception $e) {
    return ['error' => $e->getMessage()];
  }
}

$base = "http://api_php_112541:8080"; //Chú ý tên/port service chỗ này

// gọi dữ liệu từ API
$sanpham   = fetchApi("$base/sanpham");
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['TenSanPham'])) {
  $newProduct = [
    'MaSanPham' => intval($_POST['MaSanPham']),
    'TenSanPham' => $_POST['TenSanPham'],
    'GiaBan' => intval($_POST['GiaBan']),
    'MaKhoHang' => intval($_POST['MaKhoHang'])
  ];
  $result = fetchApiPost("$base/sanpham", $newProduct);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
  $id = intval($_POST['delete_id']);
  $ch = curl_init("$base/sanpham/$id");
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $json = curl_exec($ch);
  curl_close($ch);
  $deleteResult = json_decode($json, true);
}

$khachhang = fetchApi("$base/khachhang");
$hoadon    = fetchApi("$base/hoadon");
$khohang = fetchApi("$base/khohang");
$view = $_GET['view'] ?? 'sanpham'; // mặc định là sản phẩm
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Test API</title>
</head>
<body>
  <h2>Điều hướng</h2>
<form method="get" action="ui.php">
  <button type="submit" name="view" value="sanpham">Sản phẩm</button>
  <button type="submit" name="view" value="khachhang">Khách hàng</button>
  <button type="submit" name="view" value="hoadon">Hóa đơn</button>
  <button type="submit" name="view" value="chitiethoadon">Chi tiết hóa đơn</button>
  <button type="submit" name="view" value="khohang">Kho hàng</button>
</form>
<hr>
  <?php if ($view === 'sanpham'): ?>
  <h1>Danh sách Sản phẩm</h1>
  <table border="1">
  <tr><th>ID</th><th>Tên</th><th>Giá</th><th>Thao tác</th></tr>
  <?php foreach ($sanpham as $sp): ?>
    <tr>
      <td><?= $sp['MaSanPham'] ?></td>
      <td><?= $sp['TenSanPham'] ?></td>
      <td><?= $sp['GiaBan'] ?></td>
      <td>
        <form method="post" action="ui.php" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
          <input type="hidden" name="delete_id" value="<?= $sp['MaSanPham'] ?>">
          <button type="submit">Xóa</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </table>
<?php elseif ($view === 'khachhang'): ?>
  <h1>Danh sách Khách hàng</h1>
  <?php if (isset($khachhang['error'])): ?>
    <p style="color:red"><?= $khachhang['error'] ?></p>
  <?php else: ?>
    <table border="1">
      <tr><th>ID</th><th>Tên</th><th>Địa chỉ</th><th>SĐT</th></tr>
      <?php foreach ($khachhang as $kh): ?>
        <tr>
          <td><?= $kh['MaKhachHang'] ?></td>
          <td><?= $kh['TenKh'] ?></td>
          <td><?= $kh['DiaChi'] ?></td>
          <td><?= $kh['SoDienThoai'] ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
<?php elseif ($view === 'hoadon'): ?>
  <h1>Danh sách Hóa đơn</h1>
  <?php if (isset($hoadon['error'])): ?>
    <p style="color:red"><?= $hoadon['error'] ?></p>
  <?php else: ?>
    <table border="1">
      <tr><th>ID</th><th>Ngày</th><th>Khách hàng</th></tr>
      <?php foreach ($hoadon as $hd): ?>
        <tr>
          <td><?= $hd['MaHoaDon'] ?></td>
          <td><?= $hd['Ngay'] ?></td>
          <td><?= $hd['MaKhachHang'] ?? 'Ẩn' ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
<?php elseif ($view === 'khohang'): ?>
  <h1>Danh sách Kho hàng</h1>
  <?php if (isset($khohang['error'])): ?>
    <p style="color:red"><?= $khohang['error'] ?></p>
  <?php else: ?>
    <table border="1">
      <tr><th>ID</th><th>Tên kho</th><th>Địa chỉ</th></tr>
      <?php foreach ($khohang as $kho): ?>
        <tr>
          <td><?= $kho['MaKhoHang'] ?></td>
          <td><?= $kho['TenKhoHang'] ?></td>
          <td><?= $kho['DiaChi'] ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
<?php endif; ?>

  <h1>Tra cứu hóa đơn</h1>
  <form method="get" action="ui.php">
    <input type="number" name="id" placeholder="Nhập ID hóa đơn">
    <button type="submit">Xem</button>
  </form>
  <?php
  if (isset($_GET['id'])) {
      $ct = fetchApi("$base/chitiethoadon/" . intval($_GET['id']));
      echo "<pre>" . print_r($ct, true) . "</pre>";
  }
  
  ?>
  <h1>Thêm sản phẩm mới</h1>
<form method="post" action="ui.php">
  <input type="number" name="MaSanPham" placeholder="Mã sản phẩm" required><br>
  <input type="text" name="TenSanPham" placeholder="Tên sản phẩm" required><br>
  <input type="number" name="GiaBan" placeholder="Giá bán" required><br>
  <input type="number" name="MaKhoHang" placeholder="Mã kho hàng" required><br>
  <button type="submit">Thêm sản phẩm</button>
</form>
<?php
if (isset($result)) {
  if (isset($result['error'])) {
    echo "<p style='color:red'>{$result['error']}</p>";
  } else {
    echo "<p style='color:green'>Thêm sản phẩm thành công! Mã sản phẩm: {$result['MaSanPham']}</p>";
  }
}
?>
</body>
</html>