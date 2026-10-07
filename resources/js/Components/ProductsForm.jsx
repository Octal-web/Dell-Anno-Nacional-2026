import React, { useState, useEffect } from 'react';
import { useForm, usePage } from '@inertiajs/react';

import { InputMask } from "@react-input/mask";
import { SiteSelect } from './SiteSelect';

import AnimatedCheckMark from './AnimatedCheckMark';

const steps = [
    'Preencha o formulário e clique em "enviar".',
    'Em até 24 horas, um de nossos consultores entrará em contato com você.',
    'O atendimento exclusivo acompanha toda sua trajetória até a revenda autorizada Dell Anno mais próxima de você.',
    'Em seguida, a loja dá continuidade para lhe oferecer o melhor orçamento, juntamente ao seu arquiteto, totalmente sem custo e com todo apoio do atendimento de fábrica!',
];

// Chaves espelhadas em ContactService::EXPECTATIVAS
const expectativas = [
    { value: '20_40', label: 'Entre R$ 20.000,00 a R$ 40.000,00' },
    { value: '40_60', label: 'Entre R$ 40.000,00 a R$ 60.000,00' },
    { value: '60_80', label: 'Entre R$ 60.000,00 a R$ 80.000,00' },
    { value: 'a_80', label: 'Acima de R$ 80.000,00' },
];

export const ProductsForm = ({ content, posicaoForm = "Não informado" }) => {
    const { message } = usePage().props;

    const [showInfo, setShowInfo] = useState(false);
    const [isSuccessful, setIsSuccessful] = useState(false);

    const [phoneMask, setPhoneMask] = useState("(__) ____-____");

    const { data, setData, post, processing, errors, clearErrors } = useForm({
        nome: '',
        email: '',
        telefone: '',
        cep: '',
        expectativa_investimento: '',
        politica: false,

        origem: "",
        campanha: "",
        grupo: "",
        anuncio: "",
        termo: "",
        entrada: "",
        posicao_formulario: posicaoForm
    });

    useEffect(() => {
        const params = new URLSearchParams(window.location.search);

        const now = new Date();

        now.setHours(now.getHours() - 3);

        const entrada = now.toISOString().slice(0, 19).replace("T", " ");

        setData((currentData) => ({
            ...currentData,

            origem: params.get("origin") || params.get("utm_source") || "",

            campanha:
                params.get("campaign") || params.get("utm_campaign") || "",

            grupo:
                params.get("group") ||
                params.get("utm_group") ||
                params.get("utm_medium") ||
                "",

            anuncio: params.get("ad") || params.get("utm_content") || "",
            termo: params.get("utm_term") || "",

            entrada,
        }));
    }, []);

    useEffect(() => {
        const numbers = data.telefone.replace(/\D/g, "");

        setPhoneMask(
            numbers.length >= 10 ? "(__) _____-____" : "(__) ____-____",
        );
    }, [data.telefone]);

    const handleChange = (e) => {
        const { name, value, type, checked } = e.target;
        setData(prevData => ({
            ...prevData,
            [name]: type === 'checkbox' ? checked : value
        }));
        clearErrors(name);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('Contato.enviar'), {
            preserveScroll: true
        });
    };

    useEffect(() => {
        if (message && message.type === 'success') {
            setIsSuccessful(true);

            setTimeout(() => {
                setData((currentData) => ({
                    ...currentData,
                    nome: '',
                    email: '',
                    telefone: '',
                    cep: '',
                    expectativa_investimento: '',
                    politica: false,
                }));

                setIsSuccessful(false);
            }, 3000);
        }
    }, [message]);

    return (
        <section className="relative mb-20 md:mb-30">
            <div className="container max-w-medium">
                <h2 className="text-3xl sm:text-4xl 2xl:text-[45px] font-light uppercase leading-snug sm:tracking-wider mb-10 pt-16 md:pt-24 2xl:pt-36 border-t">{content.titulo}</h2>

                <div className="grid grid-cols-1 lg:grid-cols-2 gap-14 lg:gap-16 2xl:gap-24">
                    <ol className="flex flex-col gap-8 md:gap-10 max-w-md">
                        {steps.map((step, index) => (
                            <li key={index} className="flex items-center gap-5 md:gap-8">
                                <span className="shrink-0 w-14 md:w-20 text-6xl md:text-7xl 2xl:text-8xl text-neutral-300 font-extralight leading-none">{index + 1}.</span>
                                <p className="font-secondary font-light leading-relaxed tracking-wide text-neutral-700">{step}</p>
                            </li>
                        ))}
                    </ol>

                    <form
                        className="relative w-full"
                        id="budget--form"
                        onSubmit={handleSubmit}
                    >
                        <div className="mb-3 md:mb-5 min-[1440px]:mb-7 flex gap-3 md:gap-10 flex-col md:flex-row">
                            <div className="w-full md:w-1/2">
                                <label htmlFor="nome" className="inline-block font-secondary text-neutral-600 2xl:mb-2">Nome</label>
                                <input type="text" id="nome" name="nome" value={data.nome} onChange={handleChange} placeholder="Seu nome completo" className="w-full h-12 px-0 font-secondary border-0 border-b border-b-gray-300 focus:outline-none focus:ring-0 focus:border-b-black focus:shadow-inner transition-colors duration-200 placeholder:text-gray-500 placeholder:text-opacity-50" />
                                {errors.nome && <p className="text-xs text-white bg-red-900 px-3 py-1.5 mt-2">{errors.nome}</p>}
                            </div>

                            <div className="w-full md:w-1/2">
                                <label htmlFor="telefone" className="inline-block font-secondary text-neutral-600 2xl:mb-2">Telefone</label>
                                <InputMask type="text" id="telefone" name="telefone" mask={phoneMask} value={data.telefone} replacement={{ _: /\d/ }} onChange={handleChange} placeholder="Seu número de telefone" className="w-full h-12 px-0 font-secondary border-0 border-b border-b-gray-300 focus:outline-none focus:ring-0 focus:border-b-black focus:shadow-inner transition-colors duration-200 placeholder:text-gray-500 placeholder:text-opacity-50" />
                                {errors.telefone && <p className="text-xs text-white bg-red-900 px-3 py-1.5 mt-2">{errors.telefone}</p>}
                            </div>
                        </div>

                        <div className="mb-3 md:mb-5 min-[1440px]:mb-7 flex gap-3 md:gap-10 flex-col md:flex-row">
                            <div className="w-full md:w-1/2">
                                <label htmlFor="email" className="inline-block font-secondary text-neutral-600 2xl:mb-2">E-mail</label>
                                <input type="text" id="email" name="email" value={data.email} onChange={handleChange} placeholder="Seu e-mail" className="w-full h-12 px-0 font-secondary border-0 border-b border-b-gray-300 focus:outline-none focus:ring-0 focus:border-b-black focus:shadow-inner transition-colors duration-200 placeholder:text-gray-500 placeholder:text-opacity-50" />
                                {errors.email && <p className="text-xs text-white bg-red-900 px-3 py-1.5 mt-2">{errors.email}</p>}
                            </div>

                            <div className="w-full md:w-1/2">
                                <label htmlFor="cep" className="inline-block font-secondary text-neutral-600 2xl:mb-2">CEP</label>
                                <InputMask type="text" id="cep" name="cep" mask="_____-___" value={data.cep} replacement={{ _: /\d/ }} onChange={handleChange} placeholder="Seu CEP" className="w-full h-12 px-0 font-secondary border-0 border-b border-b-gray-300 focus:outline-none focus:ring-0 focus:border-b-black focus:shadow-inner transition-colors duration-200 placeholder:text-gray-500 placeholder:text-opacity-50" />
                                {errors.cep && <p className="text-xs text-white bg-red-900 px-3 py-1.5 mt-2">{errors.cep}</p>}
                            </div>
                        </div>

                        <div className="mb-3 md:mb-5 min-[1440px]:mb-7">
                            <label htmlFor="expectativa_investimento" className="inline-block font-secondary text-neutral-600 2xl:mb-2">Expectativa de investimento</label>
                            <SiteSelect
                                inputId="expectativa_investimento"
                                name="expectativa_investimento"
                                options={expectativas}
                                value={expectativas.find(option => option.value === data.expectativa_investimento) || null}
                                onChange={(selected) => {
                                    setData('expectativa_investimento', selected?.value || '');
                                    clearErrors('expectativa_investimento');
                                }}
                                isDisabled={processing}
                                isSearchable={false}
                                placeholder="Selecione uma faixa de investimento"
                                classNamePrefix="signup-select"
                            />
                            {errors.expectativa_investimento && <p className="text-xs text-white bg-red-900 px-3 py-1.5 mt-2">{errors.expectativa_investimento}</p>}
                        </div>

                        <input
                            type="hidden"
                            name="origem"
                            value={data.origem}
                        />

                        <input
                            type="hidden"
                            name="campanha"
                            value={data.campanha}
                        />

                        <input
                            type="hidden"
                            name="grupo"
                            value={data.grupo}
                        />

                        <input
                            type="hidden"
                            name="anuncio"
                            value={data.anuncio}
                        />

                        <input
                            type="hidden"
                            name="termo"
                            value={data.termo}
                        />

                        <input
                            type="hidden"
                            name="entrada"
                            value={data.entrada}
                        />

                        <input
                            type="hidden"
                            name="posicao_formulario"
                            value={data.posicao_formulario}
                        />

                        <div className="mt-8 mb-3 md:mb-5 min-[1440px]:mb-7">
                            <label className="flex items-center">
                                <label className="relative flex">
                                    <input type="checkbox" name="politica" checked={data.politica} onChange={handleChange} className="peer w-5 h-5 bg-white border-2 border-neutral-500 checked:bg-white checked:border-neutral-600 checked:bg-[length:0_0] checked:hover:bg-white checked:hover:border-neutral-600 checked:focus:bg-white checked:focus:border-neutral-600 !outline-0 !ring-0 !ring-offset-0" />
                                    <span className="peer-checked:content-[''] peer-checked:absolute peer-checked:inset-1 peer-checked:bg-black" />
                                </label>

                                <span className="max-sm:text-sm ml-2">
                                    Li e concordo com os{' '}
                                    <button
                                      type="button"
                                      onClick={() => setShowInfo(!showInfo)}
                                      className="underline focus:outline-none"
                                    >
                                      termos e condições.
                                    </button>
                                </span>
                            </label>

                            <div
                                className={`overflow-hidden transition-all duration-300 ease-in-out ${
                                    showInfo ? 'max-h-40 mt-2' : 'max-h-0'
                                }`}
                              >
                                <p className="text-xs text-neutral-700 bg-neutral-100 p-4">
                                    Ao enviar, você confirma a veracidade das informações prestadas neste formulário, bem como autoriza a UNICASA a verificar tais dados. Esteja ciente que o preenchimento de formulário não implica em nenhum compromisso para ambas as partes, em especial, não os obriga à assinatura de qualquer documento ou compromisso, sendo as informações aqui fornecidas meramente cadastrais e estritamente comerciais. A Unicasa se compromete a tratar seus dados pessoais dispostos no formulário em conformidade com a Lei Geral de Proteção de Dados (Lei 13.709/2018), sendo eliminados de maneira segura após o tempo necessário. Para mais informações, consulte nossa <a href={route('Politicas.privacidade')} target="_blank" rel="noopener noreferrer" className="underline">Política de Privacidade</a>, disponível no site.
                                </p>
                            </div>
                            {errors.politica && <p className="text-xs text-white bg-red-900 px-3 py-1.5 mt-2">{errors.politica}</p>}
                        </div>

                        <button
                            type="submit"
                            disabled={processing}
                            className="relative block bg-black px-10 py-3 mt-10 text-[15px] text-white font-medium uppercase transition-all hover:shadow hover:scale-105"
                        >
                            {!processing ? (
                                'Solicitar Projeto'
                            ) : (
                                <>
                                    <div role="status" className="absolute inset-0 flex justify-center items-center">
                                        <svg aria-hidden="true" className="w-6 h-6 text-gray-200 animate-spin fill-black" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                        </svg>
                                        <span className="sr-only">Loading...</span>
                                    </div>
                                    <span className="opacity-0">Solicitar Projeto</span>
                                </>
                            )}
                        </button>

                        {isSuccessful && (
                            <div className={`absolute inset-0 flex flex-col items-center justify-center bg-white pointer-events-none animate-fade-in-down`}>
                                <AnimatedCheckMark />

                                <h2 className="text-eng-primary text-4xl text-center font-semibold mt-4 mb-2">Successo!</h2>
                                <h4 className="font-secondary text-eng-tertiary text-2xl text-center">Sua mensagem foi enviada. Entraremos em contato em breve.</h4>
                            </div>
                        )}
                    </form>
                </div>
            </div>
        </section>
    );
};
