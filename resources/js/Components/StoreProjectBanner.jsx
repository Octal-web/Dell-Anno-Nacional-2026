import React, { useEffect, useRef } from 'react';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

export const StoreProjectBanner = ({ project }) => {
    const storeImageRef = useRef(null);

    useEffect(() => {
        gsap.registerPlugin(ScrollTrigger);

        const context = gsap.context(() => {
            gsap.fromTo(storeImageRef.current,
                {
                    objectPosition: '60% 100%',
                },
                {
                    objectPosition: '60% 0%',
                    duration: 1,
                    ease: 'none',
                    scrollTrigger: {
                        trigger: storeImageRef.current,
                        start: 'top bottom',
                        end: 'bottom top',
                        scrub: true
                    }
                }
            );
        });

        return () => context.revert();
    }, []);
    
    return (
        <section className="relative py-24 xl:py-32 2xl:py-40">
            <img
                ref={storeImageRef}
                src={project.banner}
                alt=""
                aria-hidden="true"
                className="absolute inset-0 h-full w-full object-cover"
            />
            <div className="absolute inset-0 bg-black/40" />

            <div className="relative container max-w-large">
                <h2 className="text-3xl sm:text-4xl 2xl:text-[45px] text-white font-light uppercase tracking-wide leading-snug">{project.nome}</h2>
            </div>
        </section>
    );
};
