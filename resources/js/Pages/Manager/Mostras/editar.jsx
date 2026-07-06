import React, { useEffect, useRef, useState } from 'react';
import { Link, router, usePage, useForm } from '@inertiajs/react';
import { ReactSortable } from 'react-sortablejs';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faPalette, faPlus, faSave, faArrowLeft, faTrash, faCheck, faTimes, faCity } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminLayout';
import { Breadcrumb } from '@/Components/Manager/Breadcrumb';
import { FormGroup } from '@/Components/Manager/Inputs/FormGroup';
import { ConfirmModal } from '@/Components/Manager/ConfirmModal';

const IndividualYearItem = ({ ano, index, onAnoUpdated }) => {
    const [isChecked, setIsChecked] = useState(ano.visivel || false);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [editing, setEditing] = useState(false);
    const [visibilityLoading, setVisibilityLoading] = useState(false);
    const [cellWidths, setCellWidths] = useState([]);

    const rowRef = useRef(null);
    const inputRef = useRef(null);

    const { data, setData, post, processing, errors, clearErrors } = useForm({
        ano: ano.ano || '',
    });

    useEffect(() => {
        const adjustCellWidths = () => {
            if (rowRef.current) {
                const widths = Array.from(rowRef.current.children).map(cell => cell.offsetWidth);
                setCellWidths(widths);
            }
        };

        adjustCellWidths();
        window.addEventListener('resize', adjustCellWidths);

        return () => {
            window.removeEventListener('resize', adjustCellWidths);
        };
    }, []);

    useEffect(() => {
        setIsChecked(ano.visivel || false);
        setData('ano', ano.ano || '');
    }, [ano]);

    useEffect(() => {
        if (editing && inputRef.current) {
            inputRef.current.focus();
            inputRef.current.select();
        }
    }, [editing]);

    const handleVisibility = () => {
        const previousValue = isChecked;

        setIsChecked(!previousValue);
        setVisibilityLoading(true);

        router.post(route('Manager.Mostras.visibilidade', { id: ano.id }), {}, {
            preserveScroll: true,
            onError: () => {
                setIsChecked(previousValue);
            },
            onFinish: () => {
                setVisibilityLoading(false);
            }
        });
    };

    const handleSubmitAno = (e) => {
        e.preventDefault();

        post(route('Manager.Mostras.atualizarAno', { id: ano.id }), {
            preserveScroll: true,
            onSuccess: () => {
                setEditing(false);
                clearErrors();
                onAnoUpdated(ano.id, data.ano);
            },
        });
    };

    const handleCancelEdit = () => {
        setData('ano', ano.ano || '');
        setEditing(false);
        clearErrors();
    };

    const handleKeyDown = (e) => {
        if (e.key === 'Escape') {
            handleCancelEdit();
        }
    };

    return (
        <tr ref={rowRef} className="bg-slate-50">
            <td className="border px-4 w-1/6 py-4" width={`${cellWidths[0] || 'auto'}`}>
                {index + 1}
            </td>

            <td className="border px-4 py-4" width={cellWidths[1] || 'auto'}>
                {!editing ? (
                    <button
                        type="button"
                        onDoubleClick={() => setEditing(true)}
                        className="cursor-text text-left transition-all hover:text-slate-600"
                        title="Clique duas vezes para editar o ano"
                    >
                        {ano.ano}
                    </button>
                ) : (
                    <form onSubmit={handleSubmitAno} className="relative inline-flex items-center">
                        <input
                            ref={inputRef}
                            type="text"
                            value={data.ano}
                            maxLength={4}
                            onChange={(e) => setData('ano', e.target.value)}
                            onKeyDown={handleKeyDown}
                            className="w-24 border border-gray-300 px-3 py-2 text-sm outline-none focus:border-slate-700"
                        />

                        <div className="absolute left-full ml-2 flex items-center rounded bg-white shadow-md border border-gray-200 overflow-hidden z-10">
                            <button
                                type="submit"
                                disabled={processing}
                                className="px-3 py-2 text-green-700 transition-all hover:bg-green-50 disabled:opacity-50"
                                title="Salvar ano"
                            >
                                <FontAwesomeIcon icon={faCheck} />
                            </button>

                            <button
                                type="button"
                                disabled={processing}
                                onClick={handleCancelEdit}
                                className="px-3 py-2 text-red-700 transition-all hover:bg-red-50 disabled:opacity-50"
                                title="Cancelar"
                            >
                                <FontAwesomeIcon icon={faTimes} />
                            </button>
                        </div>

                        {errors.ano && (
                            <p className="absolute top-full left-0 mt-1 whitespace-nowrap text-sm text-red-500">
                                {errors.ano}
                            </p>
                        )}
                    </form>
                )}
            </td>

            <td className="border px-4 py-4 w-1/6" width={`${cellWidths[2] || 'auto'}`}>
                <label className="cursor-pointer sort-ignore">
                    <input
                        type="checkbox"
                        checked={isChecked}
                        onChange={handleVisibility}
                        className="sr-only peer"
                        disabled={visibilityLoading}
                    />

                    <div className={`relative w-9 h-5 ${visibilityLoading ? 'opacity-50' : ''} bg-gray-200 peer-focus:outline-none peer-focus:ring-0 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600`} />
                </label>
            </td>

            <td className="border max-sm:text-center px-2 md:px-4 py-2 w-1/6 sort-ignore" width={`${cellWidths[3] || 'auto'}`}>
                <Link
                    href={route('Manager.Mostras.Cidades.index', { id: ano.id })}
                    className="h-5 w-5 relative mr-4 z-[1] before:content-[''] before:absolute before:-top-[8px] before:-left-[10px] before:w-9 before:h-9 before:bg-slate-200 before:rounded-full before:-mt-[-2px] before:-z-[1] before:transition-all before:transform before:scale-0 hover:before:scale-100"
                    title="Cidades da Mostra"
                >
                    <FontAwesomeIcon icon={faCity} className="text-slate-700" />
                </Link>

                <button
                    type="button"
                    className="h-5 w-5 relative z-[1] before:content-[''] before:absolute before:-top-[7px] before:-left-[8px] before:w-9 before:h-9 before:bg-slate-200 before:rounded-full before:-mt-[-2px] before:-z-[1] before:transition-all before:transform before:scale-0 hover:before:scale-100"
                    onClick={() => setIsModalOpen(true)}
                    disabled={visibilityLoading || processing}
                >
                    <FontAwesomeIcon icon={faTrash} className="text-red-700" />
                </button>

                {isModalOpen && (
                    <ConfirmModal
                        icon={faTrash}
                        closeModal={() => setIsModalOpen(false)}
                        type="delete"
                        confirm={route('Manager.Mostras.excluir', { id: ano.id })}
                    />
                )}
            </td>
        </tr>
    );
};

const Page = () => {
    const { idioma, idiomas, mostra, anos, tipoFixo } = usePage().props;

    const [state, setState] = useState(anos || []);
    const previousStateRef = useRef(state);
    const [isUpdated, setIsUpdated] = useState(false);

    const { data, setData, post, processing, errors } = useForm(mostra);
    const { setData: setSortData, post: postSort } = useForm({});

    const breadcrumbItems = [
        { label: 'Inspiração', link: 'Manager.Mostras.index' },
        { label: 'Mostras de decoração', link: 'Manager.Mostras.index' },
    ];

    const inputItems = [
        ...(!tipoFixo ? [
            [
                { titulo: 'Nome', name: 'nome', tamanho: 'col-span-12 lg:col-span-6', tipo: 'texto', max: 72 },
                { titulo: 'Ano', name: 'ano', tamanho: 'col-span-2', tipo: 'numero', min: 1900, max: 2113 },
            ],
        ] : [
            [{ titulo: 'Nome', name: 'nome', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto', max: 72 }],
        ]),

        [{ titulo: 'Descrição', name: 'descricao', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto_longo', editor: false }],
        [{ titulo: 'Imagem', name: 'img', tamanho: 'col-span-12 md:col-span-6 lg:col-span-4', tipo: 'imagem', crop: true, largura: 960, altura: 650, imagem: mostra.imagem }],
        [{ titulo: 'Título da Página', name: 'titulo_pagina', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto', max: 72 }],
        [{ titulo: 'Descrição da Página', name: 'descricao_pagina', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto_longo', editor: false }],
    ];

    useEffect(() => {
        setState(anos || []);
        previousStateRef.current = anos || [];
    }, [anos]);

    useEffect(() => {
        const previousState = previousStateRef.current;

        if (JSON.stringify(state) !== JSON.stringify(previousState)) {
            const orderedData = state.map((item, index) => ({
                id: item.id,
                ordem: index
            }));

            setSortData(prevData => ({ ...prevData, odr: orderedData }));
            setIsUpdated(true);
        }

        previousStateRef.current = state;
    }, [state]);

    useEffect(() => {
        if (isUpdated) {
            postSort(route('Manager.Mostras.ordenar'), {
                preserveScroll: true,
            });

            setIsUpdated(false);
        }
    }, [isUpdated]);

    const handleSubmit = (e) => {
        e.preventDefault();

        const idioma_url = new URLSearchParams(window.location.search).get('lang');

        post(route('Manager.Mostras.atualizar', { id: mostra.id, lang: idioma_url }), {
            preserveScroll: true,
        });
    };

    const handleAnoUpdated = (id, novoAno) => {
        const newState = state.map(item => {
            if (item.id === id) {
                return {
                    ...item,
                    ano: novoAno,
                    nome: novoAno,
                };
            }

            return item;
        });

        setState(newState);
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
            <Breadcrumb icon={faPalette} items={breadcrumbItems} current="Editar" idioma={idioma.codigo} idiomas={idiomas} id={mostra.id} />

            <div className="mb-6 border border-stroke bg-white px-5 py-5 shadow-md">
                {!tipoFixo && mostra.ano_id && (
                    <Link
                        href={route('Manager.Mostras.Cidades.index', { id: mostra.ano_id })}
                        className="flex items-center border border-stroke bg-white px-3 py-2 float-right rounded-md transition-all hover:bg-slate-100 ml-2"
                    >
                        <FontAwesomeIcon icon={faCity} className="text-slate-700 mr-2" />
                        Cidades
                    </Link>
                )}

                <div className={tipoFixo ? "mt-10" : "mt-12"}>
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

                                        {errors[input.name] && (
                                            <p className="text-sm text-red-500 -mt-5 mb-3">
                                                {errors[input.name]}
                                            </p>
                                        )}
                                    </div>
                                ))}
                            </div>
                        ))}

                        <div className="flex items-center justify-end mb-10">
                            <Link
                                href={route('Manager.Mostras.index')}
                                className="flex items-center w-fit border border-red-700 text-red-700 px-3 py-2 mr-3 cursor-pointer transition-all hover:bg-red-100"
                            >
                                <FontAwesomeIcon icon={faArrowLeft} className="mr-2" />
                                Voltar
                            </Link>

                            <button
                                type="submit"
                                disabled={processing}
                                className="block relative w-fit border border-gray-300 px-3 py-2 cursor-pointer transition-all hover:bg-slate-200 disabled:opacity-50"
                            >
                                <FontAwesomeIcon icon={faSave} className="text-slate-700 mr-2" />
                                Salvar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {tipoFixo && (
                <div className="relative mb-6 border border-stroke bg-white px-5 py-5 shadow-md">
                    <div className="flex items-center justify-between">
                        <h3 className="text-xl font-bold text-black">Anos</h3>

                        <Link
                            href={route('Manager.Mostras.adicionar', { tipo: mostra.slug })}
                            className="block relative w-fit border border-gray-300 px-3 py-2 cursor-pointer transition-all hover:bg-slate-200"
                        >
                            <FontAwesomeIcon icon={faPlus} className="text-slate-700 mr-2" />
                            Adicionar ano
                        </Link>
                    </div>

                    <p className="text-xs text-slate-500">
                        Clique duas vezes no ano para editar rapidamente.
                    </p>

                    <div className="mt-10 overflow-x-auto no-scrollbar">
                        <table className="w-full min-w-[30rem] border-collapse">
                            <thead>
                                <tr>
                                    <th className="border px-4 py-4 w-1/6 text-left">#</th>
                                    <th className="border px-4 py-4 text-left">Ano</th>
                                    <th className="border px-4 py-4 w-1/6 text-left">Visível</th>
                                    <th className="border px-4 py-4 w-1/6 text-left">Ações</th>
                                </tr>
                            </thead>

                            <ReactSortable
                                animation={150}
                                list={state}
                                setList={setState}
                                forceFallback={true}
                                tag="tbody"
                                filter=".sort-ignore"
                            >
                                {state.map((ano, index) => (
                                    <IndividualYearItem
                                        key={ano.id}
                                        ano={ano}
                                        index={index}
                                        onAnoUpdated={handleAnoUpdated}
                                    />
                                ))}
                            </ReactSortable>
                        </table>
                    </div>

                    {isUpdated && (
                        <div className="absolute inset-0 bg-white rounded-sm bg-opacity-50 flex items-center justify-center">
                            <div className="absolute h-16 w-16 animate-spin rounded-full border-4 border-solid border-black border-t-transparent"></div>
                        </div>
                    )}
                </div>
            )}
        </AdminLayout>
    );
};

export default Page;