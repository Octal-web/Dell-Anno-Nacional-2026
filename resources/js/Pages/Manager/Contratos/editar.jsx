import React, { useState, useEffect } from "react";
import { Link, usePage, useForm } from "@inertiajs/react";

import Select from "react-select";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
    faBuilding,
    faSave,
    faArrowLeft,
    faImage,
} from "@fortawesome/free-solid-svg-icons";

import AdminLayout from "@/Layouts/AdminLayout";
import { Breadcrumb } from "@/Components/Manager/Breadcrumb";
import { FormGroup } from "@/Components/Manager/Inputs/FormGroup";

const Page = () => {
    const { idioma, idiomas, contrato } = usePage().props;

    const { data, setData, post, processing, errors } = useForm(contrato);

    const breadcrumbItems = [
        { label: "Contratos", link: "Manager.Contratos.index" },
    ];

    const inputItems = [
        [
            {
                titulo: "Título",
                name: "titulo",
                tamanho: "cmd:col-span-6 lg:col-span-4",
                tipo: "texto",
                max: 100,
            },
            {
                titulo: "Subtítulo",
                name: "subtitulo",
                tamanho: "col-span-12 md:col-span-6 lg:col-span-4",
                tipo: "texto",
                max: 100,
            },
        ],
        [
            {
                titulo: "Texto",
                name: "texto",
                tamanho: "col-span-12 lg:col-span-8",
                tipo: "texto_longo",
                max: 500,
            },
        ],
        [
            {
                titulo: "Imagem",
                name: "img",
                tamanho: "col-span-12 md:col-span-8",
                tipo: "imagem",
                crop: true,
                largura: 740,
                altura: 400,
                imagem: contrato.imagem,
            },
        ],
    ];

    const handleSubmit = (e) => {
        e.preventDefault();
        const idioma_url = new URLSearchParams(window.location.search).get(
            "lang",
        );

        post(
            route("Manager.Contratos.atualizar", {
                id: contrato.id,
                lang: idioma_url,
            }),
            {
                preserveScroll: true,
            },
        );
        console.log(data);

        console.log(errors);
    };

    const onChange = (name, value) => {
        setData((prevData) => ({
            ...prevData,
            [name]: value,
        }));
    };

    const handleImageCrop = (croppedImage, fileExtension, name) => {
        setData((prevData) => ({
            ...prevData,
            [name]: croppedImage,
        }));

        if (name === "img") {
            const resizeBlobImage = (blob, scale = 0.40625) => {
                return new Promise((resolve) => {
                    const img = new Image();
                    img.onload = () => {
                        const canvas = document.createElement("canvas");
                        const ctx = canvas.getContext("2d");

                        const newWidth = img.width * scale;
                        const newHeight = img.height * scale;

                        canvas.width = newWidth;
                        canvas.height = newHeight;
                        ctx.drawImage(img, 0, 0, newWidth, newHeight);

                        canvas.toBlob((resizedBlob) => {
                            resolve(resizedBlob);
                        }, blob.type);
                    };

                    img.src = URL.createObjectURL(blob);
                });
            };

            resizeBlobImage(croppedImage).then((resizedBlob) => {
                setData((prevData) => ({
                    ...prevData,
                    [`${name}_alt`]: resizedBlob,
                }));
            });
        }
    };

    return (
        <AdminLayout>
            <Breadcrumb
                icon={faBuilding}
                items={breadcrumbItems}
                current="Editar"
                idioma={idioma.codigo}
                idiomas={idiomas}
                id={contrato.id}
            />

            <div className="mb-6 border border-stroke bg-white px-5 py-5 shadow-md">
                <Link
                    href={route("Manager.Contratos.Imagens.index", {
                        id: contrato.id,
                    })}
                    className="flex items-center border border-stroke bg-white px-3 py-2 float-right rounded-md transition-all hover:bg-slate-100 ml-2"
                >
                    <FontAwesomeIcon
                        icon={faImage}
                        className="text-slate-700 mr-2"
                    />
                    Imagens
                </Link>
                <div className="mt-10">
                    <form onSubmit={handleSubmit}>
                        {inputItems.map((group, groupIndex) => (
                            <div
                                key={groupIndex}
                                className="grid grid-cols-12 gap-x-6"
                            >
                                {group.map((input, index) => (
                                    <div
                                        key={index}
                                        className={`w-full ${input.tamanho}`}
                                    >
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

                        <div className="flex items-center justify-end">
                            <Link
                                href={route("Manager.Contratos.index")}
                                className="flex items-center w-fit border border-red-700 text-red-700 px-3 py-2 mr-3 cursor-pointer transition-all hover:bg-red-100"
                            >
                                <FontAwesomeIcon
                                    icon={faArrowLeft}
                                    className="mr-2"
                                />
                                Voltar
                            </Link>

                            <button
                                type="submit"
                                className="block relative w-fit border border-gray-300 px-3 py-2 cursor-pointer transition-all hover:bg-slate-200"
                            >
                                <FontAwesomeIcon
                                    icon={faSave}
                                    className="text-slate-700 mr-2"
                                />
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
