import { usePage } from "@inertiajs/react";

import { faHandshake } from "@fortawesome/free-solid-svg-icons";

import { BlockContent } from "@/Components/Manager/BlockContent";
import { Breadcrumb } from "@/Components/Manager/Breadcrumb";
import { FormContent } from "@/Components/Manager/FormContent";
import { PageSettings } from "@/Components/Manager/PageSettings";
import AdminLayout from "@/Layouts/AdminLayout";

const Page = () => {
    // Content
    const { pagina, conteudos, contratos, idioma, idiomas } = usePage().props;

    const breadcrumbItems = [
        // { label: 'Home', link: 'Home.index' },
        // { label: 'Projects', link: 'Home.index' },
    ];

    const contentContracts = {
        nome: ["Contratos", "contrato"],
        controller: "Contratos",
        imagens: true,
        imgClass: "",
        editavel: true,
        conteudos: contratos,
    };

    return (
        <AdminLayout>
            <Breadcrumb
                icon={faHandshake}
                items={breadcrumbItems}
                current="Contracts"
                idioma={idioma.codigo}
                idiomas={idiomas}
            />
            <PageSettings page={pagina} idioma={idioma.codigo} />

            <FormContent
                content={conteudos[0]}
                full={true}
                idioma={idioma.codigo}
            />

            <BlockContent content={contentContracts} />
        </AdminLayout>
    );
};

export default Page;
