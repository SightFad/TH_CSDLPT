USE Shop;
GO
IF OBJECT_ID('dbo.HoaDon_Trig', 'V') IS NOT NULL DROP VIEW dbo.HoaDon_Trig;
GO

CREATE VIEW dbo.HoaDon_Trig AS
SELECT * FROM mssql_site_a_112541.Shop.dbo.HoaDon
WHERE MaHoaDon BETWEEN 10001 AND 110000
UNION ALL
SELECT * FROM mssql_site_b_112541.Shop.dbo.HoaDon
WHERE MaHoaDon BETWEEN 110001 AND 300000;
GO

-- Tạo trigger
CREATE TRIGGER trg_Insert_HoaDon
ON dbo.HoaDon_Trig
INSTEAD OF INSERT
AS
BEGIN
  INSERT INTO mssql_site_a_112541.Shop.dbo.HoaDon
  SELECT * FROM inserted WHERE MaHoaDon BETWEEN 10001 AND 110000;

  INSERT INTO mssql_site_b_112541.Shop.dbo.HoaDon
  SELECT * FROM inserted WHERE MaHoaDon BETWEEN 110001 AND 300000;
END;
GO

-- Kiểm thử
-- Q5.1: Ghi dữ liệu trong suốt bằng View có trigger
INSERT INTO HoaDon_Trig (MaHoaDon, MaKhachHang, Ngay)
VALUES (240001, 17500, SYSDATETIME()); 