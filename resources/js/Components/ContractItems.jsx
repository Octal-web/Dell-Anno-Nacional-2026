import { useEffect, useState } from "react";

import { Reveal } from "./Reveal";

import Lightbox from "yet-another-react-lightbox";
import {
    Fullscreen,
    Thumbnails,
    Zoom,
} from "yet-another-react-lightbox/plugins";
import "yet-another-react-lightbox/plugins/thumbnails.css";
import "yet-another-react-lightbox/styles.css";

export const ContractItems = ({ items }) => {
    const [isMobile, setIsMobile] = useState(false);

    useEffect(() => {
        const checkMobile = () => {
            setIsMobile(window.innerWidth < 768);
        };

        checkMobile();
        window.addEventListener("resize", checkMobile);

        return () => window.removeEventListener("resize", checkMobile);
    }, []);

    return (
        <section className="pt-10 pb-20 md:pb-52">
            <div className="container max-w-large">
                <div className="grid grid-cols-1 gap-10 md:gap-0 md:auto-rows-[1fr]">
                    {items.map((item, index) => {
                        const isEven = index % 2 !== 0;
                        const imageFirst = isMobile || isEven;

                        return (
                            <div
                                key={index}
                                className="grid grid-cols-1 md:grid-cols-2 items-stretch py-5 md:py-10"
                            >
                                {imageFirst ? (
                                    <>
                                        <Reveal
                                            direction="left"
                                            className="h-full"
                                        >
                                            <img
                                                src={item.banner}
                                                className={`w-full h-full object-cover ${isMobile ? "mb-6" : ""}`}
                                            />
                                        </Reveal>
                                        <Reveal
                                            direction="right"
                                            className={` my-auto md:py-10 2xl:py-16 w-full ${isMobile ? "" : "pl-16"}`}
                                        >
                                            <Content item={item} />
                                        </Reveal>
                                    </>
                                ) : (
                                    <>
                                        <Reveal
                                            direction="left"
                                            className=" w-full ml-auto pr-16 md:py-10 2xl:py-16 my-auto"
                                        >
                                            <Content item={item} />
                                        </Reveal>
                                        <Reveal
                                            direction="right"
                                            className="h-full"
                                        >
                                            <img
                                                src={item.banner}
                                                className="w-full h-full object-cover"
                                            />
                                        </Reveal>
                                    </>
                                )}
                            </div>
                        );
                    })}
                </div>
            </div>
        </section>
    );
};

const Content = ({ item }) => {
    const [lightboxOpen, setLightboxOpen] = useState(false);

    const lightboxSlides = item.imagens.map((slide) => ({
        src: slide.imagem,
    }));

    return (
        <>
            <h2 className="text-4xl md:text-5xl font-light uppercase tracking-wide pt-2 md:pt-0">
                {item.titulo}
            </h2>
            <h3 className="text-lg md:text-2xl font-light uppercase tracking-wide my-4 md:my-8">
                {item.subtitulo}
            </h3>
            <div className="font-secondary font-light max-md:text-justify md:leading-loose md:tracking-wide md:max-w-[560px] whitespace-pre-line">
                {item.texto}
            </div>

            {item.imagens.length > 0 && (
                <button
                    onClick={() => setLightboxOpen(true)}
                    className="mt-4 md:mt-8 border border-neutral-800 bg-white font-light text-center px-3 py-1.5 min-w-40 sm:min-w-44 transition-all hover:bg-black hover:text-white uppercase"
                >
                    Ver mais
                </button>
            )}

            <Lightbox
                open={lightboxOpen}
                close={() => setLightboxOpen(false)}
                plugins={[Fullscreen, Thumbnails, Zoom]}
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
                className="[&_.yarl\_\_thumbnails\_thumbnail]:transition-all [&_.yarl\_\_thumbnails\_thumbnail:hover]:opacity-70 [&_.yarl\_\_thumbnails\_thumbnail\_active]:ring-2 [&_.yarl\_\_thumbnails\_thumbnail\_active]:ring-neutral-600"
            />
        </>
    );
};
