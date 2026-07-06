import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { faCity } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminLayout';
import { Breadcrumb } from '@/Components/Manager/Breadcrumb';
import { BlockContent } from '@/Components/Manager/BlockContent';

const Page = () => {
    // Content
    const { idioma, idiomas, mostraAno } = usePage().props;

    const breadcrumbItems = [
        { label: 'Inspiração', link: 'Manager.Mostras.index' },
        { label: 'Mostras de decoração', link: 'Manager.Mostras.index' },
        { label: 'Editar Mostra', link: 'Manager.Mostras.editar', params: { id: mostraAno.id }},
        { label: mostraAno.nome, link: 'Manager.Mostras.editar', params: { id: mostraAno.id }},
        { label: mostraAno.ano, link: 'Manager.Mostras.editar', params: { id: mostraAno.id }},
    ];

    const contentCities = {
        nome: ['Cidades', 'cidade'],
        controller: 'Mostras.Cidades',
        imagens: false,
        addId: mostraAno.id,
        editavel: true,
        conteudos: mostraAno.mostrasCidades
    };

    return (
        <AdminLayout>
            <Breadcrumb icon={faCity} items={breadcrumbItems} current="Cidades" idioma={idioma.codigo} idiomas={idiomas} id={mostraAno.id} />

            <BlockContent content={contentCities} />
        </AdminLayout>
    );
};

export default Page;
