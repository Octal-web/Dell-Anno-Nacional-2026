import React, { useRef, useState } from 'react';

import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay, Navigation } from 'swiper/modules'
import 'swiper/swiper-bundle.css';

import Lightbox from "yet-another-react-lightbox";
import { Fullscreen, Thumbnails, Zoom } from "yet-another-react-lightbox/plugins";
import "yet-another-react-lightbox/styles.css";
import "yet-another-react-lightbox/plugins/thumbnails.css";

export const StoreFeaturedsSlides = ({ slides }) => {
    const prevButtonRef = useRef(null);
    const nextButtonRef = useRef(null);

    const [lightboxOpen, setLightboxOpen] = useState(false);
    const [currentIndex, setCurrentIndex] = useState(0);

    if (!slides) return;

    const lightboxSlides = slides.map(slide => ({
        src: slide.imagem
    }));

    const openLightbox = (index) => {
        setCurrentIndex(index);
        setLightboxOpen(true);
    };

    // Slides duplicados para o loop funcionar com poucas imagens
    const loopSlides = [...slides, ...slides];

    return (
        <section className="pb-20">
            <div className="relative">
                <Swiper
                    slidesPerView={2}
                    centeredSlides={true}
                    modules={[Autoplay, Navigation]}
                    autoplay={{ delay: 8000 }}
                    loop={true}
                    onBeforeInit={(swiper) => {
                        swiper.params.navigation.prevEl = prevButtonRef.current;
                        swiper.params.navigation.nextEl = nextButtonRef.current;
                    }}
                    navigation={{
                        prevEl: prevButtonRef.current,
                        nextEl: nextButtonRef.current,
                    }}
                    breakpoints={{
                        0: {
                            slidesPerView: 1.3,
                            centeredSlides: true,
                        },
                        768: {
                            slidesPerView: 2,
                            centeredSlides: true,
                        },
                    }}
                    className="!overflow-visible [&_.swiper-slide:not(.swiper-slide-active)_img]:scale-[0.85] md:[&_.swiper-slide:not(.swiper-slide-active)_img]:scale-[0.8]"
                >
                    {loopSlides.map((slide, index) =>
                        <SwiperSlide key={index}>
                            <div className="cursor-pointer" onClick={() => openLightbox(index % slides.length)}>
                                <img src={slide.imagem} className="aspect-[13/9] object-cover transition-all" alt={`Showroom ${(index % slides.length) + 1}`} />
                            </div>
                        </SwiperSlide>
                    )}
                </Swiper>

                <button
                    ref={prevButtonRef}
                    type="button"
                    aria-label="Anterior"
                    className="absolute top-1/2 left-0 md:left-4 -translate-y-1/2 z-10 w-12 h-12 md:w-14 md:h-14 flex items-center justify-center rounded-full bg-white bg-opacity-80 shadow-lg transition-all hover:bg-opacity-100"
                >
                    <ArrowIcon className="rotate-180 fill-none stroke-neutral-800" />
                </button>

                <button
                    ref={nextButtonRef}
                    type="button"
                    aria-label="Próximo"
                    className="absolute top-1/2 right-0 md:right-4 -translate-y-1/2 z-10 w-12 h-12 md:w-14 md:h-14 flex items-center justify-center rounded-full bg-white bg-opacity-80 shadow-lg transition-all hover:bg-opacity-100"
                >
                    <ArrowIcon className="fill-none stroke-neutral-800" />
                </button>
            </div>

            <Lightbox
                open={lightboxOpen}
                close={() => setLightboxOpen(false)}
                plugins={[Fullscreen, Thumbnails, Zoom]}
                thumbnails={{
                    position: "bottom",
                    width: 'auto',
                    height: 80,
                    border: 0,
                    borderRadius: 0,
                    padding: 4,
                    gap: 16,
                }}
                zoom={{
                    maxZoomPixelRatio: 3,
                    zoomInMultiplier: 2,
                    doubleTapDelay: 300,
                    doubleClickDelay: 300,
                    doubleClickMaxStops: 2,
                    keyboardMoveDistance: 50,
                    wheelZoomDistanceFactor: 100,
                    pinchZoomDistanceFactor: 100,
                }}
                slides={lightboxSlides}
                index={currentIndex}
                className="[&_.yarl\_\_thumbnails\_thumbnail]:transition-all [&_.yarl\_\_thumbnails\_thumbnail:hover]:opacity-70 [&_.yarl\_\_thumbnails\_thumbnail\_active]:ring-2 [&_.yarl\_\_thumbnails\_thumbnail\_active]:ring-neutral-600"
            />
        </section>
    );
};

const ArrowIcon = ({ className }) => {
    return (
        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="11.013"
            height="18"
            viewBox="0 0 11.013 18"
            className={className}
        >
            <path
                d="M0,0,9.4,8.05,0,16.107"
                transform="translate(0.946 0.946)"
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeMiterlimit="10"
                strokeWidth="1.342"
            />
        </svg>
    );
};
