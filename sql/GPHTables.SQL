drop table participant, attendance, member, roles, performance, member_ticket_sales, guest_performer, guest_member, starring CASCADE;

create table participant(participant_id int, last_name varchar(20), first_name varchar(20), preferred_name varchar(20), street_address varchar(50), city varchar(50), state varchar(50), 
zip int, home_phone varchar(15), cell_phone varchar(15), work_phone varchar(15), email varchar(50), birthdate date, spouse_first varchar(20), spouse_last varchar(20), 
spouse_preferred varchar(20), on_facebook boolean, voice_part varchar(20), primary key(participant_id));

create table attendance(attendance_id int, participant_id int, attend_date timestamp, primary key(attendance_id), foreign key(participant_id) references participant(participant_id));

create table member(member_id int, participant_id int, youth_form boolean, arrangement varchar(50), vest_size varchar(3), generation varchar(20), primary key(member_id), 
foreign key(participant_id) references participant(participant_id));

create table roles(role_id int, member_id int, name varchar(50), primary key(role_id), foreign key(member_id) references member(member_id));

create table performance(performance_id int, performance_date timestamp, name varchar(50), venue_name varchar(50), street varchar(50), city varchar(50), 
state varchar(50), zip int, online_sales boolean, primary key(performance_id));

create table member_ticket_sales(sales_id int, performance_id int, member_id int, tickets_given int, tickets_returned int, adult_sold int, student_youth_sold int, funds_collected decimal(8,2), 
primary key(sales_id), foreign key(member_id) references member(member_id), foreign key(performance_id) references performance(performance_id));

create table guest_performer(guest_id int, group_name varchar(50), primary key(guest_id));

create table guest_member(guest_member_id int, guest_id int, last_name varchar(20), first_name varchar(20), preferred_name varchar(20), primary key(guest_member_id), 
foreign key(guest_id) references guest_performer(guest_id));

CREATE TABLE starring(starring_id int, performance_id int, guest_id int, primary key (starring_id), foreign key (guest_id) references guest_performer(guest_id), foreign key (performance_id) references performance(performance_id));

INSERT INTO participant VALUES (1, 'Doe1', 'Johnson', 'John', '123 1st St N', 'Fargo', 'North Dakota', 58102, '701-231-8523', '701-444-3333', '701-222-555', 'Johnson.Doe@gmail.com', TO_DATE('01/01/2001', 'DD/MM/YYYY'), 'Jane', 'Doe', 'Jane', 'Yes', 'Tenor');
INSERT INTO participant VALUES (2, 'Doe2', 'Johnson', 'John', '123 1st St N', 'Fargo', 'North Dakota', 58102, '701-231-8523', '701-444-3333', '701-222-555', 'Johnson.Doe@gmail.com', TO_DATE('01/01/2001', 'DD/MM/YYYY'), 'Jane', 'Doe', 'Jane', 'Yes', 'Tenor');
INSERT INTO participant VALUES (3, 'Doe3', 'Johnson', 'John', '123 1st St N', 'Fargo', 'North Dakota', 58102, '701-231-8523', '701-444-3333', '701-222-555', 'Johnson.Doe@gmail.com', TO_DATE('01/01/2001', 'DD/MM/YYYY'), 'Jane', 'Doe', 'Jane', 'Yes', 'Tenor');
INSERT INTO participant VALUES (4, 'Doe4', 'Johnson', 'John', '123 1st St N', 'Fargo', 'North Dakota', 58102, '701-231-8523', '701-444-3333', '701-222-555', 'Johnson.Doe@gmail.com', TO_DATE('01/01/2001', 'DD/MM/YYYY'), 'Jane', 'Doe', 'Jane', 'Yes', 'Tenor');
INSERT INTO attendance VALUES (1, 1, TO_TIMESTAMP('26/04/2021 07:30:00 PM', 'DD/MM/YYYY HH12:MI:SS'));
INSERT INTO attendance VALUES (2, 2, TO_TIMESTAMP('26/04/2021 07:30:00 PM', 'DD/MM/YYYY HH12:MI:SS'));
INSERT INTO attendance VALUES (3, 3, TO_TIMESTAMP('26/04/2021 07:30:00 PM', 'DD/MM/YYYY HH12:MI:SS'));
INSERT INTO attendance VALUES (4, 4, TO_TIMESTAMP('26/04/2021 07:30:00 PM', 'DD/MM/YYYY HH12:MI:SS'));
INSERT INTO member VALUES (1, 1, 'Yes', 'Row 4 Col 3', 'L', 'Boomer');
INSERT INTO member VALUES (2, 2, 'No', 'Row 4 Col 3', 'L', 'Boomer');
INSERT INTO roles VALUES (1, 1, 'President');
INSERT INTO roles VALUES (2, 2, 'Vice President');
INSERT INTO roles VALUES (3, 2, 'Pizza Man');
INSERT INTO performance VALUES (1, TO_TIMESTAMP('26/04/2021 07:30:00 PM', 'DD/MM/YYYY HH12:MI:SS'), 'Gospel Show Spring 2021', 'Bethel Church', '2702 30th Ave S', 'Fargo', 'ND', 58103, 'Yes');
INSERT INTO performance VALUES (2, TO_TIMESTAMP('26/04/2022 07:30:00 PM', 'DD/MM/YYYY HH12:MI:SS'), 'Gospel Show Spring 2022', 'Bethel Church', '2702 30th Ave S', 'Fargo', 'ND', 58103, 'No');
INSERT INTO member_ticket_sales VALUES (1, 1, 1, 30, 12, 12, 6, 300.00);
INSERT INTO member_ticket_sales VALUES (1, 1, 2, 30, 12, 12, 6, 300.00);
INSERT INTO member_ticket_sales VALUES (1, 2, 1, 30, 12, 12, 6, 300.00);
INSERT INTO member_ticket_sales VALUES (1, 2, 2, 30, 12, 12, 6, 300.00);
INSERT INTO guest_performer VALUES (1, 'Great Plains Resonance');
INSERT INTO guest_performer VALUES (2, 'Individual Artist');
INSERT INTO guest_member VALUES (1, 1, 'Erickson1', 'Jacob');
INSERT INTO guest_member VALUES (2, 1, 'Erickson2', 'Jacob');
INSERT INTO guest_member VALUES (3, 2, 'Erickson3', 'Jacob');
INSERT INTO starring VALUES (1, 1, 1);
INSERT INTO starring VALUES (1, 1, 2);
INSERT INTO starring VALUES (1, 2, 2);
