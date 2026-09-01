import { useState } from "react";

import { Swiper, SwiperSlide } from "swiper/react";
import { Autoplay } from "swiper/modules";
import "swiper/swiper-bundle.css";

import Lightbox from "yet-another-react-lightbox";
import {
    Captions,
    Fullscreen,
    Thumbnails,
    Zoom,
} from "yet-another-react-lightbox/plugins";
import "yet-another-react-lightbox/styles.css";
import "yet-another-react-lightbox/plugins/captions.css";
import "yet-another-react-lightbox/plugins/thumbnails.css";

export const CollectionEnvironmentSlides = ({ slides }) => {
    const [lightboxOpen, setLightboxOpen] = useState(false);
    const [lightboxIndex, setLightboxIndex] = useState(0);

    const lightboxSlides = slides.map((slide) => ({
        src: slide.imagem_grande,
        alt: slide.detalhes,
        description: (
            <div>
                {slide.detalhes && <p>{slide.detalhes}</p>}
                {slide.acabamentos && (
                    <p className={slide.detalhes ? "mt-2" : ""}>
                        Acabamentos: {slide.acabamentos}
                    </p>
                )}
            </div>
        ),
    }));

    const openLightbox = (index) => {
        setLightboxIndex(index);
        setLightboxOpen(true);
    };

    return (
        <>
            <div className="w-full">
                <Swiper
                    slidesPerView={1.4}
                    modules={[Autoplay]}
                    loop={slides.length > 1}
                    centeredSlides={slides.length != 2}
                    spaceBetween={50}
                    breakpoints={{
                        0: {
                            spaceBetween: 20,
                        },
                        768: {
                            spaceBetween: 40,
                        },
                        1024: {
                            spaceBetween: 50,
                        },
                    }}
                >
                    {slides.map((slide, index) => (
                        <SwiperSlide key={slide.id}>
                            <button
                                type="button"
                                onClick={() => openLightbox(index)}
                                className="relative block w-full 2xl:mb-10 overflow-hidden group"
                            >
                                <img
                                    src={slide.imagem}
                                    className="w-full aspect-[3/2] md:aspect-[2.2/1] object-cover"
                                    alt={slide.detalhes || `Imagem ${index + 1}`}
                                />

                                <div className="hidden md:block absolute inset-0 bg-black opacity-0 transition-all group-hover:opacity-20" />

                                <div className="hidden md:block w-fit absolute left-1/2 top-[55%] -translate-x-1/2 -translate-y-1/2 border border-white font-light text-center uppercase py-1 md:py-2 px-8 md:min-w-40 sm:min-w-44 opacity-0 transition-all group-hover:opacity-100 group-hover:top-1/2 group/button mix-blend-screen hover:mix-blend-normal">
                                    <div className="absolute inset-0 right-auto w-full bg-white transition-all group-hover/button:w-0" />
                                    <span className="relative transition-all group-hover/button:text-white max-sm:text-sm">
                                        Ver mais
                                    </span>
                                </div>
                            </button>
                        </SwiperSlide>
                    ))}
                </Swiper>
            </div>

            <Lightbox
                open={lightboxOpen}
                close={() => setLightboxOpen(false)}
                slides={lightboxSlides}
                index={lightboxIndex}
                plugins={[Captions, Fullscreen, Thumbnails, Zoom]}
                captions={{
                    showToggle: true,
                    descriptionTextAlign: "center",
                }}
                thumbnails={{
                    position: "bottom",
                    width: "auto",
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
                className="[&_.yarl\_\_slide\_description]:font-secondary [&_.yarl\_\_slide\_description]:font-light [&_.yarl\_\_slide\_description]:pb-3 [&_.yarl\_\_thumbnails\_thumbnail]:transition-all [&_.yarl\_\_thumbnails\_thumbnail:hover]:opacity-70 [&_.yarl\_\_thumbnails\_thumbnail\_active]:!ring-2 [&_.yarl\_\_thumbnails\_thumbnail\_active]:ring-neutral-600"
            />
        </>
    );
};
