-- Migra os produtos para ambientes e os ambientes antigos para colecoes.
-- Validado para o estado do banco oitoporoito55 em 01/09/2026.

SET FOREIGN_KEY_CHECKS = 0;

-- Copias locais para reversao antes de qualquer alteracao.
CREATE TABLE `produtos_backup_20260901` LIKE `produtos`;
INSERT INTO `produtos_backup_20260901` SELECT * FROM `produtos`;

CREATE TABLE `produtos_idiomas_backup_20260901` LIKE `produtos_idiomas`;
INSERT INTO `produtos_idiomas_backup_20260901` SELECT * FROM `produtos_idiomas`;

CREATE TABLE `ambientes_backup_20260901` LIKE `ambientes`;
INSERT INTO `ambientes_backup_20260901` SELECT * FROM `ambientes`;

CREATE TABLE `ambientes_idiomas_backup_20260901` LIKE `ambientes_idiomas`;
INSERT INTO `ambientes_idiomas_backup_20260901` SELECT * FROM `ambientes_idiomas`;

CREATE TABLE `colecoes_backup_20260901` LIKE `colecoes`;
INSERT INTO `colecoes_backup_20260901` SELECT * FROM `colecoes`;

CREATE TABLE `colecoes_idiomas_backup_20260901` LIKE `colecoes_idiomas`;
INSERT INTO `colecoes_idiomas_backup_20260901` SELECT * FROM `colecoes_idiomas`;

-- Os ambientes antigos passam a ser colecoes.
ALTER TABLE `colecoes_idiomas`
    ADD `descricao_curta` text NULL AFTER `nome`,
    ADD `descricao` text NULL AFTER `descricao_curta`;

INSERT INTO `colecoes` (
    `id`,
    `visivel`,
    `ordem`,
    `slug`,
    `ambiente_id`,
    `criado`,
    `modificado`,
    `excluido`
)
SELECT
    `id`,
    `visivel`,
    `ordem`,
    CONCAT('colecao-', `id`),
    1,
    `criado`,
    `modificado`,
    `excluido`
FROM `ambientes`;

INSERT INTO `colecoes_idiomas` (
    `id`,
    `nome`,
    `descricao_curta`,
    `descricao`,
    `idioma_id`,
    `colecao_id`,
    `criado`,
    `modificado`,
    `excluido`
)
SELECT
    `id`,
    `nome`,
    `descricao_curta`,
    `descricao`,
    `idioma_id`,
    `ambiente_id`,
    `criado`,
    `modificado`,
    `excluido`
FROM `ambientes_idiomas`;

ALTER TABLE `colecoes`
    DROP COLUMN `slug`;

ALTER TABLE `colecoes_idiomas`
    MODIFY `descricao_curta` text NOT NULL,
    MODIFY `descricao` text NOT NULL;

-- Ambientes passa a ter exatamente os campos de produtos.
ALTER TABLE `ambientes`
    ADD `imagem` varchar(36) NULL AFTER `id`,
    ADD `banner` varchar(36) NULL AFTER `imagem`,
    ADD `slug` varchar(96) NULL AFTER `banner`;

INSERT INTO `ambientes` (
    `id`,
    `imagem`,
    `banner`,
    `slug`,
    `visivel`,
    `ordem`,
    `criado`,
    `modificado`,
    `excluido`
)
SELECT
    `id`,
    `imagem`,
    `banner`,
    `slug`,
    `visivel`,
    `ordem`,
    `criado`,
    `modificado`,
    `excluido`
FROM `produtos`
ON DUPLICATE KEY UPDATE
    `imagem` = VALUES(`imagem`),
    `banner` = VALUES(`banner`),
    `slug` = VALUES(`slug`),
    `visivel` = VALUES(`visivel`),
    `ordem` = VALUES(`ordem`),
    `criado` = VALUES(`criado`),
    `modificado` = VALUES(`modificado`),
    `excluido` = VALUES(`excluido`);

DELETE FROM `ambientes`
WHERE `id` NOT IN (SELECT `id` FROM `produtos`);

ALTER TABLE `ambientes`
    MODIFY `imagem` varchar(36) NOT NULL,
    MODIFY `banner` varchar(36) NOT NULL,
    MODIFY `slug` varchar(96) NOT NULL;

-- A estrutura e o conteudo dos idiomas de produtos passam para ambientes_idiomas.
DELETE FROM `ambientes_idiomas`;

ALTER TABLE `ambientes_idiomas`
    DROP COLUMN `descricao_curta`,
    ADD `titulo_pagina` varchar(72) NULL AFTER `descricao`,
    ADD `descricao_pagina` text NULL AFTER `titulo_pagina`;

INSERT INTO `ambientes_idiomas` (
    `id`,
    `nome`,
    `descricao`,
    `titulo_pagina`,
    `descricao_pagina`,
    `idioma_id`,
    `ambiente_id`,
    `criado`,
    `modificado`,
    `excluido`
)
SELECT
    `id`,
    `nome`,
    `descricao`,
    `titulo_pagina`,
    `descricao_pagina`,
    `idioma_id`,
    `produto_id`,
    `criado`,
    `modificado`,
    `excluido`
FROM `produtos_idiomas`;

ALTER TABLE `ambientes_idiomas`
    MODIFY `titulo_pagina` varchar(72) NOT NULL,
    MODIFY `descricao_pagina` text NOT NULL;

SET FOREIGN_KEY_CHECKS = 1;
