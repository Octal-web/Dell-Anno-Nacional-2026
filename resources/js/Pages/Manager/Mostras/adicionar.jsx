import React, { useEffect } from 'react';
import { Link, usePage, useForm } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faPalette, faSave, faArrowLeft } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminLayout';
import { Breadcrumb } from '@/Components/Manager/Breadcrumb';
import { FormGroup } from '@/Components/Manager/Inputs/FormGroup';

const Page = () => {
    const { idioma, tipo, mostra, ocultarIdiomas } = usePage().props;

    const { data, setData, post, processing, errors } = useForm({
        tipo: tipo || '',
    });

    const tipoLabel = {
        'casacor': 'Casacor',
        'casas-conceito': 'Casas Conceito',
    };

    const tipoAtual = tipo && tipoLabel[tipo] ? tipoLabel[tipo] : null;

    const breadcrumbItems = [
        { label: 'Inspiração', link: 'Manager.Mostras.index' },
        { label: 'Mostras de decoração', link: 'Manager.Mostras.index' },
        ...(tipoAtual ? [{ label: tipoAtual, link: 'Manager.Mostras.index' }] : []),
    ];

    const inputItems = [
        ...(ocultarIdiomas ? [
            [{ titulo: 'Ano', name: 'ano', tamanho: 'col-span-12 md:col-span-6 lg:col-span-4', tipo: 'numero', min: 1900, max: 2113 }],
        ] : [
            [
                { titulo: 'Nome', name: 'nome', tamanho: 'col-span-12 lg:col-span-6', tipo: 'texto', max: 120 },
                { titulo: 'Ano', name: 'ano', tamanho: 'col-span-2', tipo: 'numero', min: 1900, max: 2113 },
            ],
            [{ titulo: 'Descrição', name: 'descricao', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto_longo', editor: false }],
            [{ titulo: 'Imagem', name: 'img', tamanho: 'col-span-12 md:col-span-6 lg:col-span-4', tipo: 'imagem', crop: true, largura: 960, altura: 650 }],
            [{ titulo: 'Título da Página', name: 'titulo_pagina', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto', max: 72 }],
            [{ titulo: 'Descrição da Página', name: 'descricao_pagina', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto_longo', editor: false }],
        ]),
    ];

    const initializeData = (inputItems) => {
        let initialData = {};

        inputItems.forEach(group => {
            group.forEach(item => {
                initialData[item.name] = item.tipo === 'check' ? false : '';
            });
        });

        return initialData;
    };

    useEffect(() => {
        const initialData = initializeData(inputItems);

        setData({
            ...initialData,
            tipo: tipo || '',
        });
    }, []);

    const handleSubmit = (e) => {
        e.preventDefault();

        post(route('Manager.Mostras.novo'), {
            preserveScroll: true
        });
    };

    const onChange = (name, value) => {
        setData(prevData => ({
            ...prevData,
            [name]: value
        }));
    };

    const handleImageCrop = (croppedImage, fileExtenstion, name) => {
        setData(prevData => ({
            ...prevData,
            [name]: croppedImage
        }));
    };

    return (
        <AdminLayout>
            <Breadcrumb icon={faPalette} items={breadcrumbItems} current="Adicionar" idioma={idioma.codigo} />

            <div className="mb-6 border border-stroke bg-white px-5 py-5 shadow-md">
                <div className="mt-10">
                    <form onSubmit={handleSubmit}>
                        {inputItems.map((group, groupIndex) => (
                            <div key={groupIndex} className="grid grid-cols-12 gap-x-6">
                                {group.map((input, index) => (
                                    <div key={index} className={`w-full ${input.tamanho}`}>
                                        <FormGroup
                                            input={input}
                                            idioma={idioma}
                                            value={data[input.name]}
                                            onChange={onChange}
                                            handleImageCrop={handleImageCrop}
                                        />
                                        {errors[input.name] && <p className="text-sm text-red-500 -mt-5 mb-3">{errors[input.name]}</p>}
                                    </div>
                                ))}
                            </div>
                        ))}

                        <div className="flex items-center justify-end">
                            <Link href={route('Manager.Mostras.index')} className="flex items-center w-fit border border-red-700 text-red-700 px-3 py-2 mr-3 cursor-pointer transition-all hover:bg-red-100">
                                <FontAwesomeIcon icon={faArrowLeft} className="mr-2" />
                                Voltar
                            </Link>

                            <button
                                type="submit"
                                className="block relative w-fit border border-gray-300 px-3 py-2 cursor-pointer transition-all hover:bg-slate-200"
                            >   
                                <FontAwesomeIcon icon={faSave} className="text-slate-700 mr-2" />
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