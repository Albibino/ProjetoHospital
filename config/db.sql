BEGIN;


CREATE TABLE IF NOT EXISTS public.dispositivos
(
    id serial NOT NULL,
    nome_dispo character varying COLLATE pg_catalog."default" NOT NULL,
    setor_id integer NOT NULL,
    CONSTRAINT dispositivos_pkey PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS public.questoes
(
    id serial NOT NULL,
    texto character varying(255) COLLATE pg_catalog."default" NOT NULL,
    CONSTRAINT questoes_pkey PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS public.respostas
(
    id serial NOT NULL,
    questao_id integer,
    resposta integer NOT NULL,
    CONSTRAINT respostas_pkey PRIMARY KEY (id)
);

ALTER TABLE IF EXISTS public.respostas
    ADD CONSTRAINT respostas_questao_id_fkey FOREIGN KEY (questao_id)
    REFERENCES public.questoes (id) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE NO ACTION;

END;