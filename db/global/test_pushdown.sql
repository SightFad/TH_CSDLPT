USE Shop;
GO
-- Q4.1: Truy vấn trực tiếp qua linked server:
SELECT *
FROM mssql_site_b_112541.Shop.dbo.SanPham
WHERE GiaBan > 1000000;

-- Q4.2: Đẩy filter xuống site B qua OPENQUERY:
SELECT *
FROM OPENQUERY(mssql_site_b_112541, 
  'SELECT MaSanPham, TenSanPham, GiaBan 
   FROM Shop.dbo.SanPham 
   WHERE GiaBan > 1000000');