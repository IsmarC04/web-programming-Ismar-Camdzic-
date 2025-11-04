create table users (
	user_id int auto_increment primary key,
	name varchar(100),
	email varchar(255),
	password varchar(255),
	reviews_id int

);


create table services (
	service_id int auto_increment primary key,
	name varchar (100),
	description text,
	price float,
	duration int,
	admin_id int
);


create table reviews (
	reviews_id int auto_increment primary key,
	user_id int,
	stylist_id int,
	rating tinyint
);


create table stylists ( 
	stylist_id int primary key,
	name varchar(100),
	email varchar(255),
	bio text,
	password varchar(255),
	admin_id int,
	appointment_id int
);

create table appointments (
	appintment_id int primary key,
	user_id int,
	service_id int,
	stylist_id int,
	appointments_date date,
	status enum('active', 'inactive') default 'active',
	admin_id int,
	reviews_id int
);


create table admins (
	admin_id int auto_increment primary key,
	name varchar(100),
	email varchar(100),
	service_id int,
	appointment_id int
)


create table notifications( 
	notification_id int primary key,
	appointment_id int,
	message text,
	status enum('unread', 'read')
)

select*from users
select*from admins


