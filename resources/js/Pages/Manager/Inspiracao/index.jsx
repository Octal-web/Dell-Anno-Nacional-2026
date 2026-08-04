import { usePage } from "@inertiajs/react";

import { faStar } from "@fortawesome/free-solid-svg-icons";

import { Breadcrumb } from "@/Components/Manager/Breadcrumb";
import { FormContent } from "@/Components/Manager/FormContent";
import { PageSettings } from "@/Components/Manager/PageSettings";
import AdminLayout from "@/Layouts/AdminLayout";

const Page = () => {
    // Content
    const { pagina, conteudos, idioma, idiomas } = usePage().props;

    const breadcrumbItems = [
        // { label: 'Home', link: 'Home.index' },
        // { label: 'Projects', link: 'Home.index' },
    ];

    return (
        <AdminLayout>
            <Breadcrumb
                icon={faStar}
                items={breadcrumbItems}
                current="Inspiração"
                idioma={idioma.codigo}
                idiomas={idiomas}
            />
            <PageSettings page={pagina} idioma={idioma.codigo} />

            <FormContent
                content={conteudos[0]}
                full={true}
                idioma={idioma.codigo}
            />

            <div className="grid grid-cols-1 gap-x-6 md:grid-cols-3">
                <FormContent
                    content={conteudos[1]}
                    full={false}
                    idioma={idioma.codigo}
                />
                <FormContent
                    content={conteudos[2]}
                    full={false}
                    idioma={idioma.codigo}
                />
                <FormContent
                    content={conteudos[3]}
                    full={false}
                    idioma={idioma.codigo}
                />
            </div>
        </AdminLayout>
    );
};

export default Page;
