import React from 'react';
import { usePage } from '@inertiajs/react';

import DefaultLayout from '@/Layouts/DefaultLayout';

import { PolicyText } from '@/Components/PolicyText';

const Page = () => {
    const { conteudo } = usePage().props;
    
    return (
        <DefaultLayout>
            <PolicyText content={{titulo: 'Política de Cookies', texto: conteudo}} />
        </DefaultLayout>
    );
};

export default Page;
