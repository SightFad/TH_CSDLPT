USE Shop;
GO
SET STATISTICS IO ON;
SET STATISTICS TIME ON;
SET STATISTICS PROFILE ON;

-- Q2.1
SELECT COUNT(*) FROM HoaDon;

-- Q2.2
SELECT COUNT(*) FROM ChiTietHoaDon;

-- Q2.3
SELECT h.MaHoaDon, h.Ngay, s.TenSanPham, c.SoLuong
FROM HoaDon h
  JOIN ChiTietHoaDon c ON h.MaHoaDon = c.MaHoaDon
  JOIN SanPham s ON c.MaSanPham = s.MaSanPham;