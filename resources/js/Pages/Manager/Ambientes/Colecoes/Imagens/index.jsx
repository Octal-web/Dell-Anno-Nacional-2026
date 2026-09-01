import React, { useEffect, useRef, useState } from 'react';
import { useForm, usePage } from '@inertiajs/react';
import { ReactSortable } from 'react-sortablejs';
import { faImages } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminLayout';
import { Breadcrumb } from '@/Components/Manager/Breadcrumb';
import { ImageUploader } from '@/Components/Manager/ImageUploader';
import { IndividualImage } from '@/Components/Manager/IndividualImage';

const Page = () => {
    const { colecao } = usePage().props;
    const [state, setState] = useState(colecao.imagens);
    const previousStateRef = useRef(state);
    const [isUpdated, setIsUpdated] = useState(false);
    const [isReadyToUpload, setIsReadyToUpload] = useState(false);

    const { data, setData, post } = useForm({
        images: [],
    });

    const handleImageUpload = (processedImages) => {
        const imagesData = processedImages.map(({ original, resized }) => ({
            img: original,
            img_alt: resized,
        }));

        setData('images', imagesData);
        setIsReadyToUpload(true);
    };

    useEffect(() => {
        if (isReadyToUpload && data.images.length > 0) {
            post(route('Manager.Ambientes.Colecoes.Imagens.novo', { id: colecao.id }), {
                preserveScroll: true,
                preserveState: false,
            });
            setIsReadyToUpload(false);
        }
    }, [isReadyToUpload, data.images]);

    useEffect(() => {
        const previousState = previousStateRef.current;

        if (JSON.stringify(state) !== JSON.stringify(previousState)) {
            const orderedData = state.map((item, index) => ({
                id: item.id,
                ordem: index,
            }));

            setData((previousData) => ({ ...previousData, odr: orderedData }));
            setIsUpdated(true);
        }

        previousStateRef.current = state;
    }, [state]);

    useEffect(() => {
        if (isUpdated) {
            post(route('Manager.Ambientes.Colecoes.Imagens.ordenar', { id: colecao.id }), {
                preserveScroll: true,
            });
            setIsUpdated(false);
        }
    }, [isUpdated]);

    const breadcrumbItems = [
        { label: 'Ambientes', link: 'Manager.Ambientes.index' },
        { label: colecao.ambiente_nome, link: 'Manager.Ambientes.editar', params: { id: colecao.ambiente_id } },
        { label: 'Coleções', link: 'Manager.Ambientes.Colecoes.index', params: { id: colecao.ambiente_id } },
        { label: colecao.nome, link: 'Manager.Ambientes.Colecoes.editar', params: { id: colecao.id } },
    ];

    return (
        <AdminLayout>
            <Breadcrumb icon={faImages} items={breadcrumbItems} current="Imagens" />

            <div className="rounded-sm border border-stroke bg-white px-5 py-5 shadow-md">
                <div className="grid grid-cols-1 gap-8 lg:grid-cols-5">
                    <div className="lg:col-span-4">
                        <ReactSortable
                            animation={150}
                            list={state}
                            forceFallback={true}
                            setList={setState}
                            filter=".sort-ignore"
                            className="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
                        >
                            {state.map((image) => (
                                <div key={image.id} className="relative h-full pb-12">
                                    <IndividualImage
                                        individualContent={image}
                                        controller="Ambientes.Colecoes.Imagens"
                                        edit={true}
                                        routeParams={{ colecao: colecao.id }}
                                        crop={true}
                                        size={{ largura: 450, altura: 357 }}
                                    />
                                </div>
                            ))}
                        </ReactSortable>
                    </div>

                    <div className="min-h-[calc(100vh-13rem)] lg:col-span-1">
                        <ImageUploader
                            onUpload={handleImageUpload}
                            crop={false}
                            size={{ largura: 450, altura: 357 }}
                        />
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
};

export default Page;
