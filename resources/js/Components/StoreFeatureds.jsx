import { Link } from '@inertiajs/react';

import { Reveal } from './Reveal';
import { StoreFeaturedsSlides } from './StoreFeaturedsSlides';

export const StoreFeatureds = ({ images, showroomSlug }) => {
    return (
        <section className="pb-10 md:pb-30 mt-2 md:mt-10 2xl:mt-20">
            <div className="container max-w-large">
                <Reveal direction="bottom" scale={true}>
                    <h2 className="text-3xl text-center font-light uppercase sm:tracking-wide sm:leading-snug mb-6 md:mb-12 2xl:mb-16">Showroom - Detalhes</h2>
                </Reveal>

                <StoreFeaturedsSlides slides={images} />

                {showroomSlug && (
                    <Reveal direction="bottom">
                        <Link
                            href={route('Showrooms.showroom', { slug: showroomSlug })}
                            className="block w-fit mx-auto border border-neutral-800 bg-white font-light text-center uppercase py-2 px-8 min-w-40 sm:min-w-44 transition-all hover:bg-black hover:text-white"
                        >
                            Ver mais
                        </Link>
                    </Reveal>
                )}
            </div>
        </section>
    );
};
