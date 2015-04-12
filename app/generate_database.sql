CREATE TABLE groups (
  group_id INT PRIMARY KEY NOT NULL,
  name VARCHAR(255)
);

INSERT INTO groups
(group_id, name)
VALUES
  (1, 'first'),
  (2, 'second'),
  (3, 'third'),
  (4, 'fourth'),
  (5, 'fifth'),
  (6, 'sixth');

CREATE TABLE taxes (
  tax_id SERIAL PRIMARY KEY NOT NULL,
  name VARCHAR(255) NOT NULL
);

INSERT INTO taxes
(name)
VALUES
  ('social'),
  ('unite');

CREATE TABLE ranges (
  range_id SERIAL PRIMARY KEY NOT NULL,
  name VARCHAR(255)
);

INSERT INTO ranges (name)
VALUES ('month'), ('quarter'), ('year');

CREATE TABLE taxes_for_groups (
  id SERIAL PRIMARY KEY NOT NULL,
  group_id INT REFERENCES groups,
  tax_id INT REFERENCES taxes,
  range_id INT REFERENCES ranges,
  days_to_pay INT NOT NULL,
  pre_paid BOOLEAN DEFAULT FALSE
);

INSERT INTO taxes_for_groups
(group_id, tax_id, range_id, days_to_pay, pre_paid)
VALUES
  (1, 1, 1, 20, false), -- first group social tax per month after pay
  (1, 1, 2, 20, false), -- first group social tax per quarter after pay
  (1, 2, 1, 20, true), -- first group unite tax per month pre pay

  (2, 1, 1, 20, false), -- second group social tax per month after pay
  (2, 1, 2, 20, false), -- second group social tax per quarter after pay
  (2, 2, 1, 20, true), -- second group unite tax per month pre pay

  (3, 1, 1, 20, false), -- third group social tax per month after pay
  (3, 1, 2, 20, false), -- third group social tax per quarter after pay
  (3, 2, 2, 50, false), -- third group unite tax per quarter after pay

  (5, 1, 1, 20, false), -- fifth group social tax per month after pay
  (5, 1, 2, 20, false), -- fifth group social tax per quarter after pay
  (5, 2, 2, 50, false), -- fifth group unite tax per quarter after pay

  (4, 2, 2, 50, false), -- fourth group unite tax per quarter after pay
  (6, 2, 2, 50, false); -- sixth group unite tax per quarter after pay

CREATE TABLE reports (
  report_id SERIAL PRIMARY KEY NOT NULL,
  group_id INT REFERENCES groups,
  range_id INT REFERENCES ranges,
  days_to_pay INT NOT NULL,
  continuing BOOLEAN DEFAULT false
);

INSERT INTO reports
(group_id, range_id, days_to_pay, continuing)
VALUES
  (1, 3, 40, false), -- first group per year not continuing
  (2, 3, 40, false), -- second group per year not continuing
  (3, 2, 40, true), -- third group per quarter continuing
  (5, 2, 40, true), -- fifth group per quarter continuing
  (4, 2, 40, true), -- fourth group per quarter continuing
  (6, 2, 40, true); -- sixth group per quarter continuing
