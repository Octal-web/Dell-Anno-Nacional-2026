import { usePage } from '@inertiajs/react';
import { faLayerGroup } from '@fortawesome/free-solid-svg-icons';
import AdminLayout from '@/Layouts/AdminLayout';
import { Breadcrumb } from '@/Components/Manager/Breadcrumb';
import { BlockContent } from '@/Components/Manager/BlockContent';

const Page = () => {
    const { idioma, idiomas, ambiente } = usePage().props;
    const contentCollections = {
        nome: ['Coleções', 'coleção'],
        controller: 'Ambientes.Colecoes',
        imagens: false,
        addId: ambiente.id,
        editavel: true,
        conteudos: ambiente.colecoes,
    };

    return (
        <AdminLayout>
            <Breadcrumb icon={faLayerGroup} items={[{ label: 'Ambientes', link: 'Manager.Ambientes.index' }, { label: ambiente.nome, link: 'Manager.Ambientes.editar', params: { id: ambiente.id } }]} current="Coleções" idioma={idioma.codigo} idiomas={idiomas} id={ambiente.id} />
            <BlockContent content={contentCollections} />
        </AdminLayout>
    );
};
export default Page;
