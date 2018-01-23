INSERT INTO VwServidor (Nome, Matricula, Cargo, Docente, Ativo) VALUES ('Antonio Malan','2255789', 'Diretor Geral',0, 1);
INSERT INTO VwServidor (Nome, Matricula, Cargo, Docente, Ativo) VALUES ('Tais Bogo','9875124', 'Administrador', 0, 1);

INSERT INTO Unidade (Nome, Endereco, Cep, Latitude, Longitude, DiretorGeral, Administrador, Ativo) VALUES ('Campus Graciosa', 'Perto da UFT','77000000', '-10.182903','-48.360213', 1, 2, 1);

INSERT INTO VwCurso (Nome, Unidade, CodCurso, Sigla) VALUES ('Direito', 1, '1001', 'DIREITO' );
INSERT INTO VwCurso (Nome, Unidade, CodCurso, Sigla) VALUES ('Engenharia Agronômica', 1, '1002', 'ENG.EAGRO');
INSERT INTO VwCurso (Nome, Unidade, CodCurso, Sigla) VALUES ('Serviço Social', 1, '1003', 'SERV.SOCIAL');
INSERT INTO VwCurso (Nome, Unidade, CodCurso, Sigla) VALUES ('Sistemas de Informação', 1, '1004', 'SIS.INFO');

INSERT INTO Predio (Nome, Pisos, Unidade, Ativo) VALUES ('Bloco A',2,1,1);
INSERT INTO Predio (Nome, Pisos, Unidade, Ativo) VALUES ('Bloco B',2,1,1);
INSERT INTO Predio (Nome, Pisos, Unidade, Ativo) VALUES ('Bloco C',2,1,0);

INSERT INTO Entidade (Descricao) VALUES ('Sala');
INSERT INTO Entidade (Descricao) VALUES ('Status');
INSERT INTO Entidade (Descricao) VALUES ('Equipamento');
INSERT INTO Entidade (Descricao) VALUES ('Prioridade');
INSERT INTO Entidade (Descricao) VALUES ('Turno');
INSERT INTO Entidade (Descricao) VALUES ('Dia da Semana');
INSERT INTO Entidade (Descricao) VALUES ('Tipo de Uso de Sala');

INSERT INTO Registro (IdEntidade, Descricao, Ativo) VALUES (1, 'Sala', 1);
INSERT INTO Registro (IdEntidade, Descricao, Ativo) VALUES (1, 'Laboratório', 1);
INSERT INTO Registro (IdEntidade, Descricao, Ativo) VALUES (1, 'Sala de Estudo', 1);
INSERT INTO Registro (IdEntidade, Descricao, Ativo) VALUES (3, 'Informatica', 1);
INSERT INTO Registro (IdEntidade, Descricao, Ativo) VALUES (3, 'Moveis', 1);
INSERT INTO Registro (IdEntidade, Descricao, Ativo) VALUES (3, 'Eletronicos', 1);
INSERT INTO Registro (IdEntidade, Descricao, Ativo) VALUES (3, 'Multimídia', 1);
INSERT INTO Registro (IdEntidade, Descricao, Ativo) VALUES (3, 'Outros', 1);
INSERT INTO Registro (IdEntidade, Descricao, Ativo) VALUES (5, 'Matutino', 1);
INSERT INTO Registro (IdEntidade, Descricao, Ativo) VALUES (5, 'Vespertino', 1);
INSERT INTO Registro (IdEntidade, Descricao, Ativo) VALUES (5, 'NoTurno', 1);
INSERT INTO Registro (IdEntidade, Descricao, Ativo) VALUES (5, 'Outro', 1);
INSERT INTO Registro (IdEntidade, Descricao, Ativo) VALUES (6, 'Segunda-Feira', 1);
INSERT INTO Registro (IdEntidade, Descricao, Ativo) VALUES (6, 'Terça-Feira', 1);
INSERT INTO Registro (IdEntidade, Descricao, Ativo) VALUES (6, 'Quarta-Feira', 1);
INSERT INTO Registro (IdEntidade, Descricao, Ativo) VALUES (6, 'Quinta-Feira', 1);
INSERT INTO Registro (IdEntidade, Descricao, Ativo) VALUES (6, 'Sexta-Feira', 1);
INSERT INTO Registro (IdEntidade, Descricao, Ativo) VALUES (6, 'Sábado', 1);
INSERT INTO Registro (IdEntidade, Descricao, Ativo) VALUES (7, 'Monitoria', 1);
INSERT INTO Registro (IdEntidade, Descricao, Ativo) VALUES (7, 'Uso Comum', 1);
INSERT INTO Registro (IdEntidade, Descricao, Ativo) VALUES (7, 'Uso Exclusivo', 1);

INSERT INTO Sala (Nome, Piso, Predio, Tipo, Ativo) VALUES ('Sala 1', 2, 1, 1, 1);
INSERT INTO Sala (Nome, Piso, Predio, Tipo, Ativo) VALUES ('Sala 2', 2, 1, 1, 1);
INSERT INTO Sala (Nome, Piso, Predio, Tipo, Ativo) VALUES ('Sala 3', 2, 1, 1, 1);
INSERT INTO Sala (Nome, Piso, Predio, Tipo, Ativo) VALUES ('Sala 4', 2, 1, 1, 1 );
INSERT INTO Sala (Nome, Piso, Predio, Tipo, Ativo) VALUES ('Sala 5', 2, 1, 1, 1 );
INSERT INTO Sala (Nome, Piso, Predio, Tipo, Ativo) VALUES ('Sala 6', 2, 1, 1, 1 );
INSERT INTO Sala (Nome, Piso, Predio, Tipo, Ativo) VALUES ('Sala 7', 2, 1, 1, 1 );
INSERT INTO Sala (Nome, Piso, Predio, Tipo, Ativo) VALUES ('Sala 8', 2, 1, 1, 1 );
INSERT INTO Sala (Nome, Piso, Predio, Tipo, Ativo) VALUES ('Sala 9', 2, 1, 1, 1 );
INSERT INTO Sala (Nome, Piso, Predio, Tipo, Ativo) VALUES ('Sala 10', 2, 1, 1, 1 );
INSERT INTO Sala (Nome, Piso, Predio, Tipo, Ativo) VALUES ('Sala 11', 2, 1, 1, 1 );
INSERT INTO Sala (Nome, Piso, Predio, Tipo, Ativo) VALUES ('Sala 12', 2, 1, 1, 1 );
INSERT INTO Sala (Nome, Piso, Predio, Tipo, Ativo) VALUES ('Laboratório 1', 1, 2, 2, 1 );
INSERT INTO Sala (Nome, Piso, Predio, Tipo, Ativo) VALUES ('Laboratório 2', 1, 2, 2, 1 );
INSERT INTO Sala (Nome, Piso, Predio, Tipo, Ativo) VALUES ('Laboratório 3', 1, 2, 2, 1 );
INSERT INTO Sala (Nome, Piso, Predio, Tipo, Ativo) VALUES ('Laboratório de Microscopia', 1, 2, 2, 1 );
INSERT INTO Sala (Nome, Piso, Predio, Tipo, Ativo) VALUES ('Laboratório de Hardware', 1, 2, 2, 1 );

INSERT INTO TurnoHorarios (Turno, HoraAinicio, HoraAfim, HoraBinicio, HoraBfim) VALUES (9,'08:00', '09:30', '09:45', '11:15');
INSERT INTO TurnoHorarios (Turno, HoraAinicio, HoraAfim, HoraBinicio, HoraBfim) VALUES (10,'14:00', '15:30', '15:45', '17:15');
INSERT INTO TurnoHorarios (Turno, HoraAinicio, HoraAfim, HoraBinicio, HoraBfim) VALUES (11,'19:00', '20:30', '20:45', '22:15');

INSERT INTO VwSemestre(Descricao, DataInicio, DataFim) VALUES ('2017/1','2017-01-01','2017-07-25');
INSERT INTO VwSemestre(Descricao, DataInicio, DataFim) VALUES ('2017/2','2017-08-01','2017-12-25');