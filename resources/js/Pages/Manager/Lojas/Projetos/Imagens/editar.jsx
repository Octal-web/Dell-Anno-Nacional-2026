import React from 'react';
import { Link, usePage, useForm } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faImages, faSave, faArrowLeft } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminLayout';
import { Breadcrumb } from '@/Components/Manager/Breadcrumb';
import { FormGroup } from '@/Components/Manager/Inputs/FormGroup';

const Page = () => {
    const { idioma, idiomas, imagemItem, acabamentos, colecoes } = usePage().props;

    const { data, setData, post, processing, errors } = useForm(imagemItem);

    const breadcrumbItems = [
        { label: 'Inspiração', link: 'Manager.Lojas.Projetos.index' },
        { label: 'Projetos', link: 'Manager.Lojas.Projetos.index' },
        { label: 'Galeria', link: 'Manager.Lojas.Projetos.Imagens.index', params: { id: imagemItem.projeto_id } },
    ];

    const inputItems = [
        [{ titulo: 'Imagem', name: 'img', tamanho: 'col-span-12 md:col-span-6', tipo: 'imagem', crop: true, largura: 450, altura: 357, imagem: imagemItem.imagem }],
        [{ titulo: 'Detalhes', name: 'detalhes', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto_longo', editor: false, max: 720 }],
        [{ titulo: 'Acabamentos', name: 'acabamentos', tamanho: 'col-span-12 md:col-span-6 lg:col-span-4', tipo: 'select', isMulti: true, options: acabamentos }, { titulo: 'Coleções', name: 'colecoes', tamanho: 'col-span-12 md:col-span-6 lg:col-span-4', tipo: 'select', isMulti: true, options: colecoes }],
    ];

    const handleSubmit = (e) => {
        e.preventDefault();
        const idioma_url = new URLSearchParams(window.location.search).get('lang');

        post(route('Manager.Lojas.Projetos.Imagens.atualizar', { id: imagemItem.id, lang: idioma_url }), {
            preserveScroll: true,
        });
    };

    const onChange = (name, value) => {
        setData(prevData => ({
            ...prevData,
            [name]: value,
        }));
    };

    const handleImageCrop = (croppedImage, fileExtension, name) => {
        setData(prevData => ({
            ...prevData,
            [name]: croppedImage,
        }));
    };

    return (
        <AdminLayout>
            <Breadcrumb icon={faImages} items={breadcrumbItems} current="Editar" idioma={idioma.codigo} idiomas={idiomas} id={imagemItem.id} />

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
                            <Link href={route('Manager.Lojas.Projetos.Imagens.index', { id: imagemItem.projeto_id })} className="flex items-center w-fit border border-red-700 text-red-700 px-3 py-2 mr-3 cursor-pointer transition-all hover:bg-red-100">
                                <FontAwesomeIcon icon={faArrowLeft} className="mr-2" />
                                Voltar
                            </Link>

                            <button type="submit" disabled={processing} className="block relative w-fit border border-gray-300 px-3 py-2 cursor-pointer transition-all hover:bg-slate-200 disabled:opacity-50">
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
