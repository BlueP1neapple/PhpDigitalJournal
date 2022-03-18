--
-- PostgreSQL database dump
--

-- Dumped from database version 12.9 (Ubuntu 12.9-0ubuntu0.20.04.1)
-- Dumped by pg_dump version 12.9 (Ubuntu 12.9-0ubuntu0.20.04.1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: assessment_report; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.assessment_report (
    id integer NOT NULL,
    lesson_id integer,
    student_id integer,
    mark integer
);


ALTER TABLE public.assessment_report OWNER TO postgres;

--
-- Name: assessment_report_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.assessment_report_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.assessment_report_id_seq OWNER TO postgres;

--
-- Name: assessment_report_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.assessment_report_id_seq OWNED BY public.assessment_report.id;


--
-- Name: class; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.class (
    id integer NOT NULL,
    number integer,
    letter character varying(1)
);


ALTER TABLE public.class OWNER TO postgres;

--
-- Name: class_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.class_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.class_id_seq OWNER TO postgres;

--
-- Name: class_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.class_id_seq OWNED BY public.class.id;


--
-- Name: item; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.item (
    id integer NOT NULL,
    name character varying(255),
    description character varying(255)
);


ALTER TABLE public.item OWNER TO postgres;

--
-- Name: item_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.item_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.item_id_seq OWNER TO postgres;

--
-- Name: item_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.item_id_seq OWNED BY public.item.id;


--
-- Name: lesson; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.lesson (
    id integer NOT NULL,
    item_id integer,
    date timestamp without time zone,
    lesson_duration integer,
    teacher_id integer,
    class_id integer
);


ALTER TABLE public.lesson OWNER TO postgres;

--
-- Name: lesson_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.lesson_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.lesson_id_seq OWNER TO postgres;

--
-- Name: lesson_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.lesson_id_seq OWNED BY public.lesson.id;


--
-- Name: students_to_parents; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.students_to_parents (
    student_id integer,
    parent_id integer
);


ALTER TABLE public.students_to_parents OWNER TO postgres;

--
-- Name: users_teachers; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users_teachers (
    id integer NOT NULL,
    date_of_birth date,
    phone character varying(15),
    item_id integer,
    cabinet integer,
    email character varying(255),
    login character varying(255),
    password character varying(255),
    surname character varying(250),
    name character varying(250),
    patronymic character varying(250),
    street character varying(250),
    home character varying(250),
    apartment character varying(250)
);


ALTER TABLE public.users_teachers OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users_teachers.id;


--
-- Name: users_parents; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users_parents (
    id integer DEFAULT nextval('public.users_id_seq'::regclass) NOT NULL,
    date_of_birth date,
    phone character varying(15),
    place_of_work character varying(255),
    email character varying(250),
    login character varying(255),
    password character varying(255),
    surname character varying(250),
    name character varying(250),
    patronymic character varying(250),
    street character varying(250),
    home character varying(250),
    apartment character varying(250)
);


ALTER TABLE public.users_parents OWNER TO postgres;

--
-- Name: users_students; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users_students (
    id integer DEFAULT nextval('public.users_id_seq'::regclass) NOT NULL,
    date_of_birth date,
    phone character varying(15),
    class_id integer,
    parent_id integer,
    login character varying(255),
    password character varying(255),
    surname character varying(250),
    name character varying(250),
    patronymic character varying(250),
    street character varying(250),
    home character varying(250),
    apartment character varying(250)
);


ALTER TABLE public.users_students OWNER TO postgres;

--
-- Name: assessment_report id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assessment_report ALTER COLUMN id SET DEFAULT nextval('public.assessment_report_id_seq'::regclass);


--
-- Name: class id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.class ALTER COLUMN id SET DEFAULT nextval('public.class_id_seq'::regclass);


--
-- Name: item id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.item ALTER COLUMN id SET DEFAULT nextval('public.item_id_seq'::regclass);


--
-- Name: lesson id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lesson ALTER COLUMN id SET DEFAULT nextval('public.lesson_id_seq'::regclass);


--
-- Name: users_teachers id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users_teachers ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: assessment_report; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.assessment_report (id, lesson_id, student_id, mark) FROM stdin;
1	1	4	5
2	1	10	4
3	6	4	3
4	2	5	4
5	4	8	5
6	5	10	5
7	5	11	5
8	1	8	5
\.


--
-- Data for Name: class; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.class (id, number, letter) FROM stdin;
1	4	Б
2	3	А
3	6	А
\.


--
-- Data for Name: item; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.item (id, name, description) FROM stdin;
1	Математика	Математика
2	ОБЖ	Основы безопасности жизнедеятельности
3	Химия	Химия
\.


--
-- Data for Name: lesson; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.lesson (id, item_id, date, lesson_duration, teacher_id, class_id) FROM stdin;
1	1	2011-11-10 08:30:00	40	1	3
2	1	2011-11-10 10:30:00	40	1	1
3	1	2011-11-10 11:30:00	40	1	2
4	2	2011-11-11 12:30:00	40	2	1
5	2	2011-11-11 08:30:00	40	2	2
6	3	2011-11-11 10:30:00	40	3	1
7	3	2011-11-12 11:30:00	40	3	1
8	2	2011-11-12 12:30:00	40	2	1
10	1	2022-02-23 07:00:00	40	1	1
\.


--
-- Data for Name: students_to_parents; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.students_to_parents (student_id, parent_id) FROM stdin;
4	12
5	19
6	15
7	16
8	17
9	18
10	13
11	14
\.


--
-- Data for Name: users_parents; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users_parents (id, date_of_birth, phone, place_of_work, email, login, password, surname, name, patronymic, street, home, apartment) FROM stdin;
12	1975-10-01	+79222444488	ООО Алмаз	kuznecov@gmail.com	r1	$2y$10$Xd6fW4Y24KAVJ5B5hv9YUuBNJCBpSLtSbhdEtJh6t3cmfGoYxyDtG	Кузнецов	Евгений	Сергеевич	ул. Казанская	д. 35Б	кв. 23
13	1985-11-10	+79888444488	ООО Весна	krabov@gmail.com	p1	$2y$10$r8roUvRU3isynrDpqkeOb.FazrHESXg.twAt1k1TCu2WzxKiLhQp.	Крабов	Юрий	Владимирович	ул. Новая	д. 54	кв. 22
14	1980-09-01	+79222444656	ОАО Открытие	jasnova@gmail.com	p3	$2y$10$r8roUvRU3isynrDpqkeOb.FazrHESXg.twAt1k1TCu2WzxKiLhQp.	Яснова	Ксения	Евгеньевна	ул. Щавелева	д. 55Б	кв. 23
15	1978-02-10	+79322444423	ИП Круглов	smirnova@gmail.com	p4	$2y$10$r8roUvRU3isynrDpqkeOb.FazrHESXg.twAt1k1TCu2WzxKiLhQp.	Смирнова	Алина	Викторовна	ул. Круглова	д. 32	кв. 45
16	1987-10-23	+79234444488	ООО Зеленое-Красное	zaumova@gmail.com	p5	$2y$10$r8roUvRU3isynrDpqkeOb.FazrHESXg.twAt1k1TCu2WzxKiLhQp.	Заимова	Ксения	Евгеньевна	ул. Лужская	д. 34	кв. 34
17	1978-02-05	+79223333388	ИП Сергеев	kuznecova@gmail.com	p6	$2y$10$r8roUvRU3isynrDpqkeOb.FazrHESXg.twAt1k1TCu2WzxKiLhQp.	Кузнецова	Наталия	Михайловна	ул. Грузовая	д. 45	кв. 45
18	1978-11-22	+79222222222	ООО Кружка	solomova@gmail.com	p7	$2y$10$r8roUvRU3isynrDpqkeOb.FazrHESXg.twAt1k1TCu2WzxKiLhQp.	Соломона	Елена	Николаевна	ул. Красносельская	д. 65	кв. 56
19	1985-01-11	+79222433488	ООО Тесты	sokolova@gmail.com	p8	$2y$10$r8roUvRU3isynrDpqkeOb.FazrHESXg.twAt1k1TCu2WzxKiLhQp.	Соколова	Лидия	Михайловна	ул. Зеленская	д. 47	кв. 34
\.


--
-- Data for Name: users_students; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users_students (id, date_of_birth, phone, class_id, parent_id, login, password, surname, name, patronymic, street, home, apartment) FROM stdin;
4	2011-01-11	+79222444488	1	12	s0	$2y$10$DfTegEcz7NWykVBrhEJ5Q.q79CpkhOhZz/59YmFuhWjiigFobp76y	Кузнецов	Алексей	Евгеньевич	ул. Казанская	д. 35Б	кв. 23
5	2011-01-12	+79222433488	1	19	STU1	$2y$10$MXO8CjYCfoC3YPBj1mnRU.uXgVD8WDMJueiVFyiTNJcZC1mtvhLNW	Соколова	Алла	Юрьевна	ул. Зеленская	д. 47	кв. 34
6	2011-02-01	+79322444423	1	15	STU2	$2y$10$BHoHFYduN1Qd8tFf9w3TguLolPW.v4UEPHTs5R3jQvHjStA6SipFy	Смирнова	Елена	Владимировна	ул. Круглова	д. 32	кв. 45
7	2012-02-23	+79234444488	2	16	STU3	$2y$10$X0Td9cvSXsZLb5cWUwvtAuji71bk4eZiuDRFXxzNf0VZ6D458tH9i	Заимова	Ксения	Владимировна	ул. Лужская	д. 34	кв. 34
8	2012-11-12	+79223333388	2	17	STU4	$2y$10$vuMqZGfNfzgOMG3js2mXp.SCMolYcOnlRxM6PDWZPfA1qfJhrpnl.	Кузнецова	Анастасия	Сергеевна	ул. Грузовая	д. 45	кв. 45
9	2009-07-14	+79222222222	3	18	STU5	$2y$10$fk9/LExHgSGSCtlhMMWwiuLXLPUsRgW7HvsKldHoJLsmFuE22eqGu	Соломона	Елена	Дмитриевна	ул. Красносельская	д. 65	кв. 56
10	2009-04-23	+79888444488	3	13	STU6	$2y$10$GVyuJMu6et6MHo7oa3OwyuybSos0AoNkJgpPkHAsX7exzUck101k.	Крабов	Владимир	Юрьевич	ул. Новая	д. 54	кв. 22
11	2009-11-03	+79222444656	3	14	STU7	$2y$10$obk8vuhpS7gXa31FLbAAG.3IKskNv9f.HcQLzAfIvAv2pxk7mfJAy	Яснова	Мария	Сергеевна	ул. Щавелева	д. 55Б	кв. 23
\.


--
-- Data for Name: users_teachers; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users_teachers (id, date_of_birth, phone, item_id, cabinet, email, login, password, surname, name, patronymic, street, home, apartment) FROM stdin;
1	1965-01-11	+79222444411	1	56	kruglova@gmail.com	TEA0		Круглова	Наталия	Сергеевна	ул. Ясная	д. 54	кв. 19
2	1975-11-01	+79133243412	2	77	guseva@gmail.com	TEA2	$2y$10$bTpSRFaF9zOzDMtcLIVJ8.5fWACkaVPbAHaCG51865JTy/jgcOvFe	Гусева	Анна	Владимировна	ул. Зеленская	д. 22	кв. 11
3	1970-02-01	+79655346343	3	64	dmitriev@gmail.com	TEA3	$2y$10$s6qmGVK5SEoR.KbrDew2u.uWx9RdJAATl6FVSeE7Q7ggCsrWoM/Za	Дмитриев	Дмитрий	Алексеевна	ул. Круглова	д. 11	кв. 11
\.


--
-- Name: assessment_report_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.assessment_report_id_seq', 1, false);


--
-- Name: class_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.class_id_seq', 1, false);


--
-- Name: item_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.item_id_seq', 1, false);


--
-- Name: lesson_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.lesson_id_seq', 1, false);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 1, false);


--
-- Name: assessment_report assessment_report_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assessment_report
    ADD CONSTRAINT assessment_report_pk PRIMARY KEY (id);


--
-- Name: class class_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.class
    ADD CONSTRAINT class_pk PRIMARY KEY (id);


--
-- Name: item item_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.item
    ADD CONSTRAINT item_pk PRIMARY KEY (id);


--
-- Name: lesson lesson_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lesson
    ADD CONSTRAINT lesson_pk PRIMARY KEY (id);


--
-- Name: users_parents users_parents_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users_parents
    ADD CONSTRAINT users_parents_pk PRIMARY KEY (id);


--
-- Name: users_students users_students_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users_students
    ADD CONSTRAINT users_students_pk PRIMARY KEY (id);


--
-- Name: users_teachers users_teachers_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users_teachers
    ADD CONSTRAINT users_teachers_pk PRIMARY KEY (id);


--
-- Name: assessment_report assessment_report_lesson_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assessment_report
    ADD CONSTRAINT assessment_report_lesson_id_fk FOREIGN KEY (lesson_id) REFERENCES public.lesson(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: assessment_report assessment_report_student_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assessment_report
    ADD CONSTRAINT assessment_report_student_id_fk FOREIGN KEY (student_id) REFERENCES public.users_students(id) ON UPDATE RESTRICT ON DELETE CASCADE;


--
-- Name: lesson lesson_class_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lesson
    ADD CONSTRAINT lesson_class_id_fk FOREIGN KEY (class_id) REFERENCES public.class(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: lesson lesson_item_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lesson
    ADD CONSTRAINT lesson_item_id_fk FOREIGN KEY (item_id) REFERENCES public.item(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: lesson lesson_teacher_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lesson
    ADD CONSTRAINT lesson_teacher_id_fk FOREIGN KEY (teacher_id) REFERENCES public.users_teachers(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: students_to_parents students_to_parents_parents_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.students_to_parents
    ADD CONSTRAINT students_to_parents_parents_id_fk FOREIGN KEY (parent_id) REFERENCES public.users_parents(id) ON UPDATE RESTRICT ON DELETE CASCADE;


--
-- Name: students_to_parents students_to_parents_student_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.students_to_parents
    ADD CONSTRAINT students_to_parents_student_id_fk FOREIGN KEY (student_id) REFERENCES public.users_students(id) ON UPDATE RESTRICT ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

