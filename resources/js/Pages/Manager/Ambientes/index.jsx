import { usePage } from '@inertiajs/react';
import { faHouse } from '@fortawesome/free-solid-svg-icons';
import AdminLayout from '@/Layouts/AdminLayout';
import { Breadcrumb } from '@/Components/Manager/Breadcrumb';
import { BlockContent } from '@/Components/Manager/BlockContent';

const Page = () => {
    const { idioma, idiomas, ambientes } = usePage().props;
    const contentEnvironments = {
        nome: ['Ambientes', 'ambiente'],
        controller: 'Ambientes',
        imagens: true,
        editavel: true,
        conteudos: ambientes,
    };

    return (
        <AdminLayout>
            <Breadcrumb icon={faHouse} items={[]} current="Ambientes" idioma={idioma.codigo} idiomas={idiomas} />
            <BlockContent content={contentEnvironments} />
        </AdminLayout>
    );
};

export default Page;
