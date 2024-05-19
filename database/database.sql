PRAGMA foreign_keys = ON;

DROP TABLE IF EXISTS message;
DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS item;
DROP TABLE IF EXISTS category;
DROP TABLE IF EXISTS user;

/*******************************************************************************
   Criar tabelas
********************************************************************************/
-- Criação da tabela de utilizadores
CREATE TABLE IF NOT EXISTS user (
    user_id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    email TEXT NOT NULL UNIQUE,
    wishlist TEXT,
    address VARCHAR(255),
    postal_code VARCHAR(20),
    currency VARCHAR(10),
    profile_picture TEXT DEFAULT 'default',
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    location TEXT NOT NULL DEFAULT 'Portugal'
);

-- Criação da tabela de categorias de produtos
CREATE TABLE IF NOT EXISTS category (
    category_id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE,
    icon_path TEXT DEFAULT 'images/categories/default'
);

-- Criação da tabela de itens
CREATE TABLE IF NOT EXISTS item (
    item_id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    description TEXT NOT NULL,
    price REAL NOT NULL,
    category_id INTEGER,
    seller_id INTEGER,
    item_pictures TEXT DEFAULT 'not_found',
    status TEXT NOT NULL DEFAULT 'available', -- Exemplo: available, sold
    post_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES category (category_id),
    FOREIGN KEY (seller_id) REFERENCES user (user_id)
);

-- Criação da tabela de transações
CREATE TABLE IF NOT EXISTS transactions (
    transaction_id INTEGER PRIMARY KEY AUTOINCREMENT,
    item_id text not null,
    buyer_id INTEGER,
    seller_id text not null,
    name text not null,
    address text not null,
    total_price REAL NOT NULL,
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES item (item_id)
        ON DELETE NO ACTION ON UPDATE NO ACTION,
    FOREIGN KEY (buyer_id) REFERENCES user (user_id)
        ON DELETE NO ACTION ON UPDATE NO ACTION,
    FOREIGN KEY (seller_id) REFERENCES user (user_id)
        ON DELETE NO ACTION ON UPDATE NO ACTION
);

-- Criação da tabela de mensagens
CREATE TABLE IF NOT EXISTS message (
    message_id INTEGER PRIMARY KEY AUTOINCREMENT,
    from_user_id INTEGER,
    to_user_id INTEGER,
    item_id INTEGER,
    message TEXT NOT NULL,
    send_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (from_user_id) REFERENCES user (user_id)
        ON DELETE NO ACTION ON UPDATE NO ACTION,
    FOREIGN KEY (to_user_id) REFERENCES user (user_id)
        ON DELETE NO ACTION ON UPDATE NO ACTION,
    FOREIGN KEY (item_id) REFERENCES item (item_id)
        ON DELETE NO ACTION ON UPDATE NO ACTION
);

/*******************************************************************************
   Povoar tabelas
********************************************************************************/
-- Povoamento da tabela de utilizadores
INSERT INTO user (username, password, email, profile_picture,is_admin, adress, postal_code, currency) 
VALUES



-- Povoamento da tabela de categorias
INSERT INTO category (name, icon_path)
VALUES 
       ('Football', '/../database/images/categories_icon/american.png'),
       ('Basketball', '/../database/images/categories_icon/basketball.png'),
       ('Baseball', '/../database/images/categories_icon/baseball.png'),
       ('Soccer', '/../database/images/categories_icon/futebol.png'),
       ('Tennis', '/../database/images/categories_icon/tennis.png'),
       ('Golf', '/../database/images/categories_icon/golf.png'),
       ('Swimming', '/../database/images/categories_icon/swimming.png'),
       ('Cycling', '/../database/images/categories_icon/ciclying.png'),
       ('Running', '/../database/images/categories_icon/running.png'),
       ('Boxing', '/../database/images/categories_icon/boxing.png'),
       ('Martial Arts', '/../database/images/categories_icon/marcialArts.png'),
       ('Yoga and Pilates', '/../database/images/categories_icon/yoga.png'),
       ('Hiking and Camping', '/../database/images/categories_icon/camping.png'),
       ('Fitness Equipment', '/../database/images/categories_icon/fitness.png'),
       ('Winter Sports', '/../database/images/categories_icon/winter.png');

INSERT INTO item (title, description, price, category_id, seller_id, item_pictures)
VALUES
    
