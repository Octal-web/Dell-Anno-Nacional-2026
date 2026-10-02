import React from 'react';
import { usePage } from '@inertiajs/react';

import DefaultLayout from '@/Layouts/DefaultLayout';

import { ContactBanner } from '@/Components/ContactBanner';
import { ContactBenefits } from '@/Components/ContactBenefits';
import { ProductsForm } from '@/Components/ProductsForm';
import { ContactProjects } from '@/Components/ContactProjects';

const Page = () => {
    const { conteudos, projetos } = usePage().props;

    return (
        <DefaultLayout>
            <ContactBanner content={conteudos[0]} />

            <ContactBenefits items={conteudos.slice(1, 4)} />

            <ProductsForm
                content={{
                    titulo: 'Solicite orçamento',
                }}
                posicaoForm="Página Solicite Seu Projeto"
            />

            <ContactProjects projects={projetos} />
        </DefaultLayout>
    );
};

export default Page;
