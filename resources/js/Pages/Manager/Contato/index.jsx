import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faEnvelope } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminLayout';
import { Breadcrumb } from '@/Components/Manager/Breadcrumb';
import { ConfirmModal } from '@/Components/Manager/ConfirmModal';
import { PageSettings } from '@/Components/Manager/PageSettings';
import { FormContent } from '@/Components/Manager/FormContent';
import { BlockContent } from '@/Components/Manager/BlockContent';

const Page = () => {
    // Content
    const { pagina, conteudos, idioma, idiomas, projetos } = usePage().props;

    const breadcrumbItems = [
        // { label: 'Home', link: 'Home.index' },
        // { label: 'Projects', link: 'Home.index' },
    ];

    const contentProjects = {
        nome: ['Projetos', 'projeto'],
        controller: 'ProjetosContatos',
        imagens: true,
        imgClass: '',
        editavel: true,
        conteudos: projetos,
    };

    return (
        <AdminLayout>
            <Breadcrumb icon={faEnvelope} items={breadcrumbItems} current="Contato" idioma={idioma.codigo} idiomas={idiomas} />
            <PageSettings page={pagina} idioma={idioma.codigo} />
            
            <FormContent content={conteudos[0]} full={true} idioma={idioma.codigo} />

            <div className="grid grid-cols-1 gap-x-6 md:grid-cols-3">
                <FormContent content={conteudos[1]} full={false} idioma={idioma.codigo} />
                
                <FormContent content={conteudos[2]} full={false} idioma={idioma.codigo} />
                
                <FormContent content={conteudos[3]} full={false} idioma={idioma.codigo} />
            </div>

            <BlockContent content={contentProjects} />
        </AdminLayout>
    );
};

export default Page;
