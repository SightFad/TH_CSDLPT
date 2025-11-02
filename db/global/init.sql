CREATE DATABASE Shop;
GO

USE Shop;
GO
-- Linked server tới site A
EXEC sp_addlinkedserver 
   @server = N'mssql_site_a_112541',
   @provider = N'MSOLEDBSQL',
   @srvproduct = N'',
   @datasrc = N'mssql_site_a_112541';

EXEC sp_addlinkedsrvlogin 
   @rmtsrvname = N'mssql_site_a_112541',
   @useself = 'false',
   @locallogin = NULL,
   @rmtuser = 'sa',
   @rmtpassword = 'Your@STROng!Pass#Word';

-- Linked server tới site B
EXEC sp_addlinkedserver 
   @server = N'mssql_site_b_112541',
   @provider = N'MSOLEDBSQL',
   @srvproduct = N'',
   @datasrc = N'mssql_site_b_112541';

EXEC sp_addlinkedsrvlogin 
   @rmtsrvname = N'mssql_site_b_112541',
   @useself = 'false',
   @locallogin = NULL,
   @rmtuser = 'sa',
   @rmtpassword = 'Your@STROng!Pass#Word';
GO
-- View toàn cục
CREATE VIEW SanPham AS
SELECT * FROM mssql_site_a_112541.Shop.dbo.SanPham
UNION ALL
SELECT * FROM mssql_site_b_112541.Shop.dbo.SanPham;