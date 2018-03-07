INSERT INTO vwservidor (Nome, Matricula, Cargo, Docente, Ativo) VALUES ('Antonio Malan','2255789', 'Diretor Geral',0, 1);
INSERT INTO vwservidor (Nome, Matricula, Cargo, Docente, Ativo) VALUES ('Tais Bogo','9875124', 'Administrador', 0, 1);

INSERT INTO unidade (Nome, Endereco, Cep, Latitude, Longitude, DiretorGeral, Administrador, Ativo) VALUES ('Campus Graciosa', 'Perto da UFT','77000000', '-10.182903','-48.360213', 1, 2, 1);

INSERT INTO vwcurso (nome, unidade, codcurso, sigla) VALUES ('Direito', 1, '1001', 'DIREITO' );
INSERT INTO vwcurso (nome, unidade, codcurso, sigla) VALUES ('Engenharia Agronômica', 1, '1002', 'ENG.EAGRO');
INSERT INTO vwcurso (nome, unidade, codcurso, sigla) VALUES ('Serviço Social', 1, '1003', 'SERV.SOCIAL');
INSERT INTO vwcurso (nome, unidade, codcurso, sigla) VALUES ('Sistemas de Informação', 1, '1004', 'SIS.INFO');

INSERT INTO predio (nome, pisos, unidade, ativo) VALUES ('Bloco A',2,1,1);
INSERT INTO predio (nome, pisos, unidade, ativo) VALUES ('Bloco B',2,1,1);
INSERT INTO predio (nome, pisos, unidade, ativo) VALUES ('Bloco C',2,1,0);

INSERT INTO entidade (descricao) VALUES ('Sala');
INSERT INTO entidade (descricao) VALUES ('Status');
INSERT INTO entidade (descricao) VALUES ('Equipamento');
INSERT INTO entidade (descricao) VALUES ('Prioridade');
INSERT INTO entidade (descricao) VALUES ('Turno');
INSERT INTO entidade (descricao) VALUES ('Dia da Semana');
INSERT INTO entidade (descricao) VALUES ('Tipo de Uso de Sala');

INSERT INTO registro (IdEntidade, Descricao, Ativo) VALUES (1, 'Sala', 1);
INSERT INTO registro (IdEntidade, Descricao, Ativo) VALUES (1, 'Laboratório', 1);
INSERT INTO registro (IdEntidade, Descricao, Ativo) VALUES (1, 'Sala de Estudo', 1);
INSERT INTO registro (IdEntidade, Descricao, Ativo) VALUES (3, 'Informatica', 1);
INSERT INTO registro (IdEntidade, Descricao, Ativo) VALUES (3, 'Moveis', 1);
INSERT INTO registro (IdEntidade, Descricao, Ativo) VALUES (3, 'Eletronicos', 1);
INSERT INTO registro (IdEntidade, Descricao, Ativo) VALUES (3, 'Multimídia', 1);
INSERT INTO registro (IdEntidade, Descricao, Ativo) VALUES (3, 'Outros', 1);
INSERT INTO registro (IdEntidade, Descricao, Ativo) VALUES (5, 'Matutino', 1);
INSERT INTO registro (IdEntidade, Descricao, Ativo) VALUES (5, 'Vespertino', 1);
INSERT INTO registro (IdEntidade, Descricao, Ativo) VALUES (5, 'Noturno', 1);
INSERT INTO registro (IdEntidade, Descricao, Ativo) VALUES (5, 'Outro', 1);
INSERT INTO registro (IdEntidade, Descricao, Ativo) VALUES (6, 'Segunda-Feira', 1);
INSERT INTO registro (IdEntidade, Descricao, Ativo) VALUES (6, 'Terça-Feira', 1);
INSERT INTO registro (IdEntidade, Descricao, Ativo) VALUES (6, 'Quarta-Feira', 1);
INSERT INTO registro (IdEntidade, Descricao, Ativo) VALUES (6, 'Quinta-Feira', 1);
INSERT INTO registro (IdEntidade, Descricao, Ativo) VALUES (6, 'Sexta-Feira', 1);
INSERT INTO registro (IdEntidade, Descricao, Ativo) VALUES (6, 'Sábado', 1);
INSERT INTO registro (IdEntidade, Descricao, Ativo) VALUES (7, 'Monitoria', 1);
INSERT INTO registro (IdEntidade, Descricao, Ativo) VALUES (7, 'Uso Comum', 1);
INSERT INTO registro (IdEntidade, Descricao, Ativo) VALUES (7, 'Uso Exclusivo', 1);
INSERT INTO registro (IdEntidade, Descricao, Ativo) VALUES (1, 'Sala de Orientação', 1);


INSERT INTO sala (nome, piso, predio,tipo,ativo) VALUES ('Sala 1', 2, 1, 1, 1);
INSERT INTO sala (nome, piso, predio,tipo,ativo) VALUES ('Sala 2', 2, 1, 1, 1);
INSERT INTO sala (nome, piso, predio,tipo,ativo) VALUES ('Sala 3', 2, 1, 1, 1);
INSERT INTO sala (nome, piso, predio,tipo,ativo) VALUES ('Sala 4', 2, 1, 1, 1 );
INSERT INTO sala (nome, piso, predio,tipo,ativo) VALUES ('Sala 5', 2, 1, 1, 1 );
INSERT INTO sala (nome, piso, predio,tipo,ativo) VALUES ('Sala 6', 2, 1, 1, 1 );
INSERT INTO sala (nome, piso, predio,tipo,ativo) VALUES ('Sala 7', 2, 1, 1, 1 );
INSERT INTO sala (nome, piso, predio,tipo,ativo) VALUES ('Sala 8', 2, 1, 1, 1 );
INSERT INTO sala (nome, piso, predio,tipo,ativo) VALUES ('Sala 9', 2, 1, 1, 1 );
INSERT INTO sala (nome, piso, predio,tipo,ativo) VALUES ('Sala 10', 2, 1, 1, 1 );
INSERT INTO sala (nome, piso, predio,tipo,ativo) VALUES ('Sala 11', 2, 1, 1, 1 );
INSERT INTO sala (nome, piso, predio,tipo,ativo) VALUES ('Sala 12', 2, 1, 1, 1 );
INSERT INTO sala (nome, piso, predio,tipo,ativo) VALUES ('Laboratório 1', 1, 2, 2, 1 );
INSERT INTO sala (nome, piso, predio,tipo,ativo) VALUES ('Laboratório 2', 1, 2, 2, 1 );
INSERT INTO sala (nome, piso, predio,tipo,ativo) VALUES ('Laboratório 3', 1, 2, 2, 1 );
INSERT INTO sala (nome, piso, predio,tipo,ativo) VALUES ('Laboratório de Microscopia', 1, 2, 2, 1 );
INSERT INTO sala (nome, piso, predio,tipo,ativo) VALUES ('Laboratório de Hardware', 1, 2, 2, 1 );

INSERT INTO turnohorarios (turno, horaainicio, horaafim, horabinicio, horabfim) VALUES (9,'08:00', '09:30', '09:45', '11:15');
INSERT INTO turnohorarios (turno, horaainicio, horaafim, horabinicio, horabfim) VALUES (10,'14:00', '15:30', '15:45', '17:15');
INSERT INTO turnohorarios (turno, horaainicio, horaafim, horabinicio, horabfim) VALUES (11,'19:00', '20:30', '20:45', '22:15');

INSERT INTO vwsemestre(descricao, datainicio, datafim) VALUES ('2017/1','2017-01-01','2017-07-25');
INSERT INTO vwsemestre(descricao, datainicio, datafim) VALUES ('2017/2','2017-08-01','2017-12-25');

INSERT INTO Criptografia(Chave) VALUES ('58d49ec46bf5524b4d46b57a43f9d0f41d29ec21');