import React from 'react';
import { Link, useForm, usePage } from '@inertiajs/react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faArrowLeft, faImages, faSave } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminLayout';
import { Breadcrumb } from '@/Components/Manager/Breadcrumb';
import { FormGroup } from '@/Components/Manager/Inputs/FormGroup';

const Page = () => {
    const { idioma, idiomas, imagemItem, acabamentos, colecoes } = usePage().props;
    const { data, setData, post, processing, errors } = useForm(imagemItem);

    const breadcrumbItems = [
        { label: 'Ambientes', link: 'Manager.Ambientes.index' },
        { label: 'Coleções', link: 'Manager.Ambientes.Colecoes.index', params: { id: imagemItem.ambiente_id } },
        { label: imagemItem.colecao_nome, link: 'Manager.Ambientes.Colecoes.editar', params: { id: imagemItem.colecao_id } },
        { label: 'Imagens', link: 'Manager.Ambientes.Colecoes.Imagens.index', params: { id: imagemItem.colecao_id } },
    ];

    const inputItems = [
        [
            {
                titulo: 'Imagem',
                name: 'img',
                tamanho: 'col-span-12 md:col-span-6',
                tipo: 'imagem',
                crop: true,
                largura: 450,
                altura: 357,
                imagem: imagemItem.imagem,
            },
        ],
        [
            {
                titulo: 'Detalhes',
                name: 'detalhes',
                tamanho: 'col-span-12 lg:col-span-8',
                tipo: 'texto_longo',
                editor: false,
                max: 720,
            },
        ],
        [
            {
                titulo: 'Acabamentos',
                name: 'acabamentos',
                tamanho: 'col-span-12 md:col-span-6 lg:col-span-4',
                tipo: 'select',
                isMulti: true,
                options: acabamentos,
            },
            {
                titulo: 'Coleções',
                name: 'colecoes',
                tamanho: 'col-span-12 md:col-span-6 lg:col-span-4',
                tipo: 'select',
                isMulti: true,
                options: colecoes,
            },
        ],
    ];

    const handleSubmit = (event) => {
        event.preventDefault();

        const idiomaUrl = new URLSearchParams(window.location.search).get('lang');

        post(route('Manager.Ambientes.Colecoes.Imagens.atualizar', {
            id: imagemItem.id,
            lang: idiomaUrl,
        }), {
            preserveScroll: true,
        });
    };

    const handleChange = (name, value) => {
        setData((previousData) => ({
            ...previousData,
            [name]: value,
        }));
    };

    const handleImageCrop = (croppedImage, fileExtension, name) => {
        setData((previousData) => ({
            ...previousData,
            [name]: croppedImage,
        }));
    };

    return (
        <AdminLayout>
            <Breadcrumb
                icon={faImages}
                items={breadcrumbItems}
                current="Editar"
                idioma={idioma.codigo}
                idiomas={idiomas}
                id={imagemItem.id}
            />

            <div className="mb-6 border border-stroke bg-white px-5 py-5 shadow-md">
                <form onSubmit={handleSubmit}>
                    {inputItems.map((group, groupIndex) => (
                        <div key={groupIndex} className="grid grid-cols-12 gap-x-6">
                            {group.map((input, index) => (
                                <div key={index} className={`w-full ${input.tamanho}`}>
                                    <FormGroup
                                        input={input}
                                        idioma={idioma}
                                        value={data[input.name]}
                                        onChange={handleChange}
                                        handleImageCrop={handleImageCrop}
                                    />

                                    {errors[input.name] && (
                                        <p className="-mt-5 mb-3 text-sm text-red-500">
                                            {errors[input.name]}
                                        </p>
                                    )}
                                </div>
                            ))}
                        </div>
                    ))}

                    <div className="flex items-center justify-end">
                        <Link
                            href={route('Manager.Ambientes.Colecoes.Imagens.index', { id: imagemItem.colecao_id })}
                            className="mr-3 flex items-center border border-red-700 px-3 py-2 text-red-700"
                        >
                            <FontAwesomeIcon icon={faArrowLeft} className="mr-2" />
                            Voltar
                        </Link>

                        <button
                            type="submit"
                            disabled={processing}
                            className="flex items-center border border-gray-300 px-3 py-2"
                        >
                            <FontAwesomeIcon icon={faSave} className="mr-2" />
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </AdminLayout>
    );
};

export default Page;
