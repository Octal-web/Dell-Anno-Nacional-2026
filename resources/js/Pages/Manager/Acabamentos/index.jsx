import { Link, usePage } from '@inertiajs/react';

import { faBorderAll } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminLayout';
import { Breadcrumb } from '@/Components/Manager/Breadcrumb';
import { PageSettings } from '@/Components/Manager/PageSettings';
import { BlockContent } from '@/Components/Manager/BlockContent';

const Page = () => {
    // Content
    const { pagina, idioma, idiomas, acabamentos, categorias } = usePage().props;

    const breadcrumbItems = [];
    
    const contentFinishes = {
        nome: ['Acabamentos', 'acabamento'],
        controller: 'Acabamentos',
        imagens: true,
        imgClass: '',
        editavel: true,
        conteudos: acabamentos
    };
    const contentCategories = {
        nome: ['Categorias', 'categoria'],
        controller: 'Acabamentos.Categorias',
        imagens: false,
        editavel: true,
        conteudos: categorias
    };
    
    return (
        <AdminLayout>
            <Breadcrumb icon={faBorderAll} items={breadcrumbItems} current="Acabamentos" idioma={idioma.codigo} idiomas={idiomas} />
            <PageSettings page={pagina} idioma={idioma.codigo} />

            <BlockContent content={contentFinishes} />
            <BlockContent content={contentCategories} />
        </AdminLayout>
    );
};

export default Page;
