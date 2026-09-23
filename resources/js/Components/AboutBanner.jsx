import { useEffect, useRef } from "react";
import LetterReveal from "./LetterReveal";
import { Reveal } from "./Reveal";

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

export const AboutBanner = ({ content }) => {
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
        <section className="pb-16 md:pb-20 2xl:pb-30">
            <div
                ref={imageRef}
                className="h-[88vh] max-[430px]:bg-[length:auto_120%] max-[570px]:bg-[length:200%] sm:bg-[length:170%] bg-[60%] 2xl:bg-[length:100%] bg-no-repeat bg-cover"
                style={{
                    backgroundImage: `url(${content.imagem})`,
                }}
            />

            <img
                src="/site/img/logo.svg"
                className="ml-auto mb-14 md:mb-32 w-[55vw] opacity-5"
            />
            <div className="container max-w-large">
                <Reveal direction="bottom" scale={true}>
                    <div className="flex flex-col gap-5 md:flex-row justify-between items-center mb-16 md:mb-24 2xl:mb-36">
                    <LetterReveal className='text-3xl md:text-4xl 2xl:text-5xl max-w-[650px] font-secondary' text={content.titulo} />

                    <p className="font-secondary text-left font-light md:leading-loose md:tracking-wide max-w-[500px]">
                        {content.texto}
                    </p>
                    </div>

                    <div className="border-b-2" />
                </Reveal>
            </div>
        </section>
    );
};
