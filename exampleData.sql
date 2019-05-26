INSERT INTO indicators (name,update_type) VALUES('Valor 1',1);
INSERT INTO indicators (name,update_type) VALUES('Valor 2',1);
INSERT INTO indicators (name,update_type) VALUES('Valor 3',1);
INSERT INTO indicators (name,update_type) VALUES('Valor 4',1);
INSERT INTO indicators (name,update_type) VALUES('Valor 5',1);
INSERT INTO indicators (name,update_type) VALUES('Valor 6',1);
INSERT INTO indicators (name,update_type) VALUES('Valor 7',1);

INSERT INTO indicators_history (indicator_id,value) VALUES(1,500);
INSERT INTO indicators_history (indicator_id,value) VALUES(1,5400);
INSERT INTO indicators_history (indicator_id,value) VALUES(2,320);
INSERT INTO indicators_history (indicator_id,value) VALUES(2,5);
INSERT INTO indicators_history (indicator_id,value) VALUES(2,20.2);
INSERT INTO indicators_history (indicator_id,value) VALUES(3,15);
INSERT INTO indicators_history (indicator_id,value) VALUES(3,45462);
INSERT INTO indicators_history (indicator_id,value) VALUES(3,5252.52);
INSERT INTO indicators_history (indicator_id,value) VALUES(4,3434.2);
INSERT INTO indicators_history (indicator_id,value) VALUES(5,252.5);
INSERT INTO indicators_history (indicator_id,value) VALUES(6,515.7);
INSERT INTO indicators_history (indicator_id,value) VALUES(6,2323.2);
INSERT INTO indicators_history (indicator_id,value) VALUES(7,2323.2);
INSERT INTO indicators_history (indicator_id,value) VALUES(7,5435.9);
INSERT INTO indicators_history (indicator_id,value) VALUES(7,8989.7);
INSERT INTO indicators_history (indicator_id,value) VALUES(7,43434.23);

INSERT INTO units(name,code) VALUES('Centro','CNT');
INSERT INTO units(name,code) VALUES('Fragata','FRG');
INSERT INTO units(name,code) VALUES('Porto','PRT');
INSERT INTO units(name,code) VALUES('3 Vendas','3VD');
INSERT INTO units(name,code) VALUES('Simoes Lopes','SML');



INSERT INTO indicators_history_unit (indicator_history_id,indicator_id,unit_id) VALUES(13,7,1);
INSERT INTO indicators_history_unit (indicator_history_id,indicator_id,unit_id) VALUES(14,7,2);
INSERT INTO indicators_history_unit (indicator_history_id,indicator_id,unit_id) VALUES(15,7,3);
INSERT INTO indicators_history_unit (indicator_history_id,indicator_id,unit_id) VALUES(16,7,4);
