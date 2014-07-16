-- Script was generated by Devart dbForge Studio for MySQL, Version 6.0.151.0
-- Product home page: http://www.devart.com/dbforge/mysql/studio
-- Script date 7/3/2014 11:17:43 PM
-- Server version: 5.6.16
-- Client version: 4.1

-- 
-- Set default database
--
USE db_rms_for_secheo;

--
-- Definition for table account_types
--
DROP TABLE IF EXISTS account_types;
CREATE TABLE IF NOT EXISTS account_types (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(50) NOT NULL,
  is_default smallint(6) NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE INDEX UNIQ_6FBF50415E237E06 (name)
)
ENGINE = INNODB
AUTO_INCREMENT = 4
AVG_ROW_LENGTH = 5461
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- Definition for table companies
--
DROP TABLE IF EXISTS companies;
CREATE TABLE IF NOT EXISTS companies (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(50) NOT NULL,
  status smallint(6) NOT NULL DEFAULT 1,
  description varchar(500) NOT NULL,
  creation_date datetime NOT NULL,
  modification_date datetime NOT NULL,
  PRIMARY KEY (id),
  UNIQUE INDEX UNIQ_8244AA3A5E237E06 (name)
)
ENGINE = INNODB
AUTO_INCREMENT = 4
AVG_ROW_LENGTH = 5461
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- Definition for table countries
--
DROP TABLE IF EXISTS countries;
CREATE TABLE IF NOT EXISTS countries (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(50) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE INDEX UNIQ_5D66EBAD5E237E06 (name)
)
ENGINE = INNODB
AUTO_INCREMENT = 11
AVG_ROW_LENGTH = 1638
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- Definition for table currencies
--
DROP TABLE IF EXISTS currencies;
CREATE TABLE IF NOT EXISTS currencies (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(50) NOT NULL,
  code varchar(3) NOT NULL,
  is_local smallint(6) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE INDEX UNIQ_37C446935E237E06 (name),
  UNIQUE INDEX UNIQ_37C4469377153098 (code)
)
ENGINE = INNODB
AUTO_INCREMENT = 3
AVG_ROW_LENGTH = 8192
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- Definition for table plots
--
DROP TABLE IF EXISTS plots;
CREATE TABLE IF NOT EXISTS plots (
  id int(11) NOT NULL AUTO_INCREMENT,
  plot_no int(11) NOT NULL,
  kanal smallint(6) DEFAULT NULL,
  marla smallint(6) DEFAULT NULL,
  square_feet decimal(8, 2) DEFAULT NULL,
  is_commercial smallint(6) DEFAULT NULL,
  creation_date datetime NOT NULL,
  modification_date datetime NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- Definition for table resources
--
DROP TABLE IF EXISTS resources;
CREATE TABLE IF NOT EXISTS resources (
  id int(11) NOT NULL AUTO_INCREMENT,
  parent_id int(11) DEFAULT NULL,
  name varchar(50) NOT NULL,
  code varchar(50) DEFAULT NULL,
  level int(11) DEFAULT NULL,
  serial int(11) DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX IDX_EF66EBAE727ACA70 (parent_id),
  CONSTRAINT FK_EF66EBAE727ACA70 FOREIGN KEY (parent_id)
  REFERENCES resources (id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- Definition for table branches
--
DROP TABLE IF EXISTS branches;
CREATE TABLE IF NOT EXISTS branches (
  id int(11) NOT NULL AUTO_INCREMENT,
  company_id int(11) NOT NULL,
  name varchar(50) NOT NULL,
  branch_type smallint(6) NOT NULL DEFAULT 3,
  status smallint(6) NOT NULL DEFAULT 1,
  description varchar(500) DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX IDX_D760D16F979B1AD6 (company_id),
  UNIQUE INDEX name_company_index (name, company_id),
  CONSTRAINT FK_D760D16F979B1AD6 FOREIGN KEY (company_id)
  REFERENCES companies (id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 2
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- Definition for table members
--
DROP TABLE IF EXISTS members;
CREATE TABLE IF NOT EXISTS members (
  id int(11) NOT NULL AUTO_INCREMENT,
  plot_id int(11) NOT NULL,
  old_member_id int(11) NOT NULL,
  code varchar(60) NOT NULL,
  name varchar(60) NOT NULL,
  image_name varchar(60) NOT NULL,
  nic varchar(15) NOT NULL,
  fname varchar(60) DEFAULT NULL,
  sname varchar(60) DEFAULT NULL,
  address varchar(150) DEFAULT NULL,
  status smallint(6) NOT NULL DEFAULT 1,
  is_private smallint(6) NOT NULL DEFAULT 1,
  creation_date datetime NOT NULL,
  modification_date datetime NOT NULL,
  PRIMARY KEY (id),
  INDEX IDX_45A0D2FF680D0B01 (plot_id),
  UNIQUE INDEX UNIQ_45A0D2FF77153098 (code),
  UNIQUE INDEX UNIQ_45A0D2FFAC199498 (image_name),
  UNIQUE INDEX UNIQ_45A0D2FFD4E6F81 (address),
  UNIQUE INDEX UNIQ_45A0D2FFDD8CDF34 (nic),
  CONSTRAINT FK_45A0D2FF680D0B01 FOREIGN KEY (plot_id)
  REFERENCES plots (id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- Definition for table roles
--
DROP TABLE IF EXISTS roles;
CREATE TABLE IF NOT EXISTS roles (
  id int(11) NOT NULL AUTO_INCREMENT,
  company_id int(11) NOT NULL,
  name varchar(50) NOT NULL,
  creation_date datetime DEFAULT NULL,
  modification_date datetime DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX IDX_B63E2EC7979B1AD6 (company_id),
  UNIQUE INDEX name_company_index (name, company_id),
  CONSTRAINT FK_B63E2EC7979B1AD6 FOREIGN KEY (company_id)
  REFERENCES companies (id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 11
AVG_ROW_LENGTH = 1638
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- Definition for table states
--
DROP TABLE IF EXISTS states;
CREATE TABLE IF NOT EXISTS states (
  id int(11) NOT NULL AUTO_INCREMENT,
  country_id int(11) NOT NULL,
  name varchar(50) NOT NULL,
  PRIMARY KEY (id),
  INDEX IDX_31C2774DF92F3E70 (country_id),
  UNIQUE INDEX UNIQ_31C2774D5E237E06 (name),
  CONSTRAINT FK_31C2774DF92F3E70 FOREIGN KEY (country_id)
  REFERENCES countries (id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 5
AVG_ROW_LENGTH = 4096
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- Definition for table voucher_types
--
DROP TABLE IF EXISTS voucher_types;
CREATE TABLE IF NOT EXISTS voucher_types (
  id int(11) NOT NULL AUTO_INCREMENT,
  account_type_id int(11) DEFAULT NULL,
  name varchar(50) NOT NULL,
  code varchar(3) NOT NULL,
  behaviour smallint(6) NOT NULL,
  PRIMARY KEY (id),
  INDEX IDX_9C8BDF0EC6798DB (account_type_id),
  CONSTRAINT FK_9C8BDF0EC6798DB FOREIGN KEY (account_type_id)
  REFERENCES account_types (id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 6
AVG_ROW_LENGTH = 3276
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- Definition for table cities
--
DROP TABLE IF EXISTS cities;
CREATE TABLE IF NOT EXISTS cities (
  id int(11) NOT NULL AUTO_INCREMENT,
  state_id int(11) NOT NULL,
  name varchar(50) NOT NULL,
  PRIMARY KEY (id),
  INDEX IDX_D95DB16B5D83CC1 (state_id),
  UNIQUE INDEX UNIQ_D95DB16B5E237E06 (name),
  CONSTRAINT FK_D95DB16B5D83CC1 FOREIGN KEY (state_id)
  REFERENCES states (id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 3
AVG_ROW_LENGTH = 8192
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- Definition for table resources_to_roles
--
DROP TABLE IF EXISTS resources_to_roles;
CREATE TABLE IF NOT EXISTS resources_to_roles (
  id int(11) NOT NULL AUTO_INCREMENT,
  resource_id int(11) NOT NULL,
  role_id int(11) NOT NULL,
  status smallint(6) NOT NULL,
  PRIMARY KEY (id),
  INDEX IDX_6E6AEA5689329D25 (resource_id),
  INDEX IDX_6E6AEA56D60322AC (role_id),
  UNIQUE INDEX resource_role_index (role_id, resource_id),
  CONSTRAINT FK_6E6AEA5689329D25 FOREIGN KEY (resource_id)
  REFERENCES resources (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_6E6AEA56D60322AC FOREIGN KEY (role_id)
  REFERENCES roles (id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- Definition for table role_parents
--
DROP TABLE IF EXISTS role_parents;
CREATE TABLE IF NOT EXISTS role_parents (
  id int(11) NOT NULL AUTO_INCREMENT,
  role_id int(11) NOT NULL,
  parent_id int(11) DEFAULT NULL,
  name varchar(50) NOT NULL,
  PRIMARY KEY (id),
  INDEX IDX_419DB891727ACA70 (parent_id),
  INDEX IDX_419DB891D60322AC (role_id),
  UNIQUE INDEX role_parent_index (id, role_id, parent_id),
  CONSTRAINT FK_419DB891727ACA70 FOREIGN KEY (parent_id)
  REFERENCES roles (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_419DB891D60322AC FOREIGN KEY (role_id)
  REFERENCES roles (id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 12
AVG_ROW_LENGTH = 1489
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- Definition for table users
--
DROP TABLE IF EXISTS users;
CREATE TABLE IF NOT EXISTS users (
  id int(11) NOT NULL AUTO_INCREMENT,
  company_id int(11) NOT NULL,
  role_id int(11) NOT NULL,
  username varchar(50) NOT NULL,
  name varchar(50) NOT NULL,
  password varchar(255) NOT NULL,
  salt varchar(60) NOT NULL,
  status smallint(6) NOT NULL DEFAULT 1,
  remember_me smallint(6) DEFAULT 0,
  creation_date datetime DEFAULT NULL,
  modification_date datetime DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX IDX_1483A5E9979B1AD6 (company_id),
  INDEX IDX_1483A5E9D60322AC (role_id),
  UNIQUE INDEX username_company_index (username, company_id),
  CONSTRAINT FK_1483A5E9979B1AD6 FOREIGN KEY (company_id)
  REFERENCES companies (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_1483A5E9D60322AC FOREIGN KEY (role_id)
  REFERENCES roles (id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 2
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- Definition for table accounts
--
DROP TABLE IF EXISTS accounts;
CREATE TABLE IF NOT EXISTS accounts (
  id int(11) NOT NULL AUTO_INCREMENT,
  account_type_id int(11) DEFAULT NULL,
  branch_id int(11) DEFAULT NULL,
  company_id int(11) NOT NULL,
  parent_id int(11) DEFAULT NULL,
  created_by int(11) DEFAULT NULL,
  modified_by int(11) DEFAULT NULL,
  name varchar(50) NOT NULL,
  code varchar(150) NOT NULL,
  class smallint(6) NOT NULL,
  category smallint(6) NOT NULL,
  level smallint(6) NOT NULL,
  serial smallint(6) NOT NULL DEFAULT 0,
  status smallint(6) NOT NULL,
  description varchar(500) DEFAULT NULL,
  creation_date datetime DEFAULT NULL,
  modification_date datetime DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX IDX_CAC89EAC25F94802 (modified_by),
  INDEX IDX_CAC89EAC727ACA70 (parent_id),
  INDEX IDX_CAC89EAC979B1AD6 (company_id),
  INDEX IDX_CAC89EACC6798DB (account_type_id),
  INDEX IDX_CAC89EACDCD6CC49 (branch_id),
  INDEX IDX_CAC89EACDE12AB56 (created_by),
  UNIQUE INDEX name_company_index (name, company_id),
  UNIQUE INDEX UNIQ_CAC89EAC5E237E06 (name),
  CONSTRAINT FK_CAC89EAC25F94802 FOREIGN KEY (modified_by)
  REFERENCES users (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_CAC89EAC727ACA70 FOREIGN KEY (parent_id)
  REFERENCES accounts (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_CAC89EAC979B1AD6 FOREIGN KEY (company_id)
  REFERENCES companies (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_CAC89EACC6798DB FOREIGN KEY (account_type_id)
  REFERENCES account_types (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_CAC89EACDCD6CC49 FOREIGN KEY (branch_id)
  REFERENCES branches (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_CAC89EACDE12AB56 FOREIGN KEY (created_by)
  REFERENCES users (id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- Definition for table bio_infos
--
DROP TABLE IF EXISTS bio_infos;
CREATE TABLE IF NOT EXISTS bio_infos (
  id int(11) NOT NULL AUTO_INCREMENT,
  city_id int(11) NOT NULL,
  company_id int(11) DEFAULT NULL,
  user_id int(11) DEFAULT NULL,
  branch_id int(11) DEFAULT NULL,
  zip_code smallint(6) DEFAULT NULL,
  address varchar(255) DEFAULT NULL,
  email varchar(50) NOT NULL,
  cell varchar(15) DEFAULT NULL,
  phone1 varchar(15) NOT NULL,
  phone2 varchar(15) DEFAULT NULL,
  fax varchar(15) DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX IDX_F3F6F5428BAC62AF (city_id),
  INDEX IDX_F3F6F542979B1AD6 (company_id),
  INDEX IDX_F3F6F542A76ED395 (user_id),
  INDEX IDX_F3F6F542DCD6CC49 (branch_id),
  CONSTRAINT FK_F3F6F5428BAC62AF FOREIGN KEY (city_id)
  REFERENCES cities (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_F3F6F542979B1AD6 FOREIGN KEY (company_id)
  REFERENCES companies (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_F3F6F542A76ED395 FOREIGN KEY (user_id)
  REFERENCES users (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_F3F6F542DCD6CC49 FOREIGN KEY (branch_id)
  REFERENCES branches (id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 6
AVG_ROW_LENGTH = 3276
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- Definition for table financial_years
--
DROP TABLE IF EXISTS financial_years;
CREATE TABLE IF NOT EXISTS financial_years (
  id int(11) NOT NULL AUTO_INCREMENT,
  company_id int(11) DEFAULT NULL,
  created_by int(11) DEFAULT NULL,
  modified_by int(11) DEFAULT NULL,
  name varchar(50) NOT NULL,
  start_date datetime NOT NULL,
  end_date datetime NOT NULL,
  status smallint(6) NOT NULL,
  is_current smallint(6) NOT NULL,
  creation_date datetime DEFAULT NULL,
  modification_date datetime DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX IDX_D269025825F94802 (modified_by),
  INDEX IDX_D2690258979B1AD6 (company_id),
  INDEX IDX_D2690258DE12AB56 (created_by),
  UNIQUE INDEX name_company_index (name, company_id),
  UNIQUE INDEX UNIQ_D26902585E237E06 (name),
  UNIQUE INDEX UNIQ_D2690258845CBB3E (end_date),
  UNIQUE INDEX UNIQ_D269025895275AB8 (start_date),
  CONSTRAINT FK_D269025825F94802 FOREIGN KEY (modified_by)
  REFERENCES users (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_D2690258979B1AD6 FOREIGN KEY (company_id)
  REFERENCES companies (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_D2690258DE12AB56 FOREIGN KEY (created_by)
  REFERENCES users (id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- Definition for table vouchers
--
DROP TABLE IF EXISTS vouchers;
CREATE TABLE IF NOT EXISTS vouchers (
  id int(11) NOT NULL AUTO_INCREMENT,
  voucher_type_id int(11) DEFAULT NULL,
  currency_id int(11) NOT NULL,
  created_by int(11) DEFAULT NULL,
  modified_by int(11) DEFAULT NULL,
  voucher_number varchar(10) NOT NULL,
  serial int(11) NOT NULL,
  exchange_rate double NOT NULL,
  voucher_date date NOT NULL,
  cheque_number varchar(15) DEFAULT NULL,
  cheque_date date DEFAULT NULL,
  creation_date datetime DEFAULT NULL,
  modification_date datetime DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX IDX_9315074825F94802 (modified_by),
  INDEX IDX_9315074838248176 (currency_id),
  INDEX IDX_93150748681A694 (voucher_type_id),
  INDEX IDX_93150748DE12AB56 (created_by),
  CONSTRAINT FK_9315074825F94802 FOREIGN KEY (modified_by)
  REFERENCES users (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_9315074838248176 FOREIGN KEY (currency_id)
  REFERENCES currencies (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_93150748681A694 FOREIGN KEY (voucher_type_id)
  REFERENCES voucher_types (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_93150748DE12AB56 FOREIGN KEY (created_by)
  REFERENCES users (id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- Definition for table opening_balances
--
DROP TABLE IF EXISTS opening_balances;
CREATE TABLE IF NOT EXISTS opening_balances (
  id int(11) NOT NULL AUTO_INCREMENT,
  account_id int(11) DEFAULT NULL,
  financial_year_id int(11) DEFAULT NULL,
  debit decimal(14, 2) NOT NULL,
  credit decimal(14, 2) NOT NULL,
  creation_date datetime DEFAULT NULL,
  modification_date datetime DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX IDX_AC2B0D811202BCCD (financial_year_id),
  INDEX IDX_AC2B0D819B6B5FBA (account_id),
  CONSTRAINT FK_AC2B0D811202BCCD FOREIGN KEY (financial_year_id)
  REFERENCES financial_years (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_AC2B0D819B6B5FBA FOREIGN KEY (account_id)
  REFERENCES accounts (id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- Definition for table users_financial_years
--
DROP TABLE IF EXISTS users_financial_years;
CREATE TABLE IF NOT EXISTS users_financial_years (
  user_id int(11) NOT NULL,
  financial_years_id int(11) NOT NULL,
  PRIMARY KEY (user_id, financial_years_id),
  INDEX IDX_691CFDC22B60194 (financial_years_id),
  INDEX IDX_691CFDC2A76ED395 (user_id),
  CONSTRAINT FK_691CFDC22B60194 FOREIGN KEY (financial_years_id)
  REFERENCES financial_years (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_691CFDC2A76ED395 FOREIGN KEY (user_id)
  REFERENCES users (id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- Definition for table voucher_details
--
DROP TABLE IF EXISTS voucher_details;
CREATE TABLE IF NOT EXISTS voucher_details (
  id int(11) NOT NULL AUTO_INCREMENT,
  voucher_id int(11) NOT NULL,
  narration varchar(1000) NOT NULL,
  debit decimal(14, 2) NOT NULL,
  credit decimal(14, 2) NOT NULL,
  status smallint(6) NOT NULL,
  Account_id int(11) DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX IDX_B1EB218D28AA1B6F (voucher_id),
  INDEX IDX_B1EB218DD4365C6A (Account_id),
  CONSTRAINT FK_B1EB218D28AA1B6F FOREIGN KEY (voucher_id)
  REFERENCES vouchers (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_B1EB218DD4365C6A FOREIGN KEY (Account_id)
  REFERENCES accounts (id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_unicode_ci;