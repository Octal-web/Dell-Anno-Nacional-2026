import { usePage } from "@inertiajs/react";

import DefaultLayout from "@/Layouts/DefaultLayout";
import { ContractBanner } from "@/Components/ContractBanner";
import { ContractText } from "@/Components/ContractText";
import { ContractItems } from "@/Components/ContractItems";

const Page = () => {
    const { conteudos, contratos } = usePage().props;

    return (
        <DefaultLayout>
            <ContractBanner content={conteudos[0]} />
            <ContractText content={conteudos[1]}/>

            <ContractItems items={contratos}/>
        </DefaultLayout>
    );
};

export default Page;
