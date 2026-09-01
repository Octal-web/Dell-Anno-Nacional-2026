-- Execute este script somente depois de criar um backup completo do banco.
-- Estrutura final: ambientes (antigos produtos) > colecoes (antigos ambientes) > imagens_projetos_lojas.

SET FOREIGN_KEY_CHECKS = 0;

-- As tabelas colecoes criadas anteriormente ainda estao vazias no dump auditado.
-- Elas sao substituidas pelas antigas tabelas ambientes, preservando os dados existentes.
DROP TABLE IF EXISTS `imagens_colecoes`;
DROP TABLE IF EXISTS `colecoes_idiomas`;
DROP TABLE IF EXISTS `colecoes`;

RENAME TABLE
    `ambientes_idiomas` TO `colecoes_idiomas`,
    `ambientes` TO `colecoes`,
    `produtos_idiomas` TO `ambientes_idiomas`,
    `produtos` TO `ambientes`;

-- Corrige os nomes das FKs nas tabelas de idiomas.
ALTER TABLE `colecoes_idiomas`
    DROP FOREIGN KEY `ambientes_idiomas_ibfk_1`,
    DROP FOREIGN KEY `ambientes_idiomas_ibfk_2`,
    CHANGE `ambiente_id` `colecao_id` int(11) unsigned NOT NULL;

ALTER TABLE `ambientes_idiomas`
    DROP FOREIGN KEY `produtos_idiomas_ibfk_1`,
    DROP FOREIGN KEY `produtos_idiomas_ibfk_2`,
    CHANGE `produto_id` `ambiente_id` int(11) unsigned NOT NULL;

-- O relacionamento produto_id dos ambientes antigos ja havia sido removido.
-- No dump auditado, as quatro colecoes existentes sao cozinhas e pertencem ao ambiente id 1.
ALTER TABLE `colecoes`
    ADD `ambiente_id` int(11) unsigned DEFAULT NULL AFTER `ordem`;

UPDATE `colecoes`
SET `ambiente_id` = 1
WHERE `id` IN (1, 2, 3, 4);

-- Esta alteracao falha de proposito caso exista alguma colecao sem mapeamento.
-- Nesse caso, preencha ambiente_id nos registros restantes antes de continuar.
ALTER TABLE `colecoes`
    MODIFY `ambiente_id` int(11) unsigned NOT NULL,
    ADD KEY `ambiente_id` (`ambiente_id`),
    ADD CONSTRAINT `colecoes_ibfk_1`
        FOREIGN KEY (`ambiente_id`) REFERENCES `ambientes` (`id`);

ALTER TABLE `colecoes_idiomas`
    DROP INDEX `case_cliente_id`,
    ADD UNIQUE KEY `colecao_idioma` (`colecao_id`, `idioma_id`),
    ADD KEY `colecao_id` (`colecao_id`),
    ADD CONSTRAINT `colecoes_idiomas_ibfk_1`
        FOREIGN KEY (`idioma_id`) REFERENCES `idiomas` (`id`),
    ADD CONSTRAINT `colecoes_idiomas_ibfk_2`
        FOREIGN KEY (`colecao_id`) REFERENCES `colecoes` (`id`);

ALTER TABLE `ambientes_idiomas`
    DROP INDEX `case_cliente_id`,
    ADD UNIQUE KEY `ambiente_idioma` (`ambiente_id`, `idioma_id`),
    ADD KEY `ambiente_id` (`ambiente_id`),
    ADD CONSTRAINT `ambientes_idiomas_ibfk_1`
        FOREIGN KEY (`idioma_id`) REFERENCES `idiomas` (`id`),
    ADD CONSTRAINT `ambientes_idiomas_ibfk_2`
        FOREIGN KEY (`ambiente_id`) REFERENCES `ambientes` (`id`);

CREATE TABLE `imagens_colecoes` (
    `imagem_id` int(11) NOT NULL,
    `colecao_id` int(11) unsigned NOT NULL,
    PRIMARY KEY (`imagem_id`, `colecao_id`),
    KEY `colecao_id` (`colecao_id`),
    CONSTRAINT `imagens_colecoes_ibfk_1`
        FOREIGN KEY (`imagem_id`) REFERENCES `imagens_projetos_lojas` (`id`),
    CONSTRAINT `imagens_colecoes_ibfk_2`
        FOREIGN KEY (`colecao_id`) REFERENCES `colecoes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Corrige uma FK incorreta encontrada no dump auditado.
ALTER TABLE `paginas_idiomas`
    DROP FOREIGN KEY `paginas_idiomas_ibfk_2`,
    ADD CONSTRAINT `paginas_idiomas_ibfk_2`
        FOREIGN KEY (`idioma_id`) REFERENCES `idiomas` (`id`);

-- Mantem as tabelas extintas como backup temporario.
-- Elas nao sao mais consultadas pelo codigo novo.
RENAME TABLE
    `projetos_idiomas` TO `projetos_idiomas_legado`,
    `projetos` TO `projetos_legado`,
    `imagens_produtos_idiomas` TO `imagens_produtos_idiomas_legado`,
    `imagens_produtos` TO `imagens_produtos_legado`;

SET FOREIGN_KEY_CHECKS = 1;

-- Depois de homologar a migracao e confirmar o backup, os legados podem ser removidos:
-- DROP TABLE `projetos_idiomas_legado`;
-- DROP TABLE `projetos_legado`;
-- DROP TABLE `imagens_produtos_idiomas_legado`;
-- DROP TABLE `imagens_produtos_legado`;
