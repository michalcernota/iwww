CREATE TABLE users (
    id            NUMBER(3) NOT NULL PRIMARY KEY,
    username      VARCHAR2(30 CHAR) NOT NULL,
    password      VARCHAR2(30 CHAR) NOT NULL,
    email         VARCHAR2(30 CHAR) NOT NULL,
    description   VARCHAR2(100 CHAR),
    created       DATE NOT NULL
);

CREATE SEQUENCE users_id_seq START WITH 1 NOCACHE ORDER;

CREATE OR REPLACE TRIGGER users_id_trg BEFORE
    INSERT ON users
    FOR EACH ROW
    WHEN ( new.id IS NULL )
BEGIN
    :new.id := users_id_seq.nextval;
END;