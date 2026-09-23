import { useEffect, useRef } from "react";

import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

export const AboutPainting = ({ content }) => {
    const paintingbgRef = useRef(null);

    useEffect(() => {
        gsap.fromTo(
            paintingbgRef.current,
            {
                backgroundPositionY: "100%",
            },
            {
                backgroundPositionY: "0%",
                duration: 1,
                ease: "none",
                scrollTrigger: {
                    trigger: paintingbgRef.current,
                    start: "top bottom",
                    end: "bottom top",
                    scrub: true,
                },
            },
        );
    }, []);

    return (
        <section id="sustentabilidade">
            <div
                ref={paintingbgRef}
                className="h-[80vh] max-[430px]:bg-[length:auto_120%] max-[570px]:bg-[length:200%] sm:bg-[length:170%] bg-[60%] 2xl:bg-[length:100%] bg-no-repeat bg-cover"
                style={{
                    backgroundImage: `url(${content.imagem})`,
                }}
            />
        </section>
    );
};
