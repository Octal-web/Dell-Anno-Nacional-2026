import React from 'react';
import { Link, usePage, useForm } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faHouse, faImage, faSave, faArrowLeft } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminLayout';
import { Breadcrumb } from '@/Components/Manager/Breadcrumb';
import { FormGroup } from '@/Components/Manager/Inputs/FormGroup';

const Page = () => {
    const { idioma, idiomas, mostraCidade } = usePage().props;

    const { data, setData, post, processing, errors } = useForm(mostraCidade);

    const breadcrumbItems = [
        { label: 'Produtos', link: 'Manager.Produtos.index' },
        { label: 'Inspiração', link: 'Manager.Mostras.index' },
        { label: 'Mostras de decoração', link: 'Manager.Mostras.index' },
        { label: 'Editar Mostra', link: 'Manager.Mostras.editar', params: { id: mostraCidade.mostra_ano_id }},
        { label: mostraCidade.nome, link: 'Manager.Mostras.editar', params: { id: mostraCidade.mostra_ano_id }},
    ];

    const inputItems = [
        [{ titulo: 'Nome', name: 'nome', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto', max: 80 }],
        [{ titulo: 'Cidade', name: 'cidade', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto', max: 80 }],
        [{ titulo: 'Descrição', name: 'descricao', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto_longo', editor: false, max: 1080 }]
    ];
    
    const handleSubmit = (e) => {
        e.preventDefault();
        const idioma_url = new URLSearchParams(window.location.search).get('lang');

        post(route('Manager.Mostras.Cidades.atualizar', {id: mostraCidade.id, lang: idioma_url}), {
            preserveScroll: true,
        });
        console.log(data);

        console.log(errors);
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
            <Breadcrumb icon={faHouse} items={breadcrumbItems} current="Editar" idioma={idioma.codigo} idiomas={idiomas} id={mostraCidade.id} />

            <div className="mb-6 rounded-sm border border-stroke bg-white px-5 py-5 shadow-md">
                <Link
                    href={route('Manager.Mostras.Cidades.Imagens.index', { id: mostraCidade.id })}
                    className="flex items-center border border-stroke bg-white px-3 py-2 float-right rounded-md transition-all hover:bg-slate-100 ml-2"
                >
                    <FontAwesomeIcon icon={faImage} className="text-slate-700 mr-2" />
                    Imagens
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
                                            onChange={onChange}
                                            handleImageCrop={handleImageCrop}
                                        />
                                        {errors[input.name] && <p className="text-sm text-red-500 -mt-5 mb-3">{errors[input.name]}</p>}
                                    </div>
                                ))}
                            </div>
                        ))}

                        <div className="flex items-center justify-end">
                            <Link href={route('Manager.Mostras.Cidades.index', {id: mostraCidade.mostra_ano_id})} className="flex items-center w-fit rounded-lg border border-red-700 text-red-700 px-3 py-2 mr-3 cursor-pointer transition-all hover:bg-red-100">
                                <FontAwesomeIcon icon={faArrowLeft} className="mr-2" />
                                Voltar
                            </Link>

                            <button
                                type="submit"
                                className="block relative w-fit rounded-lg border border-gray-300 px-3 py-2 cursor-pointer transition-all hover:bg-slate-200"
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