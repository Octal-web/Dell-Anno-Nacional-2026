import React, { useState, useEffect, useRef } from "react";
import { Link, useForm, usePage } from "@inertiajs/react";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faSave, faImage, faChevronUp, faChevronDown } from "@fortawesome/free-solid-svg-icons";

import { InputText } from "./Inputs/InputText";
import { InputTextArea } from "./Inputs/InputTextArea";
import { InputTipTapEditor } from "./Inputs/InputTipTapEditor";
import { InputFileImage } from "./Inputs/InputFileImage";
import { InputLink } from "./Inputs/InputLink";

const makeFormData = (content) => ({
    conteudosIdiomas: [{
        ...(content.habilitar_titulo ? { titulo: content.titulo ?? "" } : {}),
        ...(content.habilitar_subtitulo ? { subtitulo: content.subtitulo ?? "" } : {}),
        ...(content.habilitar_texto ? { texto: content.texto ?? "" } : {}),
        ...(content.habilitar_link ? {
            link: content.link ?? "",
            nova_aba: content.nova_aba ?? false,
        } : {}),
        ...(content.habilitar_video ? { video: content.video ?? "" } : {}),
    }],
});

export const FormContent = (props) => {
    const { url } = usePage();
    const lang = new URL(url, "http://localhost").searchParams.get("lang") || props.idioma || null;

    return <ContentForm key={JSON.stringify([props.content.id, lang])} {...props} lang={lang} />;
};

const ContentForm = ({ content, full, toolbar, idioma, lang }) => {
    const [isCollapsed, setIsCollapsed] = useState(content.minimizavel);
    const [contentHeight, setContentHeight] = useState("0px");

    const contentRef = useRef(null);
    const contentInnerRef = useRef(null);

    const { data, setData, post, processing, errors, recentlySuccessful } = useForm(
        `FormContent:${content.id}:${lang ?? "default"}`,
        makeFormData(content)
    );

    const updateContentHeight = () => {
        if (!contentRef.current || isCollapsed) return;

        setContentHeight(`${contentRef.current.scrollHeight}px`);
    };

    const updateContentHeightAfterRender = (delay = 0) => {
        return setTimeout(() => {
            requestAnimationFrame(() => {
                updateContentHeight();
            });
        }, delay);
    };

    useEffect(() => {
        if (!content.minimizavel || !contentInnerRef.current) return;

        const resizeObserver = new ResizeObserver(() => {
            updateContentHeight();
        });

        resizeObserver.observe(contentInnerRef.current);

        return () => {
            resizeObserver.disconnect();
        };
    }, [content.minimizavel, isCollapsed]);

    useEffect(() => {
        if (isCollapsed) return;

        const timeout = updateContentHeightAfterRender();

        return () => {
            clearTimeout(timeout);
        };
    }, [isCollapsed]);

    useEffect(() => {
        if (isCollapsed) return;

        const timeout = updateContentHeightAfterRender(150);

        return () => {
            clearTimeout(timeout);
        };
    }, [content.imagem, content.imagem_mobile]);

    const handleChange = (name, value) => {
        const [, index, field] = name.split(".");

        setData((previous) => ({
            ...previous,
            conteudosIdiomas: previous.conteudosIdiomas.map((translation, position) =>
                position === Number(index) ? { ...translation, [field]: value } : translation
            ),
        }));
    };

    const handleCheckboxChange = (name, checked) => {
        handleChange(`conteudosIdiomas.0.${name}`, checked);
    };

    const handleImageCrop = (croppedImage, _fileExtension, name) => {
        setData(name, croppedImage);
    };

    const handleSubmit = (e) => {
        e.preventDefault();

        post(route("Manager.Conteudos.editar", { id: content.id, lang }), {
            preserveScroll: true,
            preserveState: true,

            onSuccess: () => {
                updateContentHeightAfterRender();
                updateContentHeightAfterRender(150);
            },
        });
    };

    const toggleCollapse = () => {
        setIsCollapsed((prev) => !prev);
    };

    return (
        <div className={`mb-6 border border-stroke bg-white px-5 py-5 shadow-md ${content.minimizavel && " h-fit"}`}>
            <div className="flex items-center justify-between">
                <h3 className="text-xl font-bold text-black">{content.bloco}</h3>

                {content.galeria && (
                    <Link
                        href={route("Manager.Imagens.conteudo", { id: content.id })}
                        className="flex items-center border border-stroke bg-white px-3 py-2 transition-all hover:bg-slate-100 ml-2"
                    >
                        <FontAwesomeIcon icon={faImage} className="text-slate-700 mr-2" />
                        Imagens
                    </Link>
                )}

                {content.minimizavel && (
                    <button
                        type="button"
                        onClick={toggleCollapse}
                        className="relative block ml-auto mr-1 before:content-[''] before:absolute before:-top-1 before:-left-1.5 before:w-8 before:h-8 before:border before:rounded-full"
                    >
                        <FontAwesomeIcon icon={isCollapsed ? faChevronDown : faChevronUp} />
                    </button>
                )}
            </div>

            <div
                ref={contentRef}
                style={{
                    height: content.minimizavel ? (isCollapsed ? "0px" : contentHeight) : "auto",
                }}
                className="transition-all duration-300 ease-in-out overflow-hidden"
            >
                <div ref={contentInnerRef} className="mt-10">
                    <form onSubmit={handleSubmit}>
                        {content.habilitar_titulo && (
                            <div className="grid grid-cols-12 gap-x-6">
                                <div className={`col-span-12 ${full ? " lg:col-span-8" : ""}`}>
                                    {content.titulo_formatado ? (
                                        <InputTipTapEditor
                                            toolbar={["Bold", "Italic"]}
                                            title="Título"
                                            name="conteudosIdiomas.0.titulo"
                                            idioma={idioma}
                                            value={data.conteudosIdiomas[0].titulo ?? ""}
                                            onChange={(name, value) => handleChange(name, value)}
                                        />
                                    ) : (
                                        <InputText
                                            title="Título"
                                            name="conteudosIdiomas.0.titulo"
                                            idioma={idioma}
                                            value={data.conteudosIdiomas[0].titulo ?? ""}
                                            onChange={(name, value) => handleChange(name, value)}
                                        />
                                    )}

                                    {errors["conteudosIdiomas.0.titulo"] && (
                                        <p className="text-sm text-red-500 -mt-5 mb-3">{errors["conteudosIdiomas.0.titulo"]}</p>
                                    )}
                                </div>
                            </div>
                        )}

                        {content.habilitar_subtitulo && (
                            <div className="grid grid-cols-12 gap-x-6">
                                <div className={`col-span-12 ${full ? " lg:col-span-8" : ""}`}>
                                    <InputText
                                        title="Subtítulo"
                                        name="conteudosIdiomas.0.subtitulo"
                                        idioma={idioma}
                                        value={data.conteudosIdiomas[0].subtitulo ?? ""}
                                        onChange={(name, value) => handleChange(name, value)}
                                    />

                                    {errors["conteudosIdiomas.0.subtitulo"] && (
                                        <p className="text-sm text-red-500 -mt-5 mb-3">{errors["conteudosIdiomas.0.subtitulo"]}</p>
                                    )}
                                </div>
                            </div>
                        )}

                        {content.habilitar_texto && (
                            <div className="grid grid-cols-12 gap-x-6">
                                <div className={`col-span-12 ${full ? " lg:col-span-8" : ""}`}>
                                    {content.texto_formatado ? (
                                        <InputTipTapEditor
                                            title="Texto"
                                            name="conteudosIdiomas.0.texto"
                                            idioma={idioma}
                                            toolbar={["Bold", "Italic", "Underline", ...(toolbar || [])]}
                                            value={data.conteudosIdiomas[0].texto ?? ""}
                                            onChange={(name, value) => handleChange(name, value)}
                                        />
                                    ) : (
                                        <InputTextArea
                                            title="Texto"
                                            name="conteudosIdiomas.0.texto"
                                            idioma={idioma}
                                            value={data.conteudosIdiomas[0].texto ?? ""}
                                            onChange={(name, value) => handleChange(name, value)}
                                        />
                                    )}

                                    {errors["conteudosIdiomas.0.texto"] && (
                                        <p className="text-sm text-red-500 -mt-5 mb-3">{errors["conteudosIdiomas.0.texto"]}</p>
                                    )}
                                </div>
                            </div>
                        )}

                        {content.habilitar_link && (
                            <div className="grid grid-cols-12 gap-x-6">
                                <div className={`col-span-12 ${full ? " lg:col-span-8" : ""}`}>
                                    <InputLink
                                        title="Link"
                                        name="conteudosIdiomas.0.link"
                                        idioma={idioma}
                                        value={data.conteudosIdiomas[0].link ?? ""}
                                        onChange={(name, value) => handleChange(name, value)}
                                        onCheck={(name, value) => handleCheckboxChange(name, value)}
                                        novaAba={data.conteudosIdiomas[0].nova_aba ?? false}
                                    />

                                    {errors["conteudosIdiomas.0.link"] && (
                                        <p className="text-sm text-red-500 -mt-5 mb-3">{errors["conteudosIdiomas.0.link"]}</p>
                                    )}
                                </div>
                            </div>
                        )}

                        {content.habilitar_video && (
                            <div className="grid grid-cols-12 gap-x-6">
                                <div className={`col-span-12 ${full ? " lg:col-span-8" : ""}`}>
                                    <InputLink
                                        title="Vídeo"
                                        name="conteudosIdiomas.0.video"
                                        idioma={idioma}
                                        value={data.conteudosIdiomas[0].video ?? ""}
                                        onChange={(name, value) => handleChange(name, value)}
                                    />

                                    {errors["conteudosIdiomas.0.video"] && (
                                        <p className="text-sm text-red-500 -mt-5 mb-3">{errors["conteudosIdiomas.0.video"]}</p>
                                    )}
                                </div>
                            </div>
                        )}

                        {content.habilitar_img && (
                            <div className="grid grid-cols-12 gap-x-6">
                                <div className={`col-span-12 ${full ? " lg:col-span-8" : ""}`}>
                                    <InputFileImage
                                        title="Imagem"
                                        name="img"
                                        imagem={content.imagem}
                                        size={{ largura: content.largura_img, altura: content.altura_img }}
                                        allowCrop={content.recortar_img ? true : false}
                                        onImageCrop={handleImageCrop}
                                    />

                                    {errors.img && (
                                        <p className="text-sm text-red-500 -mt-5 mb-3">{errors.img}</p>
                                    )}
                                </div>

                                {content.habilitar_img_mobile && (
                                    <div className={`col-span-12 ${full ? " lg:col-span-4" : ""}`}>
                                        <InputFileImage
                                            title="Imagem Mobile"
                                            name="img_mobile"
                                            imagem={content.imagem_mobile}
                                            size={{ largura: content.largura_img_mobile, altura: content.altura_img_mobile }}
                                            allowCrop={content.recortar_img_mobile ? true : false}
                                            onImageCrop={handleImageCrop}
                                        />

                                        {errors.img_mobile && (
                                            <p className="text-sm text-red-500 -mt-5 mb-3">{errors.img_mobile}</p>
                                        )}
                                    </div>
                                )}
                            </div>
                        )}

                        <div className="flex items-center justify-end">
                            <button
                                type="submit"
                                disabled={processing}
                                className="block relative w-fit border border-gray-300 px-3 py-2 cursor-pointer transition-all hover:bg-slate-200 disabled:opacity-60 disabled:cursor-not-allowed"
                            >
                                <FontAwesomeIcon icon={faSave} className="text-slate-700 mr-2" />
                                {processing ? "Salvando..." : recentlySuccessful ? "Salvo!" : "Salvar"}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
};