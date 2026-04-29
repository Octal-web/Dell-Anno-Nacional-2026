import React from 'react';
import LetterReveal from './LetterReveal';

export const StoreShowroomVideo = ({ video, cover }) => {
    return (
        <section className="pb-20">
            <LetterReveal className="text-3xl text-center font-light uppercase sm:tracking-wide sm:leading-snug mb-6 md:mb-8 2xl:mb-10" text="Showroom" element="h2" />

            <div className="relative aspect-[48/25] max-h-[85vh] w-full">
                <video
                    className="w-full h-full object-cover"
                    autoPlay
                    muted
                    loop
                    playsInline
                    poster={cover}
                >
                    <source src={video} />
                </video>
            </div>
        </section>
    );
};