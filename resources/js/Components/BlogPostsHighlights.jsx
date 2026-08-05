import { useState } from "react";
import { Link } from "@inertiajs/react";
import { Swiper, SwiperSlide } from "swiper/react";
import { EffectFade, Autoplay } from "swiper/modules";

import "swiper/css";
import "swiper/css/effect-fade";

export const BlogPostsHighlights = ({ data }) => {
    const [currentSlide, setCurrentSlide] = useState(0);
    const [swiperRef, setSwiperRef] = useState(null);

    if (!data?.length) {
        return null;
    }
    console.log(data)

    const totalSlides = data.length;

    const formatNumber = (number) => {
        return number.toString().padStart(2, "0");
    };

    const handleSlideChange = (swiper) => {
        setCurrentSlide(swiper.realIndex);
    };

    const goToPrevSlide = () => {
        swiperRef?.slidePrev();
    };

    const goToNextSlide = () => {
        swiperRef?.slideNext();
    };

    return (
        <section className="pt-10 md:pt-20 2xl:pt-24">
            <div className="container max-w-large">
                <Swiper
                    onSwiper={setSwiperRef}
                    onSlideChange={handleSlideChange}
                    slidesPerView={1}
                    effect="fade"
                    speed={900}
                    rewind
                    autoplay={{
                        delay: 5000,
                        disableOnInteraction: false,
                        stopOnLastSlide: false,
                        pauseOnMouseEnter: false,
                    }}
                    fadeEffect={{
                        crossFade: true,
                    }}
                    modules={[EffectFade]}
                    allowTouchMove={totalSlides > 1}
                    autoHeight
                >
                    {data.map((item, index) => (
                        <SwiperSlide key={item.id ?? index}>
                            <div className="grid grid-cols-1 gap-10 md:grid-cols-2 md:gap-0">
                                <div className="aspect-[16/13] overflow-hidden">
                                    <img
                                        src={item.imagem}
                                        className="h-full w-full object-cover"
                                        alt={item.nome}
                                    />
                                </div>
                                
                                <div className="relative flex h-full items-center px-6 md:px-[10%]">
                                    <div className="w-full max-w-[58.5rem]">
                                        <div className="hidden md:block absolute top-0 flex items-center text-sm font-light text-gray-400 sm:tracking-widest">
                                            <button
                                                type="button"
                                                onClick={goToPrevSlide}
                                                className="mr-4 transition-colors hover:text-gray-600 disabled:cursor-default disabled:opacity-50"
                                                disabled={currentSlide === 0}
                                                aria-label="Ir para o slide anterior"
                                            >
                                                <svg
                                                    width="12"
                                                    height="18"
                                                    viewBox="0 0 12 18"
                                                    fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <path
                                                        d="M10 2L3 9L10 16"
                                                        stroke="currentColor"
                                                        strokeWidth="1.5"
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                    />
                                                </svg>
                                            </button>

                                            <span className="mx-3">
                                                {formatNumber(currentSlide + 1)} / {formatNumber(totalSlides)}
                                            </span>

                                            <button
                                                type="button"
                                                onClick={goToNextSlide}
                                                className="ml-4 transition-colors hover:text-gray-600 disabled:cursor-default disabled:opacity-50"
                                                disabled={currentSlide === totalSlides - 1}
                                                aria-label="Ir para o próximo slide"
                                            >
                                                <svg
                                                    width="12"
                                                    height="18"
                                                    viewBox="0 0 12 18"
                                                    fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <path
                                                        d="M2 16L9 9L2 2"
                                                        stroke="currentColor"
                                                        strokeWidth="1.5"
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                    />
                                                </svg>
                                            </button>
                                        </div>

                                        <Link
                                            href={route("Blog.post", {
                                                slug: item.slug,
                                            })}
                                            className="mb-6 block md:mb-10"
                                            aria-label={item.titulo}
                                        >
                                            <h3 className="text-3xl font-light uppercase">
                                                {item.titulo}
                                            </h3>
                                        </Link>

                                        <p className="mb-8 max-w-lg whitespace-pre-line font-secondary font-light sm:tracking-wide md:mb-12">
                                            {item.previa}
                                        </p>

                                        <Link
                                            href={route("Blog.post", {
                                                slug: item.slug,
                                            })}
                                            className="block w-fit min-w-40 border border-neutral-800 bg-white px-8 py-2 text-center font-light uppercase transition-all hover:bg-black hover:text-white sm:min-w-44"
                                            aria-label={`Saiba mais sobre ${item.titulo}`}
                                        >
                                            Saiba mais
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </SwiperSlide>
                    ))}
                </Swiper>
            </div>
        </section>
    );
};