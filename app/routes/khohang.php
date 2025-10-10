<?php
global $pdo;

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    if (isset($segments[1])) {
      // GET /khohang/{id}
      $stmt = $pdo->prepare("SELECT * FROM KhoHang WHERE MaKhoHang = ?");
      $stmt->execute([$segments[1]]);
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($data) {
        response($data);
      } else {
        responseError(404, 'Kho hàng không tồn tại');
      }
    } else {
      // GET /khohang
      $stmt = $pdo->query("SELECT * FROM KhoHang");
      $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
      response($items);
    }
    break;

  case 'POST':
    // POST /khohang
    $input = json_decode(file_get_contents('php://input'), true);
    if (!isset($input['TenKhoHang'], $input['DiaChi'])) {
      responseError(400, 'Thiếu thông tin kho hàng');
      break;
    }

    $stmt = $pdo->prepare("INSERT INTO KhoHang (TenKhoHang, DiaChi) VALUES (?, ?)");
    $success = $stmt->execute([
      $input['TenKhoHang'],
      $input['DiaChi']
    ]);

    if ($success) {
      $id = $pdo->lastInsertId();
      response(['message' => 'Thêm kho hàng thành công', 'MaKhoHang' => $id]);
    } else {
      responseError(500, 'Lỗi khi thêm kho hàng');
    }
    break;

  case 'PUT':
    // PUT /khohang/{id}
    if (!isset($segments[1])) {
      responseError(400, 'Thiếu ID kho hàng');
      break;
    }

    $input = json_decode(file_get_contents('php://input'), true);
    if (!isset($input['TenKhoHang'], $input['DiaChi'])) {
      responseError(400, 'Thiếu thông tin cập nhật');
      break;
    }

    $stmt = $pdo->prepare("UPDATE KhoHang SET TenKhoHang = ?, DiaChi = ? WHERE MaKhoHang = ?");
    $success = $stmt->execute([
      $input['TenKhoHang'],
      $input['DiaChi'],
      $segments[1]
    ]);

    if ($success && $stmt->rowCount() > 0) {
      response(['message' => 'Cập nhật kho hàng thành công']);
    } else {
      responseError(404, 'Không tìm thấy kho hàng để cập nhật');
    }
    break;

  case 'DELETE':
    // DELETE /khohang/{id}
    if (!isset($segments[1])) {
      responseError(400, 'Thiếu ID kho hàng');
      break;
    }

    $stmt = $pdo->prepare("DELETE FROM KhoHang WHERE MaKhoHang = ?");
    $success = $stmt->execute([$segments[1]]);

    if ($success && $stmt->rowCount() > 0) {
      response(['message' => 'Xóa kho hàng thành công']);
    } else {
      responseError(404, 'Không tìm thấy kho hàng để xóa');
    }
    break;

  default:
    responseError(405, 'Phương thức không được hỗ trợ');
    break;
}
