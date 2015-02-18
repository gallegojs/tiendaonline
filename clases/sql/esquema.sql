create database tiendadeportes;

create table producto (
  id tinyint(4) not null primary key auto_increment,
  nombre varchar(200) not null,
  descripcion varchar(400) not null,
  precio decimal(10,2) not null,
  iva decimal(4,2) not null, 
  estado enum('activo', 'inactivo') not null default 'activo'
) engine=innodb charset=utf8 collate=utf8_unicode_ci;

create table fotos (
  id tinyint(4) not null primary key auto_increment,
  idproducto tinyint(4) not null,
  nombre varchar(50) not null,
  CONSTRAINT foto_fk FOREIGN KEY (idproducto) REFERENCES producto (id) ON DELETE CASCADE
) engine=innodb charset=utf8 collate=utf8_unicode_ci;

create table venta (
  id tinyint(4) not null primary key auto_increment,
  fecha date not null,
  hora time not null, 
  pago enum('si', 'no', 'rev') not null default 'no',
  direccion varchar(400) not null,
  nombre varchar(200) not null,
  precio decimal(10,2) not null,
  iva decimal(4,2) not null
) engine=innodb charset=utf8 collate=utf8_unicode_ci;

create table detalleventa (
  id tinyint(4) not null primary key auto_increment,
  idventa tinyint(4) not null,
  idproducto tinyint(4) not null,
  cantidad tinyint(4) not null,
  precio decimal(10,2) not null,
  iva decimal(4,2) not null
) engine=innodb charset=utf8 collate=utf8_unicode_ci;

