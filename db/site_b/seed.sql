-- USE Shop;
-- GO

-- -- Thêm sản phẩm mới tại Site B
-- INSERT INTO SanPham (MaSanPham, TenSanPham, GiaBan, MaKhoHang) VALUES
-- (202, N'Máy in Canon', 2500000, 1000),
-- (203, N'Chuột Logitech', 350000, 1000);

-- -- Thêm khách hàng mới tại Site B
-- INSERT INTO KhachHang (MaKhachHang, TenKH, DiaChi, SoDienThoai) VALUES
-- (4, N'Lê Thị D', N'HCM', '0944444444');

-- -- Thêm hóa đơn mới tại Site B
-- INSERT INTO HoaDon (MaHoaDon, MaKhachHang, Ngay) VALUES
-- (1004, 4, SYSDATETIME());

-- -- Thêm chi tiết hóa đơn mới tại Site B
-- INSERT INTO ChiTietHoaDon (MaHoaDon, MaSanPham, SoLuong) VALUES
-- (1004, 202, 2),
-- (1004, 203, 3);

USE Shop;
GO

-- Kho hàng tại site B
INSERT INTO KhoHang (MaKhoHang, TenKhoHang, DiaChi) VALUES
(1000, N'Kho HCM', N'456 Nguyễn Huệ');

-- Sản phẩm tại site B
INSERT INTO SanPham (MaSanPham, TenSanPham, GiaBan, MaKhoHang) VALUES
(201, N'Laptop Dell', 15000000, 1000);

-- Khách hàng tại site B
INSERT INTO KhachHang (MaKhachHang, TenKH, DiaChi, SoDienThoai) VALUES
(2, N'Trần Thị B', N'HCM', '0922222222');

-- Hóa đơn tại site B
INSERT INTO HoaDon (MaHoaDon, MaKhachHang, Ngay) VALUES
(1002, 2, SYSDATETIME());

-- Chi tiết hóa đơn tại site B
INSERT INTO ChiTietHoaDon (MaHoaDon, MaSanPham, SoLuong) VALUES
(1002, 201, 1);