<?php
global $pdo;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  if (!isset($segments[1])) {
    responseError(400, 'Thiếu ID hóa đơn');
    return;
  }

  $id = intval($segments[1]);

  $stmt = $pdo->prepare("
    SELECT 
      ct.MaSanPham,
      sp.TenSanPham,
      ct.SoLuong,
      sp.GiaBan 
    FROM ChiTietHoaDon ct
    JOIN SanPham sp ON ct.MaHoaDon = sp.MaHoaDon
    WHERE ct.MaSanPham = ?
  ");
  $stmt->execute([$id]);
  $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

  response($items);
} else {
  responseError(405, 'Phương thức không được hỗ trợ');
}
