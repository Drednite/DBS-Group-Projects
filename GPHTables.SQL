drop table participant, attendance, member, roles, performance, member_ticket_sales, guest_performer, guest_member CASCADE;

create table participant(participant_id int, last_name varchar(20), first_name varchar(20), preferred_name varchar(20), street_address varchar(50), city varchar(50), state varchar(50), 
zip int, home_phone varchar(15), cell_phone varchar(15), work_phone varchar(15), email varchar(50), birthdate date, spouse_first varchar(20), spouse_last varchar(20), 
spouse_preferred varchar(20), on_facebook boolean, voice_part varchar(20), primary key(participant_id));

create table attendance(attendance_id int, participant_id int, attend_date timestamp, primary key(attendance_id), foreign key(participant_id) references participant(participant_id));

create table member(member_id int, participant_id int, youth_form boolean, arrangement varchar(50), vest_size varchar(3), generation varchar(20), primary key(member_id), 
foreign key(participant_id) references participant(participant_id));

create table roles(role_id int, member_id int, name varchar(50), primary key(role_id), foreign key(member_id) references member(member_id));

create table performance(performance_id int, performance_date timestamp, name varchar(50), online_sales boolean, venue_name varchar(50), street varchar(50), city varchar(50), 
state varchar(50), zip int, primary key(performance_id));

create table member_ticket_sales(sales_id int, member_id int, tickets_given int, tickets_returned int, adult_sold int, student_youth_sold int, funds_collected decimal(8,2), performance_id int, 
primary key(sales_id), foreign key(member_id) references member(member_id), foreign key(performance_id) references performance(performance_id));

create table guest_performer(guest_id int, performance_id int, group_name varchar(50), primary key(guest_id), foreign key(performance_id) references performance(performance_id));

create table guest_member(guest_member_id int, guest_id int, last_name varchar(20), first_name varchar(20), preferred_name varchar(20), primary key(member_id), 
foreign key(guest_id) references guest_performer(guest_id));
                                                 
INSERT INTO participant VALUES (1, 'Doe', 'Johnson', 'John', '123 1st St N', 'Fargo', 'North Dakota', 58102, '701-231-8523', , , 'Johnson.Doe@gmail.com', TO_DATE('01/01/2001', 'DD/MM/YYYY'), Jane, Doe, , 1, 'Tenor');
INSERT INTO attendance VALUES (1, 1, TO_TIMESTAMP('04/26/2021 07:30:00 PM', 'DD/MM/YYYY HH12:MI:SS'));
INSERT INTO member VALUES (1, 1, 'Yes', 'Row 4 Col 3', 'L', 'Boomer');
INSERT INTO roles VALUES (1, 1, 'President');
INSERT INTO performance VALUES (1, TO_TIMESTAMP('04/26/2021 07:30:00 PM', 'DD/MM/YYYY HH12:MI:SS'), 'Gospel Show Spring 2021', 1, 'Bethel Church', '2702 30th Ave S', 'Fargo', 'ND', 58103);
INSERT INTO member_ticket_sales VALUES (1, 1, 30, 12, 12, 6, 300.00, 1);
INSERT INTO guest_performer VALUES (1, 1, 'Great Plains Resonance');
INSERT INTO guest_member VALUES (1, 1, 'Erickson', 'Jacob', );
