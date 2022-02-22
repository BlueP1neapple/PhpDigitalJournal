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
-- Name: address; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.address (
    street character varying(255),
    home character varying(100),
    apartment character varying(100),
    user_id integer
);


ALTER TABLE public.address OWNER TO postgres;

--
-- Name: assessment_report; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.assessment_report (
    id integer,
    lesson_id integer,
    student_id integer,
    mark integer
);


ALTER TABLE public.assessment_report OWNER TO postgres;

--
-- Name: class; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.class (
    id integer,
    number integer,
    letter character varying(1)
);


ALTER TABLE public.class OWNER TO postgres;

--
-- Name: fio; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.fio (
    name character varying(50),
    surname character varying(50),
    patronymic character varying(50),
    user_id integer
);


ALTER TABLE public.fio OWNER TO postgres;

--
-- Name: item; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.item (
    id integer,
    name character varying(255),
    description character varying(255)
);


ALTER TABLE public.item OWNER TO postgres;

--
-- Name: lesson; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.lesson (
    id integer,
    item_id integer,
    date timestamp without time zone,
    lesson_duration integer,
    teacher_id integer,
    class_id integer
);


ALTER TABLE public.lesson OWNER TO postgres;

--
-- Name: users_parents; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users_parents (
    id integer,
    date_of_birth date,
    phone character varying(15),
    place_of_work character varying(255),
    email character varying(250),
    login character varying(255),
    password character varying(255)
);


ALTER TABLE public.users_parents OWNER TO postgres;

--
-- Name: users_students; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users_students (
    id integer,
    date_of_birth date,
    phone character varying(15),
    class_id integer,
    parent_id integer,
    login character varying(255),
    password character varying(255)
);


ALTER TABLE public.users_students OWNER TO postgres;

--
-- Name: users_teachers; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users_teachers (
    id integer,
    date_of_birth date,
    phone character varying(15),
    item_id integer,
    cabinet integer,
    email character varying(255),
    login character varying(255),
    password character varying(255)
);


ALTER TABLE public.users_teachers OWNER TO postgres;

--
-- Data for Name: address; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.address (street, home, apartment, user_id) FROM stdin;
\.


--
-- Data for Name: assessment_report; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.assessment_report (id, lesson_id, student_id, mark) FROM stdin;
\.


--
-- Data for Name: class; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.class (id, number, letter) FROM stdin;
\.


--
-- Data for Name: fio; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.fio (name, surname, patronymic, user_id) FROM stdin;
\.


--
-- Data for Name: item; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.item (id, name, description) FROM stdin;
\.


--
-- Data for Name: lesson; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.lesson (id, item_id, date, lesson_duration, teacher_id, class_id) FROM stdin;
\.


--
-- Data for Name: users_parents; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users_parents (id, date_of_birth, phone, place_of_work, email, login, password) FROM stdin;
\.


--
-- Data for Name: users_students; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users_students (id, date_of_birth, phone, class_id, parent_id, login, password) FROM stdin;
\.


--
-- Data for Name: users_teachers; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users_teachers (id, date_of_birth, phone, item_id, cabinet, email, login, password) FROM stdin;
\.


--
-- PostgreSQL database dump complete
--

