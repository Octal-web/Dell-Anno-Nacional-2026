import { useState, useEffect } from "react";

import { Reveal } from "./Reveal";

export const AboutSteps = ({ steps }) => {
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
        <section className="pt-10 pb-20">
            <div className="container max-w-large">
                <div className="grid grid-cols-1 gap-10 md:gap-0 md:auto-rows-[1fr]">
                {steps.map((item, index) => {
                    const isEven = index % 2 === 0;
                    const imageFirst = isMobile || isEven;

                    return (
                        <div
                            key={index}
                            className="grid grid-cols-1 md:grid-cols-2 items-stretch"
                        >
                            {imageFirst ? (
                                <>
                                    <Reveal direction="left" className="h-full">
                                        <img
                                            src={item.imagem}
                                            className={`w-full h-full object-cover ${isMobile ? "mb-6" : ""}`}
                                        />
                                    </Reveal>
                                    <Reveal
                                        direction="right"
                                        className={`max-w-[625px] my-auto md:py-10 2xl:py-16 w-full ${isMobile ? "" : "pl-16"}`}
                                    >
                                        <h2 className="text-4xl md:text-5xl 2xl:text-6xl font-secondary tracking-wide leading-snug mb-6 md:mb-8 2xl:mb-20">
                                            {item.titulo}
                                        </h2>
                                        <div className="font-secondary font-light max-md:text-justify md:leading-loose md:tracking-wide whitespace-pre-line">
                                            {item.descricao}
                                        </div>
                                    </Reveal>
                                </>
                            ) : (
                                <>
                                    <Reveal
                                        direction="left"
                                        className="max-w-[625px] w-full ml-auto pr-16 md:py-10 2xl:py-16 my-auto"
                                    >
                                        <h2 className="text-4xl md:text-5xl 2xl:text-6xl font-secondary tracking-wide leading-snug mb-6 md:mb-8 2xl:mb-20">
                                            {item.titulo}
                                        </h2>
                                        <div className="font-secondary font-light max-md:text-justify md:leading-loose md:tracking-wide whitespace-pre-line">
                                            {item.descricao}
                                        </div>
                                    </Reveal>
                                    <Reveal direction="right"  className="h-full">
                                        <img
                                            src={item.imagem}
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
