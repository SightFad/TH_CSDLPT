<?php
global $pdo;

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    // GET /hoadon → liệt kê tất cả hóa đơn
    $stmt = $pdo->query("
      SELECT * FROM HoaDon
    ");
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    response($items);
    break;

  case 'POST':
    // POST /hoadon → thêm hóa đơn mới
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['MaKhachHang'], $input['Ngay'])) {
      responseError(400, 'Thiếu thông tin hóa đơn');
      break;
    }

    $stmt = $pdo->prepare("INSERT INTO HoaDon (MaKhachHang, Ngay) VALUES (?, ?)");
    $success = $stmt->execute([
      $input['MaKhachHang'],
      $input['Ngay']
    ]);

    if ($success) {
      $id = $pdo->lastInsertId();
      response([
        'message' => 'Thêm hóa đơn thành công',
        'MaHoaDon' => $id
      ]);
    } else {
      responseError(500, 'Lỗi khi thêm hóa đơn');
    }
    break;

  default:
    responseError(405, 'Phương thức không được hỗ trợ');
    break;
}
