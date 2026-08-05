import { Link } from "@inertiajs/react";
import { Reveal } from "./Reveal";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPlus } from "@fortawesome/free-solid-svg-icons";

export const BlogPostsList = ({ data }) => {
    return (
        <section className="pt-20 md:pt-24 2xl:pt-30 pb-20 sm:pb-24 md:pb-32 2xl:pb-44">
            <div className="container max-w-large">
                <div className="grid grid-cols-2 md:grid-cols-3 gap-2">
                    {data.map((item, index) => (
                        <Reveal
                            direction="bottom"
                            scale={true}
                            className="group flex flex-col"
                            key={index}
                        >
                            <Link
                                href={route("Blog.post", { slug: item.slug })}
                                className="overflow-hidden aspect-[4/5] md:aspect-[5/6] relative"
                            >
                                <img
                                    src={item.imagem}
                                    className="w-full h-full object-cover transition-all duration-500 group-hover:scale-110"
                                    alt={item.titulo}
                                />

                                <div className="block absolute inset-0 bg-black opacity-0 transition-all duration-300 group-hover:opacity-50" />

                                <div className=" absolute inset-0 opacity-0 transition-all duration-300 group-hover:opacity-100 z-10 flex items-end">
                                    <div className="hidden absolute inset-0 lg:flex items-center justify-center">
                                        <div className="bg-white/80 rounded-full px-4 py-3 shadow-lg">
                                            <FontAwesomeIcon icon={faPlus} />
                                        </div>
                                    </div>

                                    <h3 className="text-lg sm:text-xl md:text-2xl 2xl:text-[26px] font-light max-sm:leading-tight self-end mx-auto max-w-sm pb-10 text-white text-center">
                                        {item.titulo}
                                    </h3>
                                </div>
                            </Link>
                        </Reveal>
                    ))}
                </div>
            </div>
        </section>
    );
};
