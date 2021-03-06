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
    mark integer NOT NULL
);


ALTER TABLE public.assessment_report OWNER TO postgres;

--
-- Name: assessment_report_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.assessment_report_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.assessment_report_id_seq OWNER TO postgres;

--
-- Name: class; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.class (
    id integer NOT NULL,
    number integer NOT NULL,
    letter character varying(1) NOT NULL
);


ALTER TABLE public.class OWNER TO postgres;

--
-- Name: class_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.class_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.class_id_seq OWNER TO postgres;

--
-- Name: item; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.item (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    description character varying(255) NOT NULL
);


ALTER TABLE public.item OWNER TO postgres;

--
-- Name: item_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.item_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.item_id_seq OWNER TO postgres;

--
-- Name: lesson; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.lesson (
    id integer NOT NULL,
    item_id integer,
    date timestamp(0) without time zone NOT NULL,
    lesson_duration integer NOT NULL,
    teacher_id integer,
    class_id integer
);


ALTER TABLE public.lesson OWNER TO postgres;

--
-- Name: COLUMN lesson.date; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.lesson.date IS '(DC2Type:datetime_immutable)';


--
-- Name: lesson_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.lesson_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.lesson_id_seq OWNER TO postgres;

--
-- Name: parents; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.parents (
    id integer NOT NULL,
    place_of_work character varying(255) NOT NULL,
    email character varying(255) NOT NULL
);


ALTER TABLE public.parents OWNER TO postgres;

--
-- Name: students; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.students (
    id integer NOT NULL,
    class_id integer NOT NULL
);


ALTER TABLE public.students OWNER TO postgres;

--
-- Name: students_to_parents; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.students_to_parents (
    student_id integer NOT NULL,
    parent_id integer NOT NULL
);


ALTER TABLE public.students_to_parents OWNER TO postgres;

--
-- Name: teachers; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.teachers (
    id integer NOT NULL,
    item_id integer,
    cabinet integer NOT NULL,
    email character varying(255) NOT NULL
);


ALTER TABLE public.teachers OWNER TO postgres;

--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id integer NOT NULL,
    login character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    surname character varying(255) NOT NULL,
    patronymic character varying(255) NOT NULL,
    street character varying(250) NOT NULL,
    home character varying(250) NOT NULL,
    apartment character varying(250) NOT NULL,
    phone character varying(15) NOT NULL,
    date_of_birth date NOT NULL,
    type character varying(100) NOT NULL
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: COLUMN users.date_of_birth; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.users.date_of_birth IS '(DC2Type:date_immutable)';


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO postgres;

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
9	8	8	5
11	15	11	1
13	1	4	1
15	1	4	1
\.


--
-- Data for Name: class; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.class (id, number, letter) FROM stdin;
1	4	??
2	3	??
3	6	??
\.


--
-- Data for Name: item; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.item (id, name, description) FROM stdin;
1	????????????????????	????????????????????
2	??????	???????????? ???????????????????????? ??????????????????????????????????
3	??????????	??????????
4	????????????????????	????????????????????
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
13	3	2022-04-06 09:45:00	40	3	1
15	2	2022-04-13 14:00:00	40	3	3
\.


--
-- Data for Name: parents; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.parents (id, place_of_work, email) FROM stdin;
12	?????? ??????????	kuznecov@gmail.com
13	?????? ??????????	krabov@gmail.com
14	?????? ????????????????	jasnova@gmail.com
15	???? ??????????????	smirnova@gmail.com
16	?????? ??????????????-??????????????	zaumova@gmail.com
17	???? ??????????????	kuznecova@gmail.com
18	?????? ????????????	solomova@gmail.com
19	?????? ??????????	sokolova@gmail.com
\.


--
-- Data for Name: students; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.students (id, class_id) FROM stdin;
4	1
5	1
6	1
7	2
8	2
9	3
10	3
11	3
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
-- Data for Name: teachers; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.teachers (id, item_id, cabinet, email) FROM stdin;
1	1	56	kruglova@gmail.com
2	2	77	guseva@gmail.com
3	3	64	dmitriev@gmail.com
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, login, password, name, surname, patronymic, street, home, apartment, phone, date_of_birth, type) FROM stdin;
2	TEA2	$2y$10$bTpSRFaF9zOzDMtcLIVJ8.5fWACkaVPbAHaCG51865JTy/jgcOvFe	????????	????????????	????????????????????????	????. ??????????????????	??. 22	????. 11	+79133243412	1975-11-01	Teacher
16	p5	$2y$10$r8roUvRU3isynrDpqkeOb.FazrHESXg.twAt1k1TCu2WzxKiLhQp.	????????????	??????????????	????????????????????	????. ??????????????	??. 34	????. 34	+79234444488	1987-10-23	Parent
18	p7	$2y$10$r8roUvRU3isynrDpqkeOb.FazrHESXg.twAt1k1TCu2WzxKiLhQp.	??????????	????????????????	????????????????????	????. ????????????????????????????	??. 65	????. 56	+79222222222	1978-11-22	Parent
17	p6	$2y$10$r8roUvRU3isynrDpqkeOb.FazrHESXg.twAt1k1TCu2WzxKiLhQp.	??????????????	??????????????????	????????????????????	????. ????????????????	??. 45	????. 45	+79223333388	1978-02-05	Parent
12	r1	$2y$10$Xd6fW4Y24KAVJ5B5hv9YUuBNJCBpSLtSbhdEtJh6t3cmfGoYxyDtG	??????????????	????????????????	??????????????????	????. ??????????????????	??. 35??	????. 23	+79222444488	1975-10-01	Parent
19	p8	$2y$10$r8roUvRU3isynrDpqkeOb.FazrHESXg.twAt1k1TCu2WzxKiLhQp.	??????????	????????????????	????????????????????	????. ??????????????????	??. 47	????. 34	+79222433488	1985-01-11	Parent
14	p3	$2y$10$r8roUvRU3isynrDpqkeOb.FazrHESXg.twAt1k1TCu2WzxKiLhQp.	????????????	????????????	????????????????????	????. ????????????????	??. 55??	????. 23	+79222444656	1980-09-01	Parent
13	p1	$2y$10$r8roUvRU3isynrDpqkeOb.FazrHESXg.twAt1k1TCu2WzxKiLhQp.	????????	????????????	????????????????????????	????. ??????????	??. 54	????. 22	+79888444488	1985-11-10	Parent
9	STU5	$2y$10$fk9/LExHgSGSCtlhMMWwiuLXLPUsRgW7HvsKldHoJLsmFuE22eqGu	??????????	????????????????	????????????????????	????. ????????????????????????????	??. 65	????. 56	+79222222222	2009-07-14	Student
8	STU4	$2y$10$vuMqZGfNfzgOMG3js2mXp.SCMolYcOnlRxM6PDWZPfA1qfJhrpnl.	??????????????????	??????????????????	??????????????????	????. ????????????????	??. 45	????. 45	+79223333388	2012-11-12	Student
11	STU7	$2y$10$obk8vuhpS7gXa31FLbAAG.3IKskNv9f.HcQLzAfIvAv2pxk7mfJAy	??????????	????????????	??????????????????	????. ????????????????	??. 55??	????. 23	+79222444656	2009-11-03	Student
10	STU6	$2y$10$GVyuJMu6et6MHo7oa3OwyuybSos0AoNkJgpPkHAsX7exzUck101k.	????????????????	????????????	??????????????	????. ??????????	??. 54	????. 22	+79888444488	2009-04-23	Student
5	STU1	$2y$10$MXO8CjYCfoC3YPBj1mnRU.uXgVD8WDMJueiVFyiTNJcZC1mtvhLNW	????????	????????????????	??????????????	????. ??????????????????	??. 47	????. 34	+79222433488	2011-01-12	Student
4	s0	$2y$10$DfTegEcz7NWykVBrhEJ5Q.q79CpkhOhZz/59YmFuhWjiigFobp76y	??????????????	????????????????	????????????????????	????. ??????????????????	??. 35??	????. 23	+79222444488	2011-01-11	Student
7	STU3	$2y$10$X0Td9cvSXsZLb5cWUwvtAuji71bk4eZiuDRFXxzNf0VZ6D458tH9i	????????????	??????????????	????????????????????????	????. ??????????????	??. 34	????. 34	+79234444488	2012-02-23	Student
6	STU2	$2y$10$BHoHFYduN1Qd8tFf9w3TguLolPW.v4UEPHTs5R3jQvHjStA6SipFy	??????????	????????????????	????????????????????????	????. ????????????????	??. 32	????. 45	+79322444423	2011-02-01	Student
1	TEA0	$2y$10$r8roUvRU3isynrDpqkeOb.FazrHESXg.twAt1k1TCu2WzxKiLhQp.	??????????????	????????????????	??????????????????	????. ??????????	??. 54	????. 19	+79222444411	1965-01-11	Teacher
3	TEA3	$2y$10$s6qmGVK5SEoR.KbrDew2u.uWx9RdJAATl6FVSeE7Q7ggCsrWoM/Za	??????????????	????????????????	????????????????????	????. ????????????????	??. 11	????. 11	+79655346343	1970-02-01	Teacher
15	p4	$2y$10$r8roUvRU3isynrDpqkeOb.FazrHESXg.twAt1k1TCu2WzxKiLhQp.	??????????	????????????????	????????????????????	????. ????????????????	??. 32	????. 45	+79322444423	1978-02-10	Parent
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
-- Name: parents parents_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.parents
    ADD CONSTRAINT parents_pk PRIMARY KEY (id);


--
-- Name: students students_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_pk PRIMARY KEY (id);


--
-- Name: students_to_parents students_to_parents_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.students_to_parents
    ADD CONSTRAINT students_to_parents_pkey PRIMARY KEY (parent_id, student_id);


--
-- Name: teachers teachers_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.teachers
    ADD CONSTRAINT teachers_pk PRIMARY KEY (id);


--
-- Name: users users_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pk PRIMARY KEY (id);


--
-- Name: item_name_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX item_name_idx ON public.item USING btree (name);


--
-- Name: teacher_cabinet_unq; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX teacher_cabinet_unq ON public.teachers USING btree (cabinet);


--
-- Name: teacher_email_unq; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX teacher_email_unq ON public.teachers USING btree (email);


--
-- Name: teacher_id_item_unq; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX teacher_id_item_unq ON public.teachers USING btree (item_id);


--
-- Name: users_login_unq; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX users_login_unq ON public.users USING btree (login);


--
-- Name: users_surname_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX users_surname_idx ON public.users USING btree (surname);


--
-- Name: users_type_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX users_type_idx ON public.users USING btree (type);


--
-- Name: assessment_report assessment_report_lesson_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assessment_report
    ADD CONSTRAINT assessment_report_lesson_id_fk FOREIGN KEY (lesson_id) REFERENCES public.lesson(id);


--
-- Name: assessment_report assessment_report_student_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assessment_report
    ADD CONSTRAINT assessment_report_student_id_fk FOREIGN KEY (student_id) REFERENCES public.students(id);


--
-- Name: lesson lesson_class_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lesson
    ADD CONSTRAINT lesson_class_id_fk FOREIGN KEY (class_id) REFERENCES public.class(id);


--
-- Name: lesson lesson_item_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lesson
    ADD CONSTRAINT lesson_item_id_fk FOREIGN KEY (item_id) REFERENCES public.item(id);


--
-- Name: lesson lesson_teacher_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lesson
    ADD CONSTRAINT lesson_teacher_id_fk FOREIGN KEY (teacher_id) REFERENCES public.teachers(id);


--
-- Name: parents parents_to_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.parents
    ADD CONSTRAINT parents_to_users_fk FOREIGN KEY (id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: students students_to_class_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_to_class_fk FOREIGN KEY (class_id) REFERENCES public.class(id);


--
-- Name: students_to_parents students_to_parents_to_parent_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.students_to_parents
    ADD CONSTRAINT students_to_parents_to_parent_fk FOREIGN KEY (parent_id) REFERENCES public.parents(id);


--
-- Name: students_to_parents students_to_parents_to_student_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.students_to_parents
    ADD CONSTRAINT students_to_parents_to_student_fk FOREIGN KEY (student_id) REFERENCES public.students(id);


--
-- Name: students students_to_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_to_users_fk FOREIGN KEY (id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: teachers teachers_student_to_patent_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.teachers
    ADD CONSTRAINT teachers_student_to_patent_id FOREIGN KEY (id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: teachers teachers_to_item_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.teachers
    ADD CONSTRAINT teachers_to_item_fk FOREIGN KEY (item_id) REFERENCES public.item(id);


--
-- PostgreSQL database dump complete
--

