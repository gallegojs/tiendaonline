create database tiendadeportes;

create table producto (
  id tinyint(4) not null primary key auto_increment,
  nombre varchar(200) not null,
  descripcion varchar(400) not null,
  precio decimal(10,2) not null,
  iva decimal(4,2) not null, 
  estado enum('activo', 'inactivo') not null default 'activo'
) engine=innodb charset=utf8 collate=utf8_unicode_ci;

create table foto (
  id tinyint(4) not null primary key auto_increment,
  idproducto tinyint(4) not null,
  nombre varchar(50) not null,
  CONSTRAINT foto_fk FOREIGN KEY (idproducto) REFERENCES producto (id) ON DELETE CASCADE
) engine=innodb charset=utf8 collate=utf8_unicode_ci;

create table venta (
  id int not null primary key auto_increment,
  fecha date not null,
  hora time not null, 
  pago enum('si', 'no', 'rev') not null default 'no',
  direccion varchar(400) not null,
  nombre varchar(200) not null,
  precio decimal(10,2) not null,
  iva decimal(4,2) not null
) engine=innodb charset=utf8 collate=utf8_unicode_ci;

create table detalleventa (
  id int not null primary key auto_increment,
  idventa int not null,
  idproducto tinyint(4) not null,
  cantidad tinyint(4) not null,
  precio decimal(10,2) not null,
  iva decimal(4,2) not null,
  CONSTRAINT detalleventa_fk FOREIGN KEY (idventa) REFERENCES venta (id) ON DELETE CASCADE
) engine=innodb charset=utf8 collate=utf8_unicode_ci;

create table paypal(
    idpaypal varchar(100) not null primary key,
    idpropio int not null,
    estado varchar(30) not null,
    importe decimal(10,2) not null,
    moneda varchar(10) not null,
    emailvendedor varchar(320) not null,
    emailcomprador varchar(320) not null
) engine=innodb charset=utf8 collate=utf8_unicode_ci;

/*

mc_gross --- 9.99
protection_eligibility --- Eligible
address_status --- unconfirmed
payer_id --- G7XCK384JYD2N
tax --- 0.00
address_street --- calle Vilamarí 76993- 17469
payment_date --- 03:44:44 Jan 27, 2015 PST
payment_status --- Completed
charset --- windows-1252
address_zip --- 02001
first_name --- Test
mc_fee --- 0.69
address_country_code --- ES
address_name --- Test Buyer
notify_version --- 3.8
custom --- 
payer_status --- verified
business --- javigallego93-facilitator@gmail.com
address_country --- Spain
address_city --- Albacete
quantity --- 1
verify_sign --- ARDqFvpLPXggEzBwMqTIa1XZy.HUAp5yCVctJMLQNuyFGqzxSDC4Fhh9
payer_email --- javigallego93-buyer@gmail.com
txn_id --- 41099938104538420
payment_type --- instant
last_name --- Buyer
address_state --- Albacete
receiver_email --- javigallego93-facilitator@gmail.com
payment_fee --- 
receiver_id --- AXVW92APRCF54
txn_type --- web_accept
item_name --- 1
mc_currency --- EUR
item_number --- 
residence_country --- ES
test_ipn --- 1
handling_amount --- 0.00
transaction_subject --- 
payment_gross --- 
shipping --- 0.00
ipn_track_id --- 24dc565bd1dd


*/