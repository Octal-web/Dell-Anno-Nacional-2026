import { useState } from 'react';
import { usePage } from '@inertiajs/react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faChevronDown } from '@fortawesome/free-solid-svg-icons';
import DefaultLayout from '@/Layouts/DefaultLayout';

export default function Page() {
    const { categorias } = usePage().props;
    const [abertas, setAbertas] = useState(() => categorias.length ? [categorias[0].id] : []);
    const alternar = (id) => setAbertas((atuais) => atuais.includes(id) ? atuais.filter((item) => item !== id) : [...atuais, id]);

    return <DefaultLayout>
        <main className="mx-auto min-h-[70vh] w-full max-w-[1110px] px-5 pb-20 pt-32 md:px-10 md:pb-28 md:pt-44">
            {categorias.map((categoria) => {
                const aberta = abertas.includes(categoria.id);
                return <section key={categoria.id}>
                    <button type="button" onClick={() => alternar(categoria.id)} aria-expanded={aberta} className="flex w-full items-center border-b border-black py-4 text-left text-sm font-light text-neutral-600 md:text-base">
                        <FontAwesomeIcon icon={faChevronDown} className={`mr-3 h-3 w-3 transition-transform duration-300 ${aberta ? 'rotate-180' : ''}`} />
                        {categoria.nome}
                    </button>
                    <div className={`grid transition-[grid-template-rows,opacity] duration-300 ${aberta ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'}`}>
                        <div className="overflow-hidden">
                            <div className="grid grid-cols-2 gap-x-3 gap-y-5 py-10 sm:grid-cols-3 md:grid-cols-4 md:gap-x-4">
                                {categoria.acabamentos.map((acabamento) => <article key={acabamento.id}>
                                    <img src={acabamento.imagem} alt={acabamento.nome || ''} className="aspect-[50/27] w-full object-cover" loading="lazy" />
                                    <h2 className="mt-1 text-sm font-light text-neutral-600">{acabamento.nome}</h2>
                                </article>)}
                            </div>
                        </div>
                    </div>
                </section>;
            })}
        </main>
    </DefaultLayout>;
}
