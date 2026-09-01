import { CollectionEnvironmentSlides } from './CollectionEnvironmentSlides';

export const CollectionEnvironment = ({ environment }) => {
    return (
        <section className="pt-8 md:pt-12 2xl:pt-20">
            <div className="container max-w-large">
                <div className="border-b border-neutral-300 pb-4 sm:px-4 lg:px-10">
                    <h3 className="relative w-fit mx-auto text-xl sm:text-2xl font-bold text-center uppercase leading-snug p-2 sm:p-4">
                        <span className="absolute -bottom-4 left-1/2 w-3/4 h-2 bg-black -translate-x-1/2" />
                        {environment.nome}
                    </h3>
                </div>

                {environment.descricao_curta && (
                    <p className="font-secondary font-light leading-relaxed text-center tracking-wide my-6 md:my-10 2xl:my-14">
                        {environment.descricao_curta}
                    </p>
                )}
            </div>

            <CollectionEnvironmentSlides slides={environment.imagens} />
        </section>
    );
};
