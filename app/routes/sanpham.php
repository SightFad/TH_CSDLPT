<?php
global $pdo;

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    if (isset($segments[1])) {
      // GET /sanpham/{id}
      $stmt = $pdo->prepare("SELECT * FROM SanPham WHERE MaSanPham = ?");
      $stmt->execute([$segments[1]]);
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($data) {
        response($data);
      } else {
        responseError(404, 'Sản phẩm không tồn tại');
      }
    } else {
      // GET /sanpham
      $stmt = $pdo->query("SELECT * FROM SanPham");
      $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
      response($items);
    }
    break;

  case 'POST':
    // POST /sanpham
    $input = json_decode(file_get_contents('php://input'), true);
    if (!isset($input['MaSanPham'], $input['TenSanPham'], $input['GiaBan'], $input['MaKhoHang'])) {
      responseError(400, 'Thiếu thông tin sản phẩm');
      break;
    }

    $stmt = $pdo->prepare("INSERT INTO SanPham (MaSanPham, TenSanPham, GiaBan, MaKhoHang) VALUES (?, ?, ?, ?)");
    $success = $stmt->execute([
      $input['MaSanPham'],
      $input['TenSanPham'],
      $input['GiaBan'],
      $input['MaKhoHang']
]);

    if ($success) {
      $id = $pdo->lastInsertId();
      response(['message' => 'Thêm sản phẩm thành công', 'MaSanPham' => $id]);
    } else {
      responseError(500, 'Lỗi khi thêm sản phẩm');
    }
    break;

  case 'PUT':
    // PUT /sanpham/{id}
    if (!isset($segments[1])) {
      responseError(400, 'Thiếu ID sản phẩm');
      break;
    }

    $input = json_decode(file_get_contents('php://input'), true);
    if (!isset($input['TenSanPham'], $input['GiaBan'], $input['MaKhoHang'])) {
      responseError(400, 'Thiếu thông tin cập nhật');
      break;
    }

    $stmt = $pdo->prepare("UPDATE SanPham SET TenSanPham = ?, GiaBan = ?, MaKhoHang = ? WHERE MaSanPham = ?");
    $success = $stmt->execute([
      $input['TenSanPham'],
      $input['GiaBan'],
      $input['MaKhoHang'],
      $segments[1]
    ]);

    if ($success && $stmt->rowCount() > 0) {
      response(['message' => 'Cập nhật sản phẩm thành công']);
    } else {
      responseError(404, 'Không tìm thấy sản phẩm để cập nhật');
    }
    break;

  case 'DELETE':
    // DELETE /sanpham/{id}
    if (!isset($segments[1])) {
      responseError(400, 'Thiếu ID sản phẩm');
      break;
    }

    $stmt = $pdo->prepare("DELETE FROM SanPham WHERE MaSanPham = ?");
    $success = $stmt->execute([$segments[1]]);

    if ($success && $stmt->rowCount() > 0) {
      response(['message' => 'Xóa sản phẩm thành công']);
    } else {
      responseError(404, 'Không tìm thấy sản phẩm để xóa');
    }
    break;

  default:
    responseError(405, 'Phương thức không được hỗ trợ');
    break;
}
