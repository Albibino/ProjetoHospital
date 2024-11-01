--host=localhost port=5432 dbname=projetohospital user=postgres password=xxxxxxx
--dbname = projetohospital
--PostgreSQL 17


CREATE TABLE avaliacoes (
    id integer PRIMARY KEY;
    nota integer NOT NULL;
    data_avaliacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);

CREATE TABLE perguntas (
	id serial PRIMARY KEY,
	pergunta varchar NOT NULL,
	data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE dispositivos (
	id SERIAL PRIMARY KEY,
	nome_dispo varchar NOT NULL,
	setor_id integer NOT NULL
); 