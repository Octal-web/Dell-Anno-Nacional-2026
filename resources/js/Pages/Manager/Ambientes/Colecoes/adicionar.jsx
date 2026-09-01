import React from 'react';
import { Link, useForm, usePage } from '@inertiajs/react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faArrowLeft, faLayerGroup, faSave } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminLayout';
import { Breadcrumb } from '@/Components/Manager/Breadcrumb';
import { FormGroup } from '@/Components/Manager/Inputs/FormGroup';

const Page = () => {
    const { idioma, id } = usePage().props;
    const { data, setData, post, processing, errors } = useForm({
        nome: '',
        descricao_curta: '',
        descricao: '',
    });
    const inputItems = [
        [{ titulo: 'Nome', name: 'nome', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto', max: 120 }],
        [{ titulo: 'Descrição curta', name: 'descricao_curta', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto_longo', editor: false, max: 320 }],
        [{ titulo: 'Descrição', name: 'descricao', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto_longo', editor: false, max: 720 }],
    ];

    const handleSubmit = (event) => {
        event.preventDefault();
        post(route('Manager.Ambientes.Colecoes.novo', { id }), { preserveScroll: true });
    };

    return (
        <AdminLayout>
            <Breadcrumb
                icon={faLayerGroup}
                items={[
                    { label: 'Ambientes', link: 'Manager.Ambientes.index' },
                    { label: 'Coleções', link: 'Manager.Ambientes.Colecoes.index', params: { id } },
                ]}
                current="Adicionar"
                idioma={idioma.codigo}
            />

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
                                            onChange={(name, value) => setData(name, value)}
                                        />
                                        {errors[input.name] && (
                                            <p className="-mt-5 mb-3 text-sm text-red-500">{errors[input.name]}</p>
                                        )}
                                    </div>
                                ))}
                            </div>
                        ))}

                        <div className="flex justify-end">
                            <Link
                                href={route('Manager.Ambientes.Colecoes.index', { id })}
                                className="mr-3 flex items-center border border-red-700 px-3 py-2 text-red-700"
                            >
                                <FontAwesomeIcon icon={faArrowLeft} className="mr-2" />
                                Voltar
                            </Link>
                            <button type="submit" disabled={processing} className="flex items-center border px-3 py-2">
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
