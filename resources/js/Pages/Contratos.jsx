import { usePage } from "@inertiajs/react";

import { ContractBanner } from "@/Components/ContractBanner";
import { ContractItems } from "@/Components/ContractItems";
import DefaultLayout from "@/Layouts/DefaultLayout";
import { ProductsForm } from "@/Components/ProductsForm";

const Page = () => {
    const { conteudos, contratos, chamadaForm } = usePage().props;

    return (
        <DefaultLayout>
            <ContractBanner content={conteudos[0]} />
            <ContractItems items={contratos}/>
            <ProductsForm content={chamadaForm} posicaoForm="Página Contract"/>
        </DefaultLayout>
    );
};

export default Page;
