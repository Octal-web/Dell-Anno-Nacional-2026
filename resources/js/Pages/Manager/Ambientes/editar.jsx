import React from 'react';
import { Link, useForm, usePage } from '@inertiajs/react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faArrowLeft, faHouse, faLayerGroup, faSave } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminLayout';
import { Breadcrumb } from '@/Components/Manager/Breadcrumb';
import { FormGroup } from '@/Components/Manager/Inputs/FormGroup';

const Page = () => {
    const { idioma, idiomas, ambiente } = usePage().props;
    const { data, setData, post, processing, errors } = useForm(ambiente);
    const inputItems = [
        [{ titulo: 'Nome', name: 'nome', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto', max: 100 }],
        [{ titulo: 'Descrição', name: 'descricao', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto_longo', editor: false, max: 500 }],
        [
            { titulo: 'Imagem', name: 'img', tamanho: 'col-span-12 md:col-span-6 lg:col-span-4', tipo: 'imagem', crop: true, largura: 840, altura: 380, imagem: ambiente.imagem },
            { titulo: 'Banner', name: 'img_banner', tamanho: 'col-span-12 md:col-span-6', tipo: 'imagem', crop: true, largura: 1920, altura: 680, imagem: ambiente.banner },
        ],
        [{ titulo: 'Título da Página', name: 'titulo_pagina', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto', max: 100 }],
        [{ titulo: 'Descrição da Página', name: 'descricao_pagina', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto_longo', editor: false, max: 300 }],
    ];

    const handleSubmit = (event) => {
        event.preventDefault();

        const idiomaUrl = new URLSearchParams(window.location.search).get('lang');
        post(route('Manager.Ambientes.atualizar', { id: ambiente.id, lang: idiomaUrl }), {
            preserveScroll: true,
        });
    };

    const handleChange = (name, value) => {
        setData((previousData) => ({ ...previousData, [name]: value }));
    };

    const handleImageCrop = (croppedImage, fileExtension, name) => {
        setData((previousData) => ({ ...previousData, [name]: croppedImage }));
    };

    return (
        <AdminLayout>
            <Breadcrumb
                icon={faHouse}
                items={[{ label: 'Ambientes', link: 'Manager.Ambientes.index' }]}
                current="Editar"
                idioma={idioma.codigo}
                idiomas={idiomas}
                id={ambiente.id}
            />

            <div className="mb-6 border border-stroke bg-white px-5 py-5 shadow-md">
                <Link
                    href={route('Manager.Ambientes.Colecoes.index', { id: ambiente.id })}
                    className="float-right flex items-center border border-stroke bg-white px-3 py-2 transition-all hover:bg-slate-100"
                >
                    <FontAwesomeIcon icon={faLayerGroup} className="mr-2" />
                    Coleções
                </Link>

                <div className="mt-12">
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
                                            <p className="-mt-5 mb-3 text-sm text-red-500">{errors[input.name]}</p>
                                        )}
                                    </div>
                                ))}
                            </div>
                        ))}

                        <div className="flex items-center justify-end">
                            <Link href={route('Manager.Ambientes.index')} className="mr-3 flex items-center border border-red-700 px-3 py-2 text-red-700">
                                <FontAwesomeIcon icon={faArrowLeft} className="mr-2" />
                                Voltar
                            </Link>
                            <button type="submit" disabled={processing} className="border border-gray-300 px-3 py-2">
                                <FontAwesomeIcon icon={faSave} className="mr-2" />
                                Salvar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </AdminLayout>
    );
};

export default Page;
