<?php
global $pdo;

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    if (isset($segments[1])) {
      // GET /khachhang/{id}
      $stmt = $pdo->prepare("SELECT * FROM KhachHang WHERE MaKhachHang = ?");
      $stmt->execute([$segments[1]]);
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($data) {
        response($data);
      } else {
        responseError(404, 'Khách hàng không tồn tại');
      }
    } else {
      // GET /khachhang
      $stmt = $pdo->query("SELECT * FROM KhachHang");
      $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
      response($items);
    }
    break;

  case 'POST':
    // POST /khachhang
    $input = json_decode(file_get_contents('php://input'), true);
    if (!isset($input['TenKh'], $input['DiaChi'], $input['SoDienThoai'])) {
      responseError(400, 'Thiếu thông tin khách hàng');
      break;
    }

    $stmt = $pdo->prepare("INSERT INTO KhachHang (TenKh, DiaChi, SoDienThoai) VALUES (?, ?, ?)");
    $success = $stmt->execute([
      $input['TenKh'],
      $input['DiaChi'],
      $input['SoDienThoai']
    ]);

    if ($success) {
      $id = $pdo->lastInsertId();
      response(['message' => 'Thêm khách hàng thành công', 'MaKhachHang' => $id]);
    } else {
      responseError(500, 'Lỗi khi thêm khách hàng');
    }
    break;

  case 'PUT':
    // PUT /khachhang/{id}
    if (!isset($segments[1])) {
      responseError(400, 'Thiếu ID khách hàng');
      break;
    }

    $input = json_decode(file_get_contents('php://input'), true);
    if (!isset($input['TenKh'], $input['DiaChi'], $input['SoDienThoai'])) {
      responseError(400, 'Thiếu thông tin cập nhật');
      break;
    }

    $stmt = $pdo->prepare("UPDATE KhachHang SET TenKh = ?, DiaChi = ?, SoDienThoai = ? WHERE MaKhachHang = ?");
    $success = $stmt->execute([
      $input['TenKh'],
      $input['DiaChi'],
      $input['SoDienThoai'],
      $segments[1]
    ]);

    if ($success && $stmt->rowCount() > 0) {
      response(['message' => 'Cập nhật khách hàng thành công']);
    } else {
      responseError(404, 'Không tìm thấy khách hàng để cập nhật');
    }
    break;

  case 'DELETE':
    // DELETE /khachhang/{id}
    if (!isset($segments[1])) {
      responseError(400, 'Thiếu ID khách hàng');
      break;
    }

    $stmt = $pdo->prepare("DELETE FROM KhachHang WHERE MaKhachHang = ?");
    $success = $stmt->execute([$segments[1]]);

    if ($success && $stmt->rowCount() > 0) {
      response(['message' => 'Xóa khách hàng thành công']);
    } else {
      responseError(404, 'Không tìm thấy khách hàng để xóa');
    }
    break;

  default:
    responseError(405, 'Phương thức không được hỗ trợ');
    break;
}
