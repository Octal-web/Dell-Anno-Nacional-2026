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
            <div className="container max-w-large flex flex-col md:flex-row justify-between py-10 md:py-16 mt-10 2xl:py-20">
                <LetterReveal
                    className="text-4xl md:text-5xl 2xl:text-[55px] font-light text-center uppercase tracking-wide mb-6 md:mb-10"
                    text={content.titulo}
                    element="h1"
                />
                <p className="font-secondary font-light text-justify md:text-left sm:tracking-wide sm:leading-loose whitespace-pre-line max-w-[580px] mb-10 md:ml-auto">
                    {content.texto}
                </p>
            </div>

            <div
                ref={imageRef}
                className="h-[55vh] max-[430px]:bg-[length:auto_120%] max-[570px]:bg-[length:200%] sm:bg-[length:170%] 2xl:bg-[length:100%]"
                style={{
                    backgroundImage: `url(${content.imagem})`,
                }}
            />
        </section>
    );
};
