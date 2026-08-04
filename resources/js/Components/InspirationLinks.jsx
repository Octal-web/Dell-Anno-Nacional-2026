import { Link } from "@inertiajs/react";

export const InspirationLinks = ({ content }) => {
    return (
        <section className="py-16 md:py-24 2xl:py-30">
            <div className="container max-w-large">
                <div className="grid grid-cols-1 md:grid-cols-3 max-sm:gap-y-10 gap-x-8">
                    <Link href={content[0].link} className="group">
                        <div className="overflow-hidden mb-4 md:mb-10">
                            <img
                                src={content[0].imagem}
                                className="transition-all duration-500 group-hover:scale-110"
                                alt={content[0].titulo}
                            />
                        </div>
                        <h3 className="text-xl md:text-2xl 2xl:text-[30px] font-light text-center uppercase tracking-wide">
                            {content[0].titulo}
                        </h3>
                    </Link>

                    <Link
                        href={content[1].link}
                        className="group"
                    >
                        <div className="overflow-hidden mb-4 md:mb-10">
                            <img
                                src="/site/img/inspiration-2.jpg"
                                className="transition-all duration-500 group-hover:scale-110"
                                alt={content[1].titulo}
                            />
                        </div>
                        <h3 className="text-xl md:text-2xl 2xl:text-[30px] font-light text-center uppercase tracking-wide">
                            {content[1].titulo}
                        </h3>
                    </Link>

                    <Link href={content[2].link} className="group">
                        <div className="overflow-hidden mb-4 md:mb-10">
                            <img
                                src="/site/img/inspiration-3.jpg"
                                className="transition-all duration-500 group-hover:scale-110"
                                alt={content[2].titulo}
                            />
                        </div>
                        <h3 className="text-xl md:text-2xl 2xl:text-[30px] font-light text-center uppercase tracking-wide">
                            {content[2].titulo}
                        </h3>
                    </Link>
                </div>
            </div>
        </section>
    );
};
