import { Link } from '@inertiajs/react';

import { Reveal } from './Reveal';

export const ContactBenefits = ({ items }) => {
    if (!items?.length) return;

    return (
        <section className="py-16 md:py-24 2xl:py-30">
            <div className="container max-w-medium">
                <div className="grid grid-cols-1 md:grid-cols-3 gap-12 md:gap-10 2xl:gap-16 mb-14 md:mb-20">
                    {items.map((item, index) => (
                        <Reveal key={item.id} delay={index} direction="bottom">
                            <div className="flex items-center gap-4 mb-4 md:mb-6">
                                <span className="text-5xl 2xl:text-6xl text-neutral-400 font-light leading-none">{String(index + 1).padStart(2, '0')}</span>
                                <h3 className="text-xl 2xl:text-2xl font-medium uppercase leading-tight">{item.titulo}</h3>
                            </div>
                            <div className="font-secondary font-light leading-relaxed tracking-wide text-neutral-600" dangerouslySetInnerHTML={{ __html: item.texto }} />
                        </Reveal>
                    ))}
                </div>

                <Reveal direction="bottom">
                    <Link
                        href={route('Institucional.index')}
                        className="block w-fit mx-auto border border-neutral-800 bg-white font-light text-center uppercase py-2 px-8 min-w-40 sm:min-w-44 transition-all hover:bg-black hover:text-white"
                    >
                        Quero conhecer mais sobre a Dell Anno
                    </Link>
                </Reveal>
            </div>
        </section>
    );
};
