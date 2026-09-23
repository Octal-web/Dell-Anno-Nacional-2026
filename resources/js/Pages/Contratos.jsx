import { usePage } from "@inertiajs/react";

import { ContractBanner } from "@/Components/ContractBanner";
import { ContractItems } from "@/Components/ContractItems";
import DefaultLayout from "@/Layouts/DefaultLayout";

const Page = () => {
    const { conteudos, contratos } = usePage().props;

    return (
        <DefaultLayout>
            <ContractBanner content={conteudos[0]} />
            <ContractItems items={contratos}/>
        </DefaultLayout>
    );
};

export default Page;
