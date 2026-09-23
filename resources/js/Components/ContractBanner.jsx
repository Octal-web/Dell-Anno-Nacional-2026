import { useEffect, useRef } from "react";
import LetterReveal from "./LetterReveal";

import { gsap } from "gsap";

import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

export const ContractBanner = ({ content }) => {
    const imageRef = useRef(null);

    useEffect(() => {
        gsap.fromTo(
            imageRef.current,
            {
                backgroundPositionY: "100%",
            },
            {
                backgroundPositionY: "0%",
                duration: 1,
                ease: "none",
                scrollTrigger: {
                    trigger: imageRef.current,
                    start: "top bottom",
                    end: "bottom top",
                    scrub: true,
                },
            },
        );
    }, []);
    return (
        <section>
            <div
                ref={imageRef}
                className="h-[50vh] max-[430px]:bg-[length:auto_120%] max-[570px]:bg-[length:200%] sm:bg-[length:170%] 2xl:bg-[length:100%]"
                style={{
                    backgroundImage: `url(${content.imagem})`,
                }}
            />

            <div className="container max-w-large py-10 md:py-16 mt-5 2xl:py-20">
                <LetterReveal
                    className="text-4xl md:text-5xl 2xl:text-[55px] font-light uppercase sm:tracking-wide text-balance text-center mb-6 md:mb-10"
                    text={content.titulo}
                    element="h1"
                />

                <p className="font-secondary font-light text-justify md:text-center sm:tracking-wide sm:leading-loose whitespace-pre-line max-w-[1000px] mx-auto mb-10">
                    {content.texto}
                </p>
            </div>
        </section>
    );
};
